<?php

namespace Jhonoryza\Vien\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Jhonoryza\Vien\Console\Concern\CommonConsoleTrait;

class UpdateCommand extends Command
{
    use CommonConsoleTrait;

    protected $signature = 'vien:update';

    protected $description = 'Update the Vien components';

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

        // Components + Pages...
        $exclude = ['menu-items.js'];
        $this->copyDirectoryExcludingFiles(__DIR__ . '/../../stubs/inertia-vue/Vien', resource_path('js/Components/Vien'), $exclude);

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
        $this->components->info('Vien components updated successfully.');
    }

    public function copyDirectoryExcludingFiles($source, $destination, $exclude = []): void
    {
        $filesystem = new Filesystem;

        // Ensure destination directory exists
        $filesystem->ensureDirectoryExists($destination);

        // Get all files in the source directory
        $files = $filesystem->allFiles($source);

        // Filter out the files to be excluded
        $filesToCopy = array_filter($files, function ($file) use ($exclude) {
            foreach ($exclude as $excludedFile) {
                if (str_contains($file->getPathname(), $excludedFile)) {
                    return false;
                }
            }

            return true;
        });

        // Copy each remaining file to the destination
        foreach ($filesToCopy as $file) {
            $relativePath = str_replace($source, '', $file->getPathname());
            $filesystem->copy($file->getPathname(), $destination . $relativePath);
        }

        // Get all directories in the source directory
        $directories = $filesystem->directories($source);

        // Create directories in the destination
        foreach ($directories as $directory) {
            $relativePath   = str_replace($source, '', $directory);
            $newDestination = $destination . $relativePath;
            $filesystem->ensureDirectoryExists($newDestination);
            $this->copyDirectoryExcludingFiles($directory, $newDestination, $exclude);
        }
    }
}
