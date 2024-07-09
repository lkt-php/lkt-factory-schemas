<?php

namespace Lkt\Factory\Schemas\Fields;

use Lkt\Factory\Schemas\Traits\FieldWithComponentOptionTrait;
use Lkt\Factory\Schemas\Traits\FieldWithMultipleReferencesTrait;
use Lkt\Factory\Schemas\Traits\FieldWithOrderOptionTrait;
use Lkt\Factory\Schemas\Traits\FieldWithPaginationOptionTrait;
use Lkt\Factory\Schemas\Traits\FieldWithSingleModeOptionTrait;
use Lkt\Factory\Schemas\Traits\FieldWithSoftTypedOptionTrait;
use Lkt\Factory\Schemas\Traits\FieldWithWhereOptionTrait;

class RelatedField extends AbstractField
{
    const TYPE = 'related';

    use FieldWithComponentOptionTrait,
        FieldWithWhereOptionTrait,
        FieldWithOrderOptionTrait,
        FieldWithSoftTypedOptionTrait,
        FieldWithSingleModeOptionTrait,
        FieldWithMultipleReferencesTrait,
        FieldWithPaginationOptionTrait;

    protected $relatedComponentFeeds = [];

    public static function defineRelation(string $component, string $name, string $column = ''): static
    {
        return (new static($name, $column))->setComponent($component);
    }

    public function addRelatedComponentFeed(string $column, $value): static
    {
        $this->relatedComponentFeeds[$column] = $value;
        return $this;
    }

    public function getRelatedComponentFeeds(): array
    {
        return $this->relatedComponentFeeds;
    }
}