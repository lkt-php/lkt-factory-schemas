<?php

namespace Lkt\Factory\Schemas\Views\Layouts;

/**
 * @deprecated
 */
class SchemaLayout
{
    protected string $name = '';
    protected array $conditionalModes = [];
    protected array $conditionalTypes = [];
    protected array $conditionallyHidden = [];
    protected array $conditionallyVisible = [];

    protected string $boxTitle = '';
    protected string $boxComponent = '';

    public function getName(): string
    {
        return $this->name;
    }

    public function setConditionalModes(string $field, mixed $value, array $modes): static
    {
        $this->conditionalModes[] = [
            'field' => $field,
            'value' => $value,
            'modes' => $modes,
        ];
        return $this;
    }

    public function setConditionalTypes(string $field, mixed $value, array $types): static
    {
        $this->conditionalTypes[] = [
            'field' => $field,
            'value' => $value,
            'types' => $types,
        ];
        return $this;
    }

    public function setConditionallyHidden(string $field, mixed $value): static
    {
        $this->conditionallyHidden[$field] = $value;
        return $this;
    }

    public function setConditionallyVisible(string $field, mixed $value): static
    {
        $this->conditionallyVisible[$field] = $value;
        return $this;
    }
}