<?php

namespace Lkt\Factory\Schemas\Values;

use Lkt\Factory\Schemas\Exceptions\InvalidSchemaClassNameForGeneratedClassException;

final class SchemaClassForGeneratedClassValue
{
    private $value;

    /**
     * @param string $value
     * @param string $name
     * @throws InvalidSchemaClassNameForGeneratedClassException
     */
    public function __construct(string $value, string $name = '')
    {
        if (!$value) {
            $value = $name;
        }

        if (!$value) {
            throw new InvalidSchemaClassNameForGeneratedClassException();
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