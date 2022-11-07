<?php

namespace Lkt\Factory\Schemas\Traits;

use Lkt\Factory\Schemas\Values\BooleanValue;

trait FieldWithSoftTypedOptionTrait
{
    protected $softTyped;

    /**
     * @param bool $softTyped
     * @return $this
     */
    final public function setIsSoftTyped(bool $softTyped = true): self
    {
        $this->softTyped = new BooleanValue($softTyped);
        return $this;
    }

    /**
     * @return bool
     */
    final public function isSoftTyped(): bool
    {
        if ($this->softTyped instanceof BooleanValue) {
            return $this->softTyped->getValue();
        }
        return false;
    }
}