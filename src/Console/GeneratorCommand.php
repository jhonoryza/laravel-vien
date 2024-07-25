<?php

namespace Jhonoryza\Vien\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Jhonoryza\Vien\Concern\FileGenerator;
use Jhonoryza\Vien\Console\Concern\CommonConsoleTrait;
use Jhonoryza\Vien\Generator\ControllerGenerator;
use Jhonoryza\Vien\Generator\CreateGenerator;
use Jhonoryza\Vien\Generator\EditGenerator;
use Jhonoryza\Vien\Generator\IndexGenerator;
use Jhonoryza\Vien\Generator\RouteGenerator;
use Jhonoryza\Vien\Generator\ShowGenerator;

use function Laravel\Prompts\text;

class GeneratorCommand extends Command
{
    use CommonConsoleTrait;

    protected $signature = 'vien:generate {table? : The table name}';

    protected $description = 'Generate Vien route, controller and resource';

    public function __construct(public Filesystem $filesystem, public Composer $composer)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $tableName = $this->argument('table') ?? text(
            label: 'Enter table name',
            placeholder: 'users',
            required: true
        );

        $this->generateFile(new RouteGenerator($this->filesystem, $tableName));
        $this->generateFile(new ControllerGenerator($this->filesystem, $tableName));
        $this->generateFile(new IndexGenerator($this->filesystem, $tableName));
        $this->generateFile(new ShowGenerator($this->filesystem, $tableName));
        $this->generateFile(new EditGenerator($this->filesystem, $tableName));
        $this->generateFile(new CreateGenerator($this->filesystem, $tableName));

        $this->runCommands(['./vendor/bin/pint .']);
        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $this->runCommands(['pnpm run build']);
        } elseif (file_exists(base_path('yarn.lock'))) {
            $this->runCommands(['yarn run build']);
        } elseif (file_exists(base_path('bun.lockb'))) {
            $this->runCommands(['bun run build']);
        } else {
            $this->runCommands(['npm run build']);
        }

        $this->composer->dumpAutoloads();
    }

    private function generateFile(FileGenerator $generator): void
    {
        if (! $this->filesystem->exists($generator->getPath())) {
            $this->filesystem->makeDirectory($generator->getPath(), 0755, true);
        }

        $this->filesystem->put($generator->getFullPath(), $generator->getContent());

        $this->info('Generated: ' . $generator->getPath() . DIRECTORY_SEPARATOR . $generator->getFilename());
    }
}
