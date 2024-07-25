<?php

namespace Jhonoryza\Vien\Concern;

interface FileGenerator
{
    /**
     * Get the generated file's content.
     */
    public function getContent(): string;

    /**
     * Get the generate file's name.
     */
    public function getFilename(): string;

    /**
     * Get the full path of generated file.
     */
    public function getFullPath(): string;

    /**
     * Get the path of directory which the generated file
     * will be written to.
     */
    public function getPath(): string;
}
