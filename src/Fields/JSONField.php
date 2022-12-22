<?php

namespace Lkt\Factory\Schemas\Fields;

use Lkt\Factory\Schemas\Traits\FieldWithCompressOptionTrait;
use Lkt\Factory\Schemas\Traits\FieldWithNullOptionTrait;
use Lkt\Factory\Schemas\Values\BooleanValue;

class JSONField extends AbstractField
{
    use FieldWithCompressOptionTrait,
        FieldWithNullOptionTrait;

    protected ?BooleanValue $assoc = null;

    public function setIsAssoc(bool $assoc = true): static
    {
        $this->assoc = new BooleanValue($assoc);
        return $this;
    }

    public function isAssoc(bool $assoc = true): bool
    {
        if ($this->assoc instanceof BooleanValue) {
            return $this->assoc->getValue();
        }
        return false;
    }
}