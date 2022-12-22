<?php

namespace Lkt\Factory\Schemas\Values;

use Lkt\QueryBuilding\Where;

final class FieldWhereValue
{
    private array $value = [];

    public function __construct($value)
    {
        if (!$value) {
            $value = [];
        }

        if ($value instanceof Where) {
            $value = [$value->whereConstraintsToString()];
        }
        elseif (!is_array($value)) {
            $value = [$value];
        }
        $this->value = $value;
    }

    public function getValue(): array
    {
        return $this->value;
    }
}