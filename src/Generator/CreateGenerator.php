<?php

namespace Jhonoryza\Vien\Generator;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Jhonoryza\Vien\Concern\FileGenerator;
use Jhonoryza\Vien\Concern\ReplaceResourceKeywords;

class CreateGenerator implements FileGenerator
{
    use CommonGeneratorTrait;
    use ReplaceResourceKeywords;

    protected string $filename;

    protected string $path;

    protected string $stubFilename = 'create.stub';

    public function __construct(public Filesystem $filesystem, public string $tableName)
    {
        $this->path     = base_path(config('laravel-vien.generator.view.path')) . Str::studly(Str::singular($this->tableName));
        $this->filename = $this->generateFilename();
    }

    private function generateFilename(): string
    {
        return 'Create.vue';
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
                '//Create Form',
                '//UseForm Create',
            ],
            [
                $this->getCreateForm(),
                $this->getUseFormCreate(),
            ],
            $content
        );

        return $this->replaceKeywords($content);
    }
}
