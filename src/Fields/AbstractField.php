<?php

namespace Lkt\Factory\Schemas\Fields;

use Lkt\Factory\Schemas\Exceptions\InvalidFieldNameException;
use Lkt\Factory\Schemas\Values\BooleanValue;
use Lkt\Factory\Schemas\Values\FieldColumnValue;
use Lkt\Factory\Schemas\Values\FieldCustomTypeValue;
use Lkt\Factory\Schemas\Values\FieldLabelValue;
use Lkt\Factory\Schemas\Values\FieldNameValue;
use Lkt\Factory\Schemas\Views\FieldViewConfig;

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

    protected $configuredViews = [];

    protected $defaultValue = [];


    /**
     * @throws InvalidFieldNameException
     */
    public function __construct(string $name, string $column = '')
    {
        $this->name = new FieldNameValue($name);
        $this->column = new FieldColumnValue($column, $this->name->getValue());
        $this->label = new FieldLabelValue('');
        $this->customType = new FieldCustomTypeValue(static::TYPE);

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
        if ($this instanceof ForeignKeysField) return 'get'. ucfirst($this->getName()) . 'Ids';
        return 'get'. ucfirst($this->getName());
    }

    public function getGetterForData(): string
    {
        if ($this instanceof BooleanField) return $this->getName();
        if ($this instanceof ForeignKeyField) return 'get'. ucfirst($this->getName());
        return 'get'. ucfirst($this->getName()) . 'Data';
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
    /** @deprecated  */

    public function setIsVisibleInCreateView(bool $enabled = true): static
    {
        $this->showInCreateView = new BooleanValue($enabled);
        return $this;
    }

    /** @deprecated  */
    public function isVisibleInCreateView(): bool
    {
        return $this->showInCreateView->getValue();
    }

    /** @deprecated  */
    public function setIsEditableInCreateView(bool $enabled = true): static
    {
        $this->editInCreateView = new BooleanValue($enabled);
        return $this;
    }
    /** @deprecated  */

    public function isEditableInCreateView(): bool
    {
        return $this->editInCreateView->getValue();
    }

    /** @deprecated  */
    public function setIsHiddenInCreateView(bool $enabled = true): static
    {
        $this->hideInCreateView = new BooleanValue($enabled);
        return $this;
    }

    /** @deprecated  */
    public function isHiddenInCreateView(): bool
    {
        return $this->hideInCreateView->getValue();
    }
    /** @deprecated  */

    public function setIsDataInCreateView(bool $enabled = true): static
    {
        $this->hideInCreateView = new BooleanValue($enabled);
        return $this;
    }
    /** @deprecated  */

    public function isDataInCreateView(): bool
    {
        return $this->dataInCreateView->getValue();
    }

    /** @deprecated  */
    public function setIsVisibleInUpdateView(bool $enabled = true): static
    {
        $this->showInUpdateView = new BooleanValue($enabled);
        return $this;
    }

    /** @deprecated  */
    public function isVisibleInUpdateView(): bool
    {
        return $this->showInUpdateView->getValue();
    }

    /** @deprecated  */
    public function setIsEditableInUpdateView(bool $enabled = true): static
    {
        $this->editInUpdateView = new BooleanValue($enabled);
        return $this;
    }

    /** @deprecated  */
    public function isEditableInUpdateView(): bool
    {
        return $this->editInUpdateView->getValue();
    }

    /** @deprecated  */
    public function setIsHiddenInUpdateView(bool $enabled = true): static
    {
        $this->hideInUpdateView = new BooleanValue($enabled);
        return $this;
    }

    /** @deprecated  */
    public function isHiddenInUpdateView(): bool
    {
        return $this->hideInUpdateView->getValue();
    }

    /** @deprecated  */
    public function setIsDataInUpdateView(bool $enabled = true): static
    {
        $this->dataInUpdateView = new BooleanValue($enabled);
        return $this;
    }

    /** @deprecated  */
    public function isDataInUpdateView(): bool
    {
        return $this->dataInUpdateView->getValue();
    }

    public function getModeInCreateView(): string
    {
        if ($this->hasViewConfigured('create')) {
            return $this->configuredViews['create']->getMode();
        }
        if ($this->isEditableInCreateView()) return 'edit';
        if ($this->isVisibleInCreateView()) return 'read';
        if ($this->isHiddenInCreateView()) return 'hide';
        if ($this->isDataInCreateView()) return 'data';
        return 'data';
    }

    public function getModeInUpdateView(): string
    {
        if ($this->hasViewConfigured('edit')) {
            return $this->configuredViews['edit']->getMode();
        }
        if ($this->isEditableInUpdateView()) return 'edit';
        if ($this->isVisibleInUpdateView()) return 'read';
        if ($this->isHiddenInUpdateView()) return 'hide';
        if ($this->isDataInUpdateView()) return 'data';
        return 'data';
    }

    public function configureView(FieldViewConfig $config): static
    {
        $this->configuredViews[$config->getName()] = $config;
        return $this;
    }

    public function hasViewConfigured(string $name)
    {
        return isset($this->configuredViews[$name]);
    }

    public function getViewConfig(string $name): FieldViewConfig
    {
        return $this->configuredViews[$name];
    }

    public function setDefaultValue($value)
    {
        $this->defaultValue[0] = $value;
        return $this;
    }

    public function hasDefaultValue(): bool
    {
        return isset($this->defaultValue[0]);
    }

    public function getDefaultValue(): mixed
    {
        if (is_callable($this->defaultValue[0])) {
            return call_user_func($this->defaultValue[0]);
        }

        return $this->defaultValue[0];
    }
}