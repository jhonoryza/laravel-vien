<?php

namespace Jhonoryza\Vien\Generator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait CommonGeneratorTrait
{
    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getFullPath(): string
    {
        return $this->path . '/' . $this->filename;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    protected function getStubPath(string $filename): string
    {
        $customPath = app()->basePath('stubs/vien_generator/' . $filename);
        return file_exists($customPath)
            ? $customPath
            : __DIR__ . DIRECTORY_SEPARATOR . 'Stubs' . DIRECTORY_SEPARATOR . $filename;
    }

    protected function indent(string $line, int $times = 1): string
    {
        return str_repeat(' ', $times * 4) . $line;
    }

    public function getImplodeSelect(): string
    {
        $columns = Schema::getColumnListing($this->tableName);
        $columns = collect($columns)
            ->filter(fn($item) => !in_array($item, ['id', 'created_at', 'updated_at']))
            ->all();
        $attribute = '';
        foreach ($columns as $column) {
            $col = sprintf("'%s'", $column);
            $attribute .= $col . ', ';
        }
        return rtrim($attribute);
    }

    public function getQueryFilters(): string
    {
        $columns = Schema::getColumnListing($this->tableName);
        $columns = collect($columns)
            ->filter(fn($item) => !in_array($item, ['id', 'created_at', 'updated_at']))
            ->all();
        $attribute = '';
        $template = "
            ->when(
                request('filter.%s'),
                fn (Builder \$query, \$filter) => \$query->where('%s', \$filter)
            )
        ";
        foreach ($columns as $column) {
            $col = sprintf($template, $column, $column);
            $attribute .= $this->indent($col, 3);
        }
        return rtrim($attribute);
    }

    public function getImplodeFilters(): string
    {
        $columns = Schema::getColumnListing($this->tableName);
        $columns = collect($columns)
            ->filter(fn($item) => !in_array($item, ['id', 'created_at', 'updated_at']))
            ->all();
        $attribute = '';
        foreach ($columns as $column) {
            $col = sprintf("'%s',", $column);
            $attribute .= $this->indent($col, 5) . PHP_EOL;
        }
        return rtrim($attribute);
    }

    public function getModelColumns(): string
    {
        $columns = Schema::getColumnListing($this->tableName);
        $attribute = '';
        $template = "['key' => '%s', 'label' => '%s', 'visible' => true, 'sortable' => true],";
        foreach ($columns as $column) {
            $title = Str::title(
                str_replace('_', ' ', Str::snake(Str::singular($column)))
            );
            $col = sprintf($template, $column, $title);
            $attribute .= $this->indent($col, 5) . PHP_EOL;
        }
        return rtrim($attribute);
    }

    public function getModelAttribute(): string
    {
        $columns = Schema::getColumnListing($this->tableName);
        $attribute = '';
        $template = "'%s' => %s,";
        foreach ($columns as $column) {
            $col = sprintf($template, $column, '$camelDummy->' . $column);
            $attribute .= $this->indent($col, 4) . PHP_EOL;
        }
        return rtrim($attribute);
    }

    public function getValidationRules(): string
    {
        $columns = DB::getSchemaBuilder()->getColumns($this->tableName);
        $columns = collect($columns)
            ->filter(fn($item) => !in_array($item['name'], ['id', 'created_at', 'updated_at']))
            ->all();

        $attribute = '';
        $template = "'%s' => '%s',";
        foreach ($columns as $column) {
            $col = sprintf($template, $column['name'], !$column['nullable'] ? 'required' : 'nullable');
            $attribute .= $this->indent($col, 5) . PHP_EOL;
        }
        return rtrim($attribute);
    }
}