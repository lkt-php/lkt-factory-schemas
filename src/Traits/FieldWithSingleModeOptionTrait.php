<?php

namespace Lkt\Factory\Schemas\Traits;

use Lkt\Factory\Schemas\Values\BooleanValue;

trait FieldWithSingleModeOptionTrait
{
    protected $singleMode;

    /**
     * @param bool $isSingleMode
     * @return FieldWithSingleModeOptionTrait
     */
    final public function setSingleMode(bool $isSingleMode = true): self
    {
        $this->singleMode = new BooleanValue($isSingleMode);
        return $this;
    }

    /**
     * @return bool
     */
    final public function isSingleMode(): bool
    {
        if ($this->singleMode instanceof BooleanValue) {
            return $this->singleMode->getValue();
        }
        return false;
    }
}