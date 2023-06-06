<?php

namespace Sedlatschek\LaravelTypescriptWriter;

class TypescriptFile
{
    public static function __set_state(array $state)
    {
        return new TypescriptFile(
            $state['path'],
            $state['data'],
            $state['typescript'] ?? '',
        );
    }

    /**
     * @param  TypescriptData[]  $data
     * @param  string  $typescript Typescript code that is placed above the generated data.
     */
    public function __construct(
        public string $path,
        public array $data,
        public string $typescript = '',
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
*/".str_repeat(self::eol(), 2);
    }

    /**
     * The end of line character.
     */
    private static function eol(): string
    {
        return config('typescript-writer.eol_char', PHP_EOL);
    }

    /**
     * Convert file to typescript.
     */
    public function toTypescript(): string
    {
        return static::getHeader()
            .$this->typescript
            .collect($this->data)->map(fn ($d) => $d->toTypescript())->join(self::eol());
    }
}
