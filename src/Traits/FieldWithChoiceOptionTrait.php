<?php

namespace Lkt\Factory\Schemas\Traits;

trait FieldWithChoiceOptionTrait
{
    protected array $allowedOptions = [];

    protected array $compareIn = [];

    final public function setAllowedOptions(array $options): static
    {
        $this->allowedOptions = $options;
        return $this;
    }

    final public function getAllowedOptions(): array
    {
        return $this->allowedOptions;
    }

    final public static function choice(array $options, string $name, string $column = ''): static
    {
        return (new static($name, $column))->setAllowedOptions($options);
    }

    final public function addComparatorIn(string $name, array $values): static
    {
        $this->compareIn[$name] = $values;
        return $this;
    }

    final public function getComparatorsIn(): array
    {
        return $this->compareIn;
    }
}