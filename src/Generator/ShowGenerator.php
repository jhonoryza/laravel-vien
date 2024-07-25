<?php

namespace Jhonoryza\Vien\Generator;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Jhonoryza\Vien\Concern\FileGenerator;
use Jhonoryza\Vien\Concern\ReplaceResourceKeywords;

class ShowGenerator implements FileGenerator
{
    use CommonGeneratorTrait;
    use ReplaceResourceKeywords;

    protected string $filename;

    protected string $path;

    protected string $stubFilename = 'show.stub';

    public function __construct(public Filesystem $filesystem, public string $tableName)
    {
        $this->path     = base_path(config('laravel-vien.generator.view.path')) . Str::studly(Str::singular($this->tableName));
        $this->filename = $this->generateFilename();
    }

    private function generateFilename(): string
    {
        return 'Show.vue';
    }

    public function getContent(): string
    {
        return $this->manipulateContent(
            (string) file_get_contents($this->getStubPath($this->stubFilename))
        );
    }

    private function manipulateContent(string $content): string
    {
        $content = str_replace(
            [
                '//View Form',
            ],
            [
                $this->getViewForm(),
            ],
            $content
        );

        return $this->replaceKeywords($content);
    }
}
