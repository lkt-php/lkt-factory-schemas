<?php

namespace Lkt\Factory\Schemas\Traits;

use Lkt\Factory\Schemas\Exceptions\InvalidComponentException;
use Lkt\Factory\Schemas\Values\ComponentValue;

trait FieldWithComponentOptionTrait
{
    protected $component;

    /**
     * @param string $component
     * @return \Lkt\Factory\Schemas\Fields\RelatedKeysField|\Lkt\Factory\Schemas\Fields\ForeignKeyField|\Lkt\Factory\Schemas\Fields\ForeignKeysField|\Lkt\Factory\Schemas\Fields\PivotField|\Lkt\Factory\Schemas\Fields\RelatedField|FieldWithComponentOptionTrait
     * @throws InvalidComponentException
     */
    final public function setComponent(string $component = ''): self
    {
        $this->component = new ComponentValue($component);
        return $this;
    }

    /**
     * @return string
     */
    final public function getComponent(): string
    {
        if ($this->component instanceof ComponentValue) {
            return $this->component->getValue();
        }
        return '';
    }
}