<?php

namespace Sedlatschek\LaravelTypescriptWriter;

trait WritesTypescript
{
    /**
     * Indent the given string.
     */
    private function indent(int $level, $use = true): string
    {
        if (! $use) {
            return '';
        }

        return str_repeat(' ', config('typescript-writer.indentation', 2) * $level);
    }

    /**
     * The end of line character.
     */
    private static function eol(): string
    {
        return config('typescript-writer.eol_char', PHP_EOL);
    }

    /**
     * Get the quote character
     */
    private function quote(): string
    {
        if (config('typescript-writer.single_quote', true)) {
            return "'";
        }

        return '"';
    }

    /**
     * Wrap a given string in quotes.
     */
    private function wrap(string $value, string $char = null): string
    {
        $char = isset($char)
            ? $char
            : $this->quote();

        return "$char{$value}$char";
    }

    /**
     * Write the given object key.
     */
    private function writeKey(string $key): string
    {
        if (str_contains($key, '-') || str_contains($key, ' ')) {
            return $this->wrap($key, "'");
        }

        return $key;
    }
}
