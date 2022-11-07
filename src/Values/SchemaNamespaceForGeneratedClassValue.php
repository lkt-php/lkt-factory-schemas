<?php

namespace Lkt\Factory\Schemas\Values;

use Lkt\Factory\Schemas\Exceptions\InvalidSchemaNamespaceForGeneratedClassException;

final class SchemaNamespaceForGeneratedClassValue
{
    private $value;

    /**
     * @param string $value
     * @param string $name
     * @throws InvalidSchemaNamespaceForGeneratedClassException
     */
    public function __construct(string $value, string $name = '')
    {
        if (!$value) {
            $value = $name;
        }

        if (!$value) {
            throw new InvalidSchemaNamespaceForGeneratedClassException();
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