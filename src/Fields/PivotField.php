<?php

namespace Lkt\Factory\Schemas\Fields;

use Lkt\Factory\Schemas\Traits\FieldWithComponentOptionTrait;
use Lkt\Factory\Schemas\Traits\FieldWithOrderOptionTrait;
use Lkt\Factory\Schemas\Traits\FieldWithWhereOptionTrait;

class PivotField extends AbstractField
{
    use FieldWithComponentOptionTrait,
        FieldWithWhereOptionTrait,
        FieldWithOrderOptionTrait;
}