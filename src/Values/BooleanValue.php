<?php

namespace Lkt\Factory\Schemas\Values;

final class BooleanValue
{
    private $value = false;

    /**
     * @param bool $value
     */
    public function __construct(bool $value)
    {
        if (!$value) {
            $value = false;
        }
        $this->value = $value === true;
    }

    /**
     * @return bool
     */
    public function getValue(): bool
    {
        return $this->value;
    }
}