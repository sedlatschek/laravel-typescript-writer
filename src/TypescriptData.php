<?php

namespace Sedlatschek\LaravelTypescriptWriter;

use Illuminate\Support\Collection;
use RuntimeException;

class TypescriptData
{
    public function __construct(
        public string $class,
        public string $name,
        public $data,
    ) {
    }

    /**
     * Convert data to typescript.
     */
    public function toTypescript(): string
    {
        return "export const $this->name: $this->class = ".$this->write($this->data, 0, false).';';
    }

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
    private function eol(): string
    {
        return config('typescript-writer.eol_char', PHP_EOL);
    }

    /**
     * Wrap a given string in quotes.
     */
    private function wrap(string $value, string $char = '"'): string
    {
        return "$char{$value}$char";
    }

    /**
     * Write the given object key.
     */
    private function writeKey(string $key)
    {
        if (str_contains($key, '-')) {
            return $this->wrap($key, "'");
        }

        return $key;
    }

    /**
     * Write the given data.
     *
     * @throws \RuntimeException
     */
    private function write($data, $level = 0, $indent = true): string|int|float
    {
        if (is_string($data)) {
            return $this->wrap($data);
        }

        if (is_int($data) || is_float($data)) {
            return $data;
        }

        if (is_bool($data)) {
            return $data ? 'true' : 'false';
        }

        if (is_null($data)) {
            return 'null';
        }

        if (is_a($data, Collection::class)) {
            return $this->write($data->toArray(), $level, $indent);
        }

        if (is_object($data)) {
            return $this->write((array) $data, $level, $indent);
        }

        if (is_array($data)) {
            // list array
            if (array_is_list($data)) {
                return $this->indent($level, $indent).'['.$this->eol()
                    .collect($data)
                        ->map(fn ($d) => $this->indent($level + 1).$this->write($d, $level + 1, false))
                        ->join(','.$this->eol(), ','.$this->eol()).
                    $this->eol().$this->indent($level).']';
            }

            // assoc array
            return $this->indent($level, $indent).'{'.$this->eol()
                .collect($data)
                    ->map(fn ($value, string $key) => $this->indent($level + 1).$this->writeKey($key).': '.$this->write($value, $level + 1, false))
                    ->join(','.$this->eol(), ','.$this->eol()).
                $this->eol().$this->indent($level).'}';
        }

        throw new RuntimeException('Type of data is not implemented');
    }
}
