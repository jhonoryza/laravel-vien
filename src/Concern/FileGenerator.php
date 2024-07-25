<?php

namespace Jhonoryza\Vien\Concern;

interface FileGenerator
{
    /**
     * Get the generated file's content.
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Get the generate file's name.
     *
     * @return string
     */
    public function getFilename(): string;

    /**
     * Get the full path of generated file.
     *
     * @return string
     */
    public function getFullPath(): string;

    /**
     * Get the path of directory which the generated file
     * will be written to.
     *
     * @return string
     */
    public function getPath(): string;
}
