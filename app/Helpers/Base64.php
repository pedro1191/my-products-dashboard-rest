<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class Base64
{
    public static function generateBase64String(UploadedFile | string $file): string
    {
        return self::getBase64String(File::mimeType($file), File::get($file));
    }

    private static function getBase64String(string $contentType, string $fileContents): string
    {
        return "data:{$contentType};base64," . base64_encode($fileContents);
    }
}
