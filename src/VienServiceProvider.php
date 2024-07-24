<?php

namespace Jhonoryza\Vien;

use Illuminate\Support\ServiceProvider;
use Jhonoryza\Vien\Console\InstallCommand;

class VienServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            InstallCommand::class,
        ]);
    }

    public function provides()
    {
        return [
            InstallCommand::class,
        ];
    }
}
