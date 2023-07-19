<?php

namespace Sedlatschek\LaravelTypescriptWriter;

class TypescriptEnum
{
    use WritesTypescript;

    public static function __set_state(array $state)
    {
        return new TypescriptEnum(
            $state['name'],
            $state['data'],
        );
    }

    /**
     * @param  \Illuminate\Support\Collection<string, int|string>  $data
     */
    public function __construct(
        public string $name,
        public $data,
    ) {
    }

    /**
     * Convert data to typescript.
     */
    public function toTypescript(): string
    {
        return "export const enum $this->name {".$this->eol()
            .collect($this->data)->map(function ($v, $k) {
                $value = is_string($v)
                    ? $this->wrap($v)
                    : $v;

                return $this->indent(1).$this->writeKey($k)." = $value";
            })->join(','.self::eol())
            .','.self::eol().'}';
    }

    /**
     * Get an array that can be used as replacement values for TypescriptData.
     */
    public function asReplacementValues(): array
    {
        return collect($this->data)
            ->mapWithKeys(function ($v, $k) {
                return [$v => $this->name.'.'.$k];
            })->toArray();
    }
}
