<?php

namespace Jhonoryza\Vien\Generator;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Jhonoryza\Vien\Concern\FileGenerator;
use Jhonoryza\Vien\Concern\ReplaceResourceKeywords;

class RouteGenerator implements FileGenerator
{
    use ReplaceResourceKeywords;
    use CommonGeneratorTrait;

    protected string $filename;
    protected string $path;

    public function __construct(public Filesystem $filesystem, public string $tableName)
    {
        $this->path = base_path(dirname(config('laravel-vien.generator.route_path')));
        $this->filename = $this->generateFilename();
    }

    private function generateFilename(): string
    {
        return basename(config('laravel-vien.generator.route_path'));
    }

    public function getContent(): string
    {
        return $this->manipulateContent(
            $this->filesystem->get($this->getFullPath())
        );
    }

    private function manipulateContent(string $content): string
    {
        $import = "use App\\Http\\Controllers\\{$this->getStudlyDummies()}Controller;";
        if (!Str::contains($content, $import)) {
            $search = "<?php";
            $replace = PHP_EOL . PHP_EOL . $import;
            $content = str_replace($search, $search . $replace, $content);
        }

        $resource = "Route::resource('{$this->getSnakeDummies()}', {$this->getStudlyDummies()}Controller::class);";
        $bulkDelete = "Route::delete('{$this->getSnakeDummies()}/bulk-delete',[{$this->getStudlyDummies()}Controller::class,'bulkDestroy'])->name('{$this->getSnakeDummies()}.bulk-delete');";
        if (!Str::contains($content, $resource)) {
            $content .= PHP_EOL . $resource;
        }

        if (!Str::contains($content, $bulkDelete)) {
            $content .= PHP_EOL . $bulkDelete;
        }

        return $content;
    }
}