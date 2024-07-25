<?php

namespace Jhonoryza\Vien\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Jhonoryza\Vien\Concern\FileGenerator;
use Jhonoryza\Vien\Console\Concern\CommonConsoleTrait;
use Jhonoryza\Vien\Generator\ControllerGenerator;
use Jhonoryza\Vien\Generator\RouteGenerator;

class GeneratorCommand extends Command
{
    use CommonConsoleTrait;

    protected $signature = 'vien:generate {table}';

    protected $description = 'Generate Vien route, controller and resource';

    public function __construct(public Filesystem $filesystem, public Composer $composer)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $tableName = $this->argument('table');

        $this->generateFile(new RouteGenerator($this->filesystem, $tableName));
        $this->generateFile(new ControllerGenerator($this->filesystem, $tableName));

        $this->runCommands(['./vendor/bin/pint .']);

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
