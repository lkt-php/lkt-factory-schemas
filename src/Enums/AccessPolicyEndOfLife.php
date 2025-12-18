<?php

namespace Lkt\Factory\Schemas\Enums;

enum AccessPolicyEndOfLife: int
{
    case UntilUpdated = 1;
    case UntilNextWrite = 2;
    case UntilNextRead = 3;
}