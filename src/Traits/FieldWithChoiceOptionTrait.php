<?php

namespace Lkt\Factory\Schemas\Traits;

trait FieldWithChoiceOptionTrait
{
    protected array $allowedOptions = [];

    final public function setAllowedOptions(array $options): self
    {
        $this->allowedOptions = $options;
        return $this;
    }

    final public function getAllowedOptions(): array
    {
        return $this->allowedOptions;
    }
}