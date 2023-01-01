<?php

namespace App\Helpers;

class RandomImage
{
    const IMAGE_SOURCE = 'https://loremflickr.com/';
    const IMAGE_WIDTH = 300;
    const IMAGE_HEIGHT = 300;
    const IMAGE_CATEGORIES = 'food,dish';

    public static function url(): string
    {
        return self::IMAGE_SOURCE
            . self::IMAGE_WIDTH
            . '/'
            . self::IMAGE_HEIGHT
            . '/'
            . self::IMAGE_CATEGORIES;
    }

    public static function pathToSave(): string
    {
        return tempnam(sys_get_temp_dir(), config('filesystems.default'));
    }
}
