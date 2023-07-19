<?php

namespace Sedlatschek\LaravelTypescriptWriter;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RuntimeException;

class TypescriptData
{
    use WritesTypescript;

    public static function __set_state(array $state)
    {
        return new TypescriptData(
            $state['class'],
            $state['name'],
            $state['data'],
            $state['valueReplacements'] ?? [],
        );
    }

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
