<?php

namespace Lkt\Factory\Schemas\Traits;

use Lkt\Factory\Schemas\Exceptions\InvalidComponentException;
use Lkt\Factory\Schemas\Values\ComponentValue;

trait FieldWithComponentOptionTrait
{
    protected $component;

    /**
     * @param bool $component
     * @return $this
     * @throws InvalidComponentException
     */
    final public function setComponent(bool $component = true): self
    {
        $this->component = new ComponentValue($component);
        return $this;
    }

    /**
     * @return bool
     */
    final public function getComponent(): bool
    {
        if ($this->component instanceof ComponentValue) {
            return $this->component->getValue();
        }
        return false;
    }
}