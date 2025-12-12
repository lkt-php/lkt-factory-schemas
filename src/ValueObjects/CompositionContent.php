<?php

namespace Lkt\Factory\Schemas\ValueObjects;

use Lkt\Factory\Schemas\Exceptions\InvalidCompositionConfigException;
use Lkt\Factory\Schemas\Fields\ForeignKeyField;
use Lkt\Factory\Schemas\Fields\RelatedField;
use Lkt\Factory\Schemas\Schema;

class CompositionContent
{
    public string $parentComponent = '';

    public array $fields = [];

    public ForeignKeyField|RelatedField|null $relatedField;
    public string $relatedFieldName = '';

    public function __construct(string $parentComponent, ForeignKeyField|RelatedField|string $relatedField, array $fields)
    {
        $this->parentComponent = $parentComponent;
        $this->fields = $fields;
        $this->relatedField = ($relatedField instanceof RelatedField || $relatedField instanceof ForeignKeyField) ? $relatedField : null;
        $this->relatedFieldName = is_string($relatedField) ? $relatedField : '';

        if (!$this->relatedField instanceof RelatedField && !$this->relatedField instanceof ForeignKeyField) {
            $schema = Schema::get($this->parentComponent);
            $field = $schema->getField($this->relatedFieldName);
            if (!$field instanceof RelatedField && !$field instanceof ForeignKeyField) {
                throw InvalidCompositionConfigException::stringFieldNamePointingToNonRelatedField($this->parentComponent, $this->relatedFieldName);
            }
            $this->relatedField = $field;
        }
    }

    public function getFieldName(): string
    {
        return $this->relatedField->getName();
    }

    public function getRelatedField(): RelatedField|ForeignKeyField
    {
        return $this->relatedField;
    }
}