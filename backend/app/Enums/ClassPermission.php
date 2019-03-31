<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ClassPermission extends Enum
{
    const ALLOW = "allow";
    const NOT_ALLOW = "not_allow";

    public static $type = [self::ALLOW, self::NOT_ALLOW];
}
