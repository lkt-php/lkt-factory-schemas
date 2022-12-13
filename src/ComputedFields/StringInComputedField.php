<?php

namespace Lkt\Factory\Schemas\ComputedFields;

use Lkt\Factory\Schemas\Fields\AbstractField;

class StringInComputedField extends AbstractComputedField
{
    final public static function compute(string $name, string $field, array $value = []): self
    {
        $r = new static($name);
        $r->setField($field)->setValue($value);
        return $r;
    }
}