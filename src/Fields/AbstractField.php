<?php

namespace Lkt\Factory\Schemas\Fields;

use Lkt\Factory\Schemas\Exceptions\InvalidFieldNameException;
use Lkt\Factory\Schemas\Values\FieldColumnValue;
use Lkt\Factory\Schemas\Values\FieldLabelValue;
use Lkt\Factory\Schemas\Values\FieldNameValue;

abstract class AbstractField
{
    protected FieldNameValue $name;
    protected FieldColumnValue $column;
    protected FieldLabelValue $label;


    /**
     * @throws InvalidFieldNameException
     */
    public function __construct(string $name, string $column = '')
    {
        $this->name = new FieldNameValue($name);
        $this->column = new FieldColumnValue($column, $this->name->getValue());
        $this->label = new FieldLabelValue('');
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
     * @throws InvalidFieldNameException
     */
    final public static function define(string $name, string $column = ''): static
    {
        return new static($name, $column);
    }

    public function getValidations()
    {
        // @todo
        return [];
    }

    public function getParser()
    {
        // @todo
        return null;
    }

    public function getSetter(): string
    {
        return 'set'. ucfirst($this->getName());
    }

    public function getGetterForComputed(): string
    {
        if ($this instanceof BooleanField) {
            return $this->getName();
        }
        return 'get'. ucfirst($this->getName());
    }

    public function getGetterForPrimitiveValue(): string
    {
        if ($this instanceof BooleanField) return $this->getName();
        if ($this instanceof ForeignKeyField) return 'get'. ucfirst($this->getName()) . 'Id';
        return 'get'. ucfirst($this->getName());
    }

    public function setLabel(string $label): static
    {
        $this->label = new FieldLabelValue($label);
        return $this;
    }

    public function getLabel(): string
    {
        return $this->label->getValue();
    }
}