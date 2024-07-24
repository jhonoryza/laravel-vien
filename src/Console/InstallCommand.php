<?php

namespace Jhonoryza\Vien\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vien:install';

    protected $description = 'Install the Vien controllers and resources';

    public function handle(): void
    {
        $this->installInertiaVueStack();
    }

    /**
     * Install the Inertia Vue Breeze stack.
     *
     * @return int|null
     */
    protected function installInertiaVueStack()
    {
        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@tabler/icons-vue' => '^3.7.0',
                'vue3-click-away'   => '^1.2.4',
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
        copy(__DIR__ . '/../../stubs/inertia-vue/Default/Authenticated.vue', base_path('js/Layouts/Authenticated.vue'));

        // Routes...
        copy(__DIR__ . '/../../stubs/inertia-vue/routes/web.php', base_path('routes/web.php'));

        // Tailwind / Vite...
        copy(__DIR__ . '/../../stubs/inertia-vue/Default/app.css', resource_path('css/app.css'));
        copy(__DIR__ . '/../../stubs/inertia-vue/Default/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__ . '/../../stubs/inertia-vue/Default/app.js', resource_path('js/app.js'));

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

    /**
     * Update the "package.json" file.
     *
     * @param  bool  $dev
     * @return void
     */
    protected static function updateNodePackages(callable $callback, $dev = true)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }

    /**
     * Run the given commands.
     *
     * @param  array  $commands
     * @return void
     */
    protected function runCommands($commands)
    {
        $process = Process::fromShellCommandline(implode(' && ', $commands), null, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $this->output->writeln('  <bg=yellow;fg=black> WARN </> ' . $e->getMessage() . PHP_EOL);
            }
        }

        $process->run(function ($type, $line) {
            $this->output->write('    ' . $line);
        });
    }
}
