<?php

namespace App\Helpers;

class StringHelper
{
    public static function prepareForDBSearch(string $string): string
    {
        return '%' . mb_strtolower(trim($string)) . '%';
    }
}
