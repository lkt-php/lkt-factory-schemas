<?php

namespace Lkt\Factory\Schemas\ValueObjects;

use Lkt\Factory\Schemas\Fields\AbstractField;

class AccessPolicy
{
    public string $name;

    /**
     * Indicates which fields should be included while reading and writing data.
     *
     * @var string[]
     * Formatting:
     *   Case 1: Numeric index means "get the field included in the value"
     *   Case 2: String index means "get the field defined in the value and uses the key value as the name
     *           This applies in both cases: reading and writing data
     */
    public array $availableFields;

    /**
     * Represents which fields holding a composition config should add their composed values while reading or
     * have in count while writing.
     * Keeps the same format as $availableFields var
     *
     * @var string[]
     */
    public array $availableCompositionFields;

    public function __construct(string $name, array $availableFields, array $availableCompositionFields)
    {
        $this->name = $name;
        $this->availableFields = $availableFields;
        $this->availableCompositionFields = $availableCompositionFields;
    }

    public function includesField(AbstractField $field): bool
    {
        return in_array($field->getName(), $this->availableFields);
    }

    public function includesCompositionField(AbstractField $field): bool
    {
        return in_array($field->getName(), $this->availableCompositionFields);
    }
}