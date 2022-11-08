<?php

namespace Lkt\Factory\Schemas\Traits;

use Lkt\Factory\Schemas\Exceptions\InvalidComponentException;
use Lkt\Factory\Schemas\Values\ComponentValue;

trait FieldWithPivotOptionTrait
{
    protected $pivotComponent;

    /**
     * @param string $component
     * @return FieldWithPivotOptionTrait|\Lkt\Factory\Schemas\Fields\PivotField
     * @throws InvalidComponentException
     */
    final public function setPivotComponent(string $component = ''): self
    {
        $this->pivotComponent = new ComponentValue($component);
        return $this;
    }

    /**
     * @return string
     */
    final public function getPivotComponent(): string
    {
        if ($this->pivotComponent instanceof ComponentValue) {
            return $this->pivotComponent->getValue();
        }
        return '';
    }
}