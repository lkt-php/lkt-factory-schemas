<?php

namespace Lkt\Factory\Schemas\ComputedFields;

use Lkt\Factory\Schemas\Fields\AbstractField;

class StringEqualComputedField extends AbstractComputedField
{
    final public static function compute(string $name, string $field, string $value = ''): self
    {
        $r = new static($name);
        $r->setField($field)->setValue($value);
        return $r;
    }
}