<?php

namespace Sedlatschek\LaravelTypescriptWriter;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RuntimeException;

class TypescriptData
{
    public function __construct(
        public string $class,
        public string $name,
        public $data,
        public array $valueReplacements = [],
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
    private function wrap(string $value, string|null $char = null): string
    {
        $char = isset($char)
            ? $char
            : $this->quote();

        return "$char{$value}$char";
    }

    /**
     * Replace a given value with preconfigured replacements.
     */
    private function replaceValue(string|null $key, $value)
    {
        foreach ($this->valueReplacements as $repKey => $repValues) {
            if (Str::is($repKey, $key)) {
                if (is_array($repValues) && array_key_exists($value, $repValues)) {
                    return $repValues[$value];
                }
            }
        }

        return $value;
    }

    /**
     * Write the given object key.
     */
    private function writeKey(string $key): string
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
    private function write($data, string|null $propKey = null, $level = 0, $indent = true): string|int|float
    {
        if (is_string($data)) {
            return $this->wrap($this->replaceValue($propKey, $data));
        }

        if (is_int($data) || is_float($data)) {
            return $this->replaceValue($propKey, $data);
        }

        if (is_bool($data)) {
            return $this->replaceValue($propKey, $data ? 'true' : 'false');
        }

        if (is_null($data)) {
            return $this->replaceValue($propKey, 'null');
        }

        if (is_a($data, Collection::class)) {
            return $this->write($data->toArray(), $propKey, $level, $indent);
        }

        if (is_object($data)) {
            return $this->write((array) $data, $propKey, $level, $indent);
        }

        if (is_array($data)) {
            // list array
            if (array_is_list($data)) {
                return $this->indent($level, $indent).'['.self::eol()
                    .collect($data)
                        ->map(fn ($d) => $this->indent($level + 1).$this->write($d, $propKey, $level + 1, false))
                        ->join(','.self::eol(), ','.self::eol()).
                    self::eol().$this->indent($level).']';
            }

            // assoc array
            return $this->indent($level, $indent).'{'.self::eol()
                .collect($data)
                    ->map(fn ($value, string $key) => $this->indent($level + 1).$this->writeKey($key).': '.$this->write($value, isset($propKey) ? "$propKey.$key" : $key, $level + 1, false))
                    ->join(','.self::eol(), ','.self::eol()).
                self::eol().$this->indent($level).'}';
        }

        throw new RuntimeException('Type of data is not implemented');
    }
}
