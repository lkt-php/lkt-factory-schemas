<?php

namespace Lkt\Factory\Schemas\ComputedFields;

use Lkt\Factory\Schemas\Fields\AbstractField;

abstract class AbstractComputedField extends AbstractField
{
    protected $value;
    protected $field;

    protected function setField(string $value)
    {
        $this->field = $value;
        return $this;
    }

    protected function setValue($value = null)
    {
        $this->value = $value;
        return $this;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getValue()
    {
        return $this->value;
    }
}