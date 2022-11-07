<?php

namespace Lkt\Factory\Schemas\Traits;

use Lkt\Factory\Schemas\Values\BooleanValue;

trait FieldWithCompressOptionTrait
{
    protected $compress;

    /**
     * @param bool $compress
     * @return $this
     */
    final public function setIsCompressed(bool $compress = true): self
    {
        $this->compress = new BooleanValue($compress);
        return $this;
    }

    /**
     * @return bool
     */
    final public function isCompressed(): bool
    {
        if ($this->compress instanceof BooleanValue) {
            return $this->compress->getValue();
        }
        return false;
    }
}