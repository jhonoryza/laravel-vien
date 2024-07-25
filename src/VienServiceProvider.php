<?php

namespace Jhonoryza\Vien;

use Illuminate\Support\ServiceProvider;
use Jhonoryza\Vien\Console\GeneratorCommand;
use Jhonoryza\Vien\Console\InstallCommand;

class VienServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-vien.php', 'laravel-vien');
    }

    public function boot(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/laravel-vien.php' => config_path('laravel-vien.php'),
        ], 'vien-config');

        $this->publishes([
            __DIR__ . '/Generators/Stubs' => base_path('stubs/vien_generator'),
        ], 'vien-stubs');

        $this->commands([
            InstallCommand::class,
            GeneratorCommand::class,
        ]);
    }

    public function provides(): array
    {
        return [
            InstallCommand::class,
            GeneratorCommand::class,
        ];
    }
}
