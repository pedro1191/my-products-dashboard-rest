<?php

namespace App\Helpers;

class EmailHelper
{
    public static function formatMessageSender(array $messageData): string
    {
        return '"' . $messageData['name'] . '", "' . $messageData['email'] . '"';
    }
}
