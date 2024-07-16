<?php

namespace Lkt\Factory\Schemas\Views\Layouts;

class SchemaLayout
{
    protected string $name = '';
    protected array $conditionalModes = [];

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
}