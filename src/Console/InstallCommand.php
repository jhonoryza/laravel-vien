<?php

namespace Jhonoryza\Vien\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Jhonoryza\Vien\Console\Concern\CommonConsoleTrait;

class InstallCommand extends Command
{
    use CommonConsoleTrait;

    protected $signature = 'vien:install';

    protected $description = 'Install the Vien controllers and resources';

    public function handle(): void
    {
        $this->installInertiaVueStack();
    }

    protected function installInertiaVueStack(): void
    {
        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@tabler/icons-vue'       => '^3.7.0',
                'vue3-click-away'         => '^1.2.4',
                '@headlessui/vue'         => '^1.7.22',
                'flatpickr'               => '^4.6.13',
                'vue-flatpickr-component' => '^11.0.5',
            ] + $packages;
        });

        // Models...
        (new Filesystem)->ensureDirectoryExists(app_path('Models'));
        copy(__DIR__ . '/../../stubs/inertia-vue/Models/Setting.php', app_path('Models/Setting.php'));

        // Controllers...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia-vue/Controllers', app_path('Http/Controllers'));

        (new Filesystem)->ensureDirectoryExists(app_path('Http/Middleware'));
        copy(__DIR__ . '/../../stubs/inertia-vue/Middleware/HandleInertiaRequests.php', app_path('Http/Middleware/HandleInertiaRequests.php'));

        // Database...
        copy(__DIR__ . '/../../stubs/inertia-vue/database/migrations/0001_01_01_000003_create_settings_tables.php', database_path('migrations/0001_01_01_000003_create_settings_tables.php'));
        copy(__DIR__ . '/../../stubs/inertia-vue/database/seeders/DatabaseSeeder.php', database_path('seeders/DatabaseSeeder.php'));
        copy(__DIR__ . '/../../stubs/inertia-vue/database/factories/SettingFactory.php', database_path('factories/SettingFactory.php'));

        // Components + Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components/Vien'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));

        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia-vue/Vien', resource_path('js/Components/Vien'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia-vue/Pages', resource_path('js/Pages'));
        copy(__DIR__ . '/../../stubs/inertia-vue/Default/AuthenticatedLayout.vue', resource_path('js/Layouts/AuthenticatedLayout.vue'));

        // Routes...
        copy(__DIR__ . '/../../stubs/inertia-vue/routes/web.php', base_path('routes/web.php'));

        // Tailwind / Vite...
        copy(__DIR__ . '/../../stubs/inertia-vue/Default/app.css', resource_path('css/app.css'));
        copy(__DIR__ . '/../../stubs/inertia-vue/Default/app.js', resource_path('js/app.js'));
        copy(__DIR__ . '/../../stubs/inertia-vue/Default/tailwind.config.js', base_path('tailwind.config.js'));

        $this->components->info('Installing and building Node dependencies.');

        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $this->runCommands(['pnpm install', 'pnpm run build']);
        } elseif (file_exists(base_path('yarn.lock'))) {
            $this->runCommands(['yarn install', 'yarn run build']);
        } elseif (file_exists(base_path('bun.lockb'))) {
            $this->runCommands(['bun install', 'bun run build']);
        } else {
            $this->runCommands(['npm install', 'npm run build']);
        }

        $this->line('');
        $this->components->info('Vien scaffolding installed successfully.');

        $this->components->info('Running migration.');
        $this->runCommands(['composer dump-autoload', 'php artisan migrate --force']);
    }
}
