<?php

namespace Sedlatschek\LaravelTypescriptWriter;

class TypescriptFile
{
    /**
     * @param  TypescriptData[]  $data
     */
    public function __construct(
        public string $path,
        public array $data,
    ) {
    }

    /**
     * Get the file header.
     */
    public static function getHeader(): string
    {
        return
"/**
* This file is auto generated using 'php artisan typescript-writer'
*
* Changes to this file will be lost when the command is run again
*
* See https://github.com/sedlatschek/laravel-typescript-writer
*/".str_repeat(config('typescript-writer.eol_char', PHP_EOL), 2);
    }

    /**
     * Convert file to typescript.
     */
    public function toTypescript(): string
    {
        return static::getHeader()
            .collect($this->data)->map(fn ($d) => $d->toTypescript())->join(config('typescript-writer.eol_char', PHP_EOL));
    }
}
