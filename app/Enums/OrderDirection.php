<?php

namespace App\Enums;

enum OrderDirection: string
{
    case Ascending = 'asc';
    case Descending = 'desc';

    public static function csv(): string
    {
        return implode(',', array_column(self::cases(), 'value'));
    }
}
