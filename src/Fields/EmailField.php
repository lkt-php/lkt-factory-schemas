<?php

namespace Lkt\Factory\Schemas\Fields;

use Lkt\Factory\Schemas\Traits\FieldWithNullOptionTrait;

class EmailField extends AbstractField
{
    const TYPE = 'email';

    use FieldWithNullOptionTrait;
}