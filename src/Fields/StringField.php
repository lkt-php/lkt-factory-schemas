<?php

namespace Lkt\Factory\Schemas\Fields;

use Lkt\Factory\Schemas\Traits\FieldWithJsonI18nStorageTrait;
use Lkt\Factory\Schemas\Traits\FieldWithNullOptionTrait;

class StringField extends AbstractField
{
    const TYPE = 'text';

    use FieldWithNullOptionTrait,
        FieldWithJsonI18nStorageTrait;
}