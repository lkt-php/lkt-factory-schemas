<?php

namespace Lkt\Factory\Schemas\Values;

final class FieldOrderValue
{
    private $value = [];

    /**
     * @param $value
     */
    public function __construct($value)
    {
        if (!$value) {
            $value = [];
        }
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->value;
    }
}