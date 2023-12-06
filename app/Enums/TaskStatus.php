<?php

namespace App\Enums;

enum TaskStatus: int
{
    case TODO = 0;
    case DONE = 1;

    public static function randElem(): int
    {
        $values =  array_column(self::cases(), 'value');
        return $values[array_rand($values)];
    }
}
