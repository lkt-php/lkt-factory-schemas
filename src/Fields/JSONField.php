<?php

namespace Lkt\Factory\Schemas\Fields;

use Lkt\Factory\Schemas\Traits\FieldWithCompressOptionTrait;
use Lkt\Factory\Schemas\Values\BooleanValue;

class JSONField extends AbstractField
{
    use FieldWithCompressOptionTrait;

    protected $assoc;

    /**
     * @param bool $assoc
     * @return $this
     */
    public function setIsAssoc(bool $assoc = true): self
    {
        $this->assoc = new BooleanValue($assoc);
        return $this;
    }
}