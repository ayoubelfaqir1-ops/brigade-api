<?php

namespace App\Enums;

enum DietaryTag: string
{
    case VEGAN          = 'vegan';
    case NO_SUGAR       = 'no_sugar';
    case NO_CHOLESTEROL = 'no_cholesterol';
    case GLUTEN_FREE    = 'gluten_free';
    case NO_LACTOSE     = 'no_lactose';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
