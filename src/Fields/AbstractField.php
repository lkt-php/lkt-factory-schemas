<?php

namespace Lkt\Factory\Schemas\Fields;

use Lkt\Factory\Schemas\Exceptions\InvalidFieldNameException;
use Lkt\Factory\Schemas\Values\FieldColumnValue;
use Lkt\Factory\Schemas\Values\FieldNameValue;

abstract class AbstractField
{
    protected $name;
    protected $column;

    /**
     * @param string $name
     * @param string $column
     * @throws InvalidFieldNameException
     */
    public function __construct(string $name, string $column = '')
    {
        $this->name = new FieldNameValue($name);
        $this->column = new FieldColumnValue($column, $this->name->getValue());
    }

    final public function getName(): string
    {
        return $this->name->getValue();
    }

    final public function getColumn(): string
    {
        return $this->column->getValue();
    }

    /**
     * @param string $name
     * @param string $column
     * @return static
     * @throws InvalidFieldNameException
     */
    final public static function define(string $name, string $column = ''): self
    {
        return new static($name, $column);
    }
}