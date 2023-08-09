<?php

namespace Lkt\Factory\Schemas\Fields;

use Lkt\Factory\Schemas\Traits\FieldWithNullOptionTrait;

class ConcatField extends AbstractField
{
    use FieldWithNullOptionTrait;

    protected array $fields = [];
    protected string $separator = '';


    final public static function concat(string $name, array $fields, string $separator): static
    {
        $r = new static($name, '');
        $r->fields = $fields;
        $r->separator = $separator;
        return $r;
    }

    public function getConcatenatedFields(): array
    {
        return $this->fields;
    }

    public function getConcatenatedFieldsAsString(): string
    {
        $r = [];
        foreach ($this->fields as $field) {
            $r[] = "'{$field}'";
        }
        $r = implode(',', $r);
        return "[{$r}]";
    }

    public function getSeparator(): string
    {
        return $this->separator;
    }
}