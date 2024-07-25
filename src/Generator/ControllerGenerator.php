<?php

namespace Jhonoryza\Vien\Generator;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Jhonoryza\Vien\Concern\FileGenerator;
use Jhonoryza\Vien\Concern\ReplaceResourceKeywords;

class ControllerGenerator implements FileGenerator
{
    use CommonGeneratorTrait;
    use ReplaceResourceKeywords;

    protected string $filename;

    protected string $path;

    protected string $stubFilename = 'controller.stub';

    public function __construct(public Filesystem $filesystem, public string $tableName)
    {
        $this->path     = base_path(config('laravel-vien.generator.controller.path'));
        $this->filename = $this->generateFilename();
    }

    private function generateFilename(): string
    {
        return Str::studly(Str::plural($this->tableName)) . 'Controller.php';
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
                'ControllerNamespace',
                'ModelNamespace',
                '//Model Attributes',
                '//Builder Attributes',
                '//Model Columns',
                'implode_select',
                '//Query Filters',
                '//Implode Filters',
                '//Validation Rules',
            ],
            [
                config('laravel-vien.generator.controller.namespace'),
                config('laravel-vien.generator.model.namespace'),
                $this->getModelAttribute(),
                $this->getModelAttribute(true),
                $this->getModelColumns(),
                $this->getImplodeSelect(),
                $this->getQueryFilters(),
                $this->getImplodeFilters(),
                $this->getValidationRules(),
            ],
            $content
        );

        return $this->replaceKeywords($content);
    }
}
