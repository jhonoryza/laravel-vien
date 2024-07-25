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
            ->filter(fn ($item) => ! in_array($item, ['id', 'created_at', 'updated_at']))
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
            ->filter(fn ($item) => ! in_array($item, ['id', 'created_at', 'updated_at']))
            ->all();
        $attribute = '';
        $template  = "
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
            ->filter(fn ($item) => ! in_array($item, ['id', 'created_at', 'updated_at']))
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
        $columns   = Schema::getColumnListing($this->tableName);
        $attribute = '';
        $template  = "['key' => '%s', 'label' => '%s', 'visible' => true, 'sortable' => true],";
        foreach ($columns as $column) {
            $title = Str::title(
                str_replace('_', ' ', Str::snake(Str::singular($column)))
            );
            $col = sprintf($template, $column, $title);
            $attribute .= $this->indent($col, 5) . PHP_EOL;
        }

        return rtrim($attribute);
    }

    public function getModelAttribute(bool $asBuilder = false): string
    {
        $columns   = Schema::getColumnListing($this->tableName);
        $attribute = '';
        $template  = "'%s' => %s,";
        foreach ($columns as $column) {
            $format = $column === 'created_at' || $column === 'updated_at' ? "->format('d F Y H:i')" : '';
            $format = $asBuilder ? '' : $format;
            $col    = sprintf($template, $column, '$camelDummy->' . $column . $format);
            $attribute .= $this->indent($col, 4) . PHP_EOL;
        }

        return rtrim($attribute);
    }

    public function getValidationRules(): string
    {
        $columns = DB::getSchemaBuilder()->getColumns($this->tableName);
        $columns = collect($columns)
            ->filter(fn ($item) => ! in_array($item['name'], ['id', 'created_at', 'updated_at']))
            ->all();

        $attribute = '';
        $template  = "'%s' => '%s',";
        foreach ($columns as $column) {
            $col = sprintf($template, $column['name'], ! $column['nullable'] ? 'required' : 'nullable');
            $attribute .= $this->indent($col, 5) . PHP_EOL;
        }

        return rtrim($attribute);
    }

    public function getIndexFilter(): string
    {
        $columns = Schema::getColumnListing($this->tableName);
        $columns = collect($columns)
            ->filter(fn ($item) => ! in_array($item, ['id', 'created_at', 'updated_at']))
            ->all();

        $template = '
            <label
                class="block font-medium text-sm dark:text-gray-100"
                for="%s"
                >%s</label
              >
              <InputText v-model="filter.%s" />
        ';
        $attribute = '';
        foreach ($columns as $column) {
            $title = Str::title(
                str_replace('_', ' ', Str::snake(Str::singular($column)))
            );
            $col = sprintf($template, $column, $title, $column);
            $attribute .= $this->indent($col, 3);
        }

        return rtrim($attribute);
    }

    public function getViewForm(): string
    {
        $columns = Schema::getColumnListing($this->tableName);
        $columns = collect($columns)
            ->filter(fn ($item) => ! in_array($item, ['id']))
            ->all();

        $template = '
            <div>
              <InputLabel for="%s" value="%s" />

              <TextInput
                id="%s"
                v-model="camelDummy.%s"
                type="text"
                class="mt-1 block w-full"
                disabled
              />
            </div>
        ';
        $attribute = '';
        foreach ($columns as $column) {
            $col = sprintf($template, $column, Str::studly($column), $column, $column);
            $attribute .= $this->indent($col, 3);
        }

        return rtrim($attribute);
    }

    public function getEditForm(): string
    {
        $columns = Schema::getColumnListing($this->tableName);
        $columns = collect($columns)
            ->filter(fn ($item) => ! in_array($item, ['id', 'created_at', 'updated_at']))
            ->all();

        $template = '
            <div>
              <InputLabel for="%s" value="%s" />

              <TextInput
                id="%s"
                v-model="form.%s"
                type="text"
                class="mt-1 block w-full"
                autocomplete="%s"
              />

              <InputError :message="form.errors.%s" class="mt-2" />
            </div>
        ';
        $attribute = '';
        foreach ($columns as $column) {
            $col = sprintf($template, $column, Str::studly($column), $column, $column, $column, $column);
            $attribute .= $this->indent($col, 3);
        }

        return rtrim($attribute);
    }

    public function getUseFormEdit(): string
    {
        $columns = Schema::getColumnListing($this->tableName);
        $columns = collect($columns)
            ->filter(fn ($item) => ! in_array($item, ['id', 'created_at', 'updated_at']))
            ->all();

        $template = '
            %s: props.camelDummy.%s,
        ';
        $attribute = '';
        foreach ($columns as $column) {
            $col = sprintf($template, $column, $column);
            $attribute .= $this->indent($col);
        }

        return rtrim($attribute);
    }

    public function getUseFormCreate(): string
    {
        $columns = Schema::getColumnListing($this->tableName);
        $columns = collect($columns)
            ->filter(fn ($item) => ! in_array($item, ['id', 'created_at', 'updated_at']))
            ->all();

        $template = '
            %s: "",
        ';
        $attribute = '';
        foreach ($columns as $column) {
            $col = sprintf($template, $column, $column);
            $attribute .= $this->indent($col);
        }

        return rtrim($attribute);
    }

    public function getCreateForm(): string
    {
        $columns = Schema::getColumnListing($this->tableName);
        $columns = collect($columns)
            ->filter(fn ($item) => ! in_array($item, ['id', 'created_at', 'updated_at']))
            ->all();

        $template = '
            <div>
              <InputLabel for="%s" value="%s" />

              <TextInput
                id="%s"
                v-model="form.%s"
                type="text"
                class="mt-1 block w-full"
                autocomplete="%s"
              />

              <InputError :message="form.errors.%s" class="mt-2" />
            </div>
        ';
        $attribute = '';
        foreach ($columns as $column) {
            $col = sprintf($template, $column, Str::studly($column), $column, $column, $column, $column);
            $attribute .= $this->indent($col, 3);
        }

        return rtrim($attribute);
    }
}
