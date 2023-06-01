<?php

namespace Sedlatschek\LaravelTypescriptWriter;

class LaravelTypescriptWriter
{
    public static function write(TypescriptFile $file): void
    {
        file_put_contents($file->path, $file->toTypescript());
    }
}
