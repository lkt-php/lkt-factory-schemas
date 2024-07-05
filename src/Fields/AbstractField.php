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
    protected BooleanValue $editInCreateView;
    protected BooleanValue $hideInCreateView;
    protected BooleanValue $dataInCreateView;
    protected BooleanValue $showInUpdateView;
    protected BooleanValue $editInUpdateView;
    protected BooleanValue $hideInUpdateView;
    protected BooleanValue $dataInUpdateView;


    /**
     * @throws InvalidFieldNameException
     */
    public function __construct(string $name, string $column = '')
    {
        $this->name = new FieldNameValue($name);
        $this->column = new FieldColumnValue($column, $this->name->getValue());
        $this->label = new FieldLabelValue('');
        $this->customType = new FieldCustomTypeValue(self::TYPE);

        $this->showInCreateView = new BooleanValue(false);
        $this->showInUpdateView = new BooleanValue(false);
        $this->hideInCreateView = new BooleanValue(false);
        $this->hideInUpdateView = new BooleanValue(false);
        $this->editInCreateView = new BooleanValue(false);
        $this->editInUpdateView = new BooleanValue(false);
        $this->dataInCreateView = new BooleanValue(false);
        $this->dataInUpdateView = new BooleanValue(false);
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

    public function setIsEditableInCreateView(bool $enabled = true): static
    {
        $this->editInCreateView = new BooleanValue($enabled);
        return $this;
    }

    public function isEditableInCreateView(): bool
    {
        return $this->editInCreateView->getValue();
    }

    public function setIsHiddenInCreateView(bool $enabled = true): static
    {
        $this->hideInCreateView = new BooleanValue($enabled);
        return $this;
    }

    public function isHiddenInCreateView(): bool
    {
        return $this->hideInCreateView->getValue();
    }

    public function setIsDataInCreateView(bool $enabled = true): static
    {
        $this->hideInCreateView = new BooleanValue($enabled);
        return $this;
    }

    public function isDataInCreateView(): bool
    {
        return $this->dataInCreateView->getValue();
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

    public function setIsEditableInUpdateView(bool $enabled = true): static
    {
        $this->editInUpdateView = new BooleanValue($enabled);
        return $this;
    }

    public function isEditableInUpdateView(): bool
    {
        return $this->editInUpdateView->getValue();
    }

    public function setIsHiddenInUpdateView(bool $enabled = true): static
    {
        $this->hideInUpdateView = new BooleanValue($enabled);
        return $this;
    }

    public function isHiddenInUpdateView(): bool
    {
        return $this->hideInUpdateView->getValue();
    }

    public function setIsDataInUpdateView(bool $enabled = true): static
    {
        $this->dataInUpdateView = new BooleanValue($enabled);
        return $this;
    }

    public function isDataInUpdateView(): bool
    {
        return $this->dataInUpdateView->getValue();
    }

    public function getModeInCreateView(): string
    {
        if ($this->isEditableInCreateView()) return 'edit';
        if ($this->isVisibleInCreateView()) return 'read';
        if ($this->isHiddenInCreateView()) return 'hide';
        if ($this->isDataInCreateView()) return 'data';
        return 'data';
    }

    public function getModeInUpdateView(): string
    {
        if ($this->isEditableInUpdateView()) return 'edit';
        if ($this->isVisibleInUpdateView()) return 'read';
        if ($this->isHiddenInUpdateView()) return 'hide';
        if ($this->isDataInUpdateView()) return 'data';
        return 'data';
    }
}