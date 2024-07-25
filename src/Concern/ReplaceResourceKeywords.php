<?php

namespace Jhonoryza\Vien\Concern;

use Illuminate\Support\Str;

trait ReplaceResourceKeywords
{
    /**
     * Array of keywords related to the database resource.
     *
     * @var string[]
     */
    protected static array $keywords = [
        'StudlyDummies',
        'StudlyDummy',
        'camelDummies',
        'camelDummy',
        'snake_dummies',
        'snake_dummy',
        'kebab-dummies',
        'kebab-dummy',
        'Big Title Dummies',
        'Big Title Dummy',
        'small title dummies',
        'small title dummy',
    ];

    /**
     * Get the `Big Title Dummies` replacement value.
     */
    protected function getBigTitleDummies(): string
    {
        return Str::title(
            str_replace('_', ' ', $this->getSnakeDummies())
        );
    }

    /**
     * Get the `Big Title Dummy` replacement value.
     */
    protected function getBigTitleDummy(): string
    {
        return Str::title(
            str_replace('_', ' ', $this->getSnakeDummy())
        );
    }

    /**
     * Get the `camelDummies` replacement value.
     */
    protected function getCamelDummies(): string
    {
        return Str::camel($this->getPluralName());
    }

    /**
     * Get the `camelDummy` replacement value.
     */
    protected function getCamelDummy(): string
    {
        return Str::camel($this->getSingularName());
    }

    /**
     * Get the `kebab-dummies` replacement value.
     */
    protected function getKebabDummies(): string
    {
        return Str::kebab($this->getSmallTitleDummies());
    }

    /**
     * Get the `kebab-dummy` replacement value.
     */
    protected function getKebabDummy(): string
    {
        return Str::kebab($this->getSmallTitleDummy());
    }

    /**
     * Get the plural table name.
     */
    private function getPluralName(): string
    {
        return Str::plural($this->tableName);
    }

    /**
     * Get the singular table name.
     */
    private function getSingularName(): string
    {
        return Str::singular($this->tableName);
    }

    /**
     * Get the `small title dummies` replacement value.
     */
    protected function getSmallTitleDummies(): string
    {
        return strtolower($this->getBigTitleDummies());
    }

    /**
     * Get the `small title dummy` replacement value.
     */
    protected function getSmallTitleDummy(): string
    {
        return strtolower($this->getBigTitleDummy());
    }

    /**
     * Get the `snake_dummies` replacement value.
     */
    protected function getSnakeDummies(): string
    {
        return Str::snake($this->getPluralName());
    }

    /**
     * Get the `snake_dummy` replacement value.
     */
    protected function getSnakeDummy(): string
    {
        return Str::snake($this->getSingularName());
    }

    /**
     * Get `StudlyDummies` replacement value.
     */
    protected function getStudlyDummies(): string
    {
        return Str::studly($this->getPluralName());
    }

    /**
     * Get `StudlyDummy` replacement value.
     */
    protected function getStudlyDummy(): string
    {
        return Str::studly($this->getSingularName());
    }

    /**
     * Replace all dummy keywords in the given content with the real values.
     */
    public function replaceKeywords(string $content): string
    {
        return str_replace(
            self::$keywords,
            [
                $this->getStudlyDummies(),
                $this->getStudlyDummy(),
                $this->getCamelDummies(),
                $this->getCamelDummy(),
                $this->getSnakeDummies(),
                $this->getSnakeDummy(),
                $this->getKebabDummies(),
                $this->getKebabDummy(),
                $this->getBigTitleDummies(),
                $this->getBigTitleDummy(),
                $this->getSmallTitleDummies(),
                $this->getSmallTitleDummy(),
            ],
            $content
        );
    }
}
