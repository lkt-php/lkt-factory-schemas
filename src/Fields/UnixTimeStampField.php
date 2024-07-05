<?php

namespace Lkt\Factory\Schemas\Fields;

use Lkt\Factory\Schemas\Traits\FieldWithFormatsOptionTrait;
use Lkt\Factory\Schemas\Traits\FieldWithNullOptionTrait;

class UnixTimeStampField extends AbstractField
{
    const TYPE = 'date';

    use FieldWithNullOptionTrait,
        FieldWithFormatsOptionTrait;
}