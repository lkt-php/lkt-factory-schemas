<?php

namespace Lkt\Factory\Schemas\Values;

use Lkt\Factory\Schemas\Exceptions\InvalidFieldFilePathException;

final class FieldFilePathValue
{
    private $value;

    /**
     * @param string $value
     * @throws InvalidFieldFilePathException
     */
    public function __construct(string $value)
    {
        if (!$value) {
            $value = '';
//            throw new InvalidFieldFilePathException();
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