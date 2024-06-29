<?php

namespace Lkt\Factory\Schemas\Traits;

use Lkt\Factory\Schemas\Values\BooleanValue;

trait FieldWithJsonI18nStorageTrait
{
    protected ?BooleanValue $storeAsI18nJson = null;

    final public function setIsI18nJson(bool $allow = true): self
    {
        $this->storeAsI18nJson = new BooleanValue($allow);
        return $this;
    }

    final public function isI18nJson(): bool
    {
        if ($this->storeAsI18nJson instanceof BooleanValue) {
            return $this->storeAsI18nJson->getValue();
        }
        return false;
    }
}