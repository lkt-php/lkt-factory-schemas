<?php

namespace Lkt\Factory\Schemas\Fields;

use Lkt\Factory\Schemas\Traits\FieldWithComponentOptionTrait;
use Lkt\Factory\Schemas\Traits\FieldWithSoftTypedOptionTrait;
use Lkt\Factory\Schemas\Traits\FieldWithWhereOptionTrait;

class ForeignKeyField extends IntegerField
{
    use FieldWithComponentOptionTrait,
        FieldWithWhereOptionTrait,
        FieldWithSoftTypedOptionTrait;

    public static function defineRelation(string $component, string $name, string $column = ''): static
    {
        return (new static($name, $column))->setComponent($component);
    }
}