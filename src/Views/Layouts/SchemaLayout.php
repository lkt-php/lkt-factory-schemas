<?php

namespace Lkt\Factory\Schemas\Views\Layouts;

class SchemaLayout
{
    protected string $name = '';
    protected array $conditionalModes = [];
    protected array $conditionalTypes = [];

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
}