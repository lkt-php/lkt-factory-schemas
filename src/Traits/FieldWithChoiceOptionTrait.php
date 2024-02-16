<?php

namespace Lkt\Factory\Schemas\Traits;

trait FieldWithChoiceOptionTrait
{
    protected array $allowedOptions = [];

    protected array $compareIn = [];

    protected bool $enabledEmptyPreset = false;
    protected string|int|null $emptyDefault = null;

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

    final public function setEnabledEmptyPreset($enabled = true): static
    {
        $this->enabledEmptyPreset = $enabled;
        return $this;
    }

    final public function hasEnabledEmptyPreset(): bool
    {
        return $this->enabledEmptyPreset;
    }

    final public function setEmptyDefault(string|int $value): static
    {
        $this->emptyDefault = $value;
        return $this;
    }

    final public function hasEmptyDefault(): bool
    {
        return $this->emptyDefault !== null;
    }

    final public function getEmptyDefault(): int|string|null
    {
        return $this->emptyDefault;
    }
}