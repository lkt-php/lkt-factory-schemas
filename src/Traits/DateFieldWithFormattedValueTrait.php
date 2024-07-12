<?php

namespace Lkt\Factory\Schemas\Traits;

trait DateFieldWithFormattedValueTrait
{
    protected string $defaultReadFormat = '';

    public function setDefaultReadFormat(string $format): static
    {
        $this->defaultReadFormat = $format;
        return $this;
    }

    public function getDefaultReadFormat(): string
    {
        return $this->defaultReadFormat;
    }
}