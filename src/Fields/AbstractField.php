<?php

namespace Lkt\Factory\Schemas\Fields;

use Lkt\Factory\Schemas\Exceptions\InvalidFieldNameException;
use Lkt\Factory\Schemas\Values\BooleanValue;
use Lkt\Factory\Schemas\Values\FieldColumnValue;
use Lkt\Factory\Schemas\Values\FieldCustomTypeValue;
use Lkt\Factory\Schemas\Values\FieldLabelValue;
use Lkt\Factory\Schemas\Values\FieldNameValue;

abstract class AbstractField
{
    const TYPE = '';

    protected FieldNameValue $name;
    protected FieldColumnValue $column;
    protected FieldLabelValue $label;
    protected FieldCustomTypeValue $customType;

    protected BooleanValue $showInCreateView;
    protected BooleanValue $showInUpdateView;


    /**
     * @throws InvalidFieldNameException
     */
    public function __construct(string $name, string $column = '')
    {
        $this->name = new FieldNameValue($name);
        $this->column = new FieldColumnValue($column, $this->name->getValue());
        $this->label = new FieldLabelValue('');
        $this->customType = new FieldCustomTypeValue(self::TYPE);
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

    public function setCustomType(string $type): static
    {
        $this->customType = new FieldCustomTypeValue($type);
        return $this;
    }

    public function getCustomType(): string
    {
        return $this->customType->getValue();
    }

    public function setIsVisibleInCreateView(bool $enabled = true): static
    {
        $this->showInCreateView = new BooleanValue($enabled);
        return $this;
    }

    public function isVisibleInCreateView(): bool
    {
        return $this->showInCreateView->getValue();
    }

    public function setIsVisibleInUpdateView(bool $enabled = true): static
    {
        $this->showInUpdateView = new BooleanValue($enabled);
        return $this;
    }

    public function isVisibleInUpdateView(): bool
    {
        return $this->showInUpdateView->getValue();
    }
}