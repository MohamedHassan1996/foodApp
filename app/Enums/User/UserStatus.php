<?php

namespace App\Enums\User;

enum UserStatus: int{

    case ACTIVE = 1;
    case IN_ACTIVE = 0;
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
