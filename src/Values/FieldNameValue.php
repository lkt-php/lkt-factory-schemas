<?php

namespace Lkt\Factory\Schemas\Values;

use Lkt\Factory\Schemas\Exceptions\InvalidFieldNameException;

final class FieldNameValue
{
    private $value;

    /**
     * @param string $value
     * @throws InvalidFieldNameException
     */
    public function __construct(string $value)
    {
        if (!$value) {
            throw new InvalidFieldNameException();
        }
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}