<?php

namespace App\Services\Email;

use App\Helpers\EmailHelper;
use App\Notifications\MessageSent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class Send
{
    public function execute(array $data): void
    {
        Notification::route('mail', config('mail.mailers.smtp.username'))->notify(new MessageSent($data));

        $messageSender = EmailHelper::formatMessageSender($data);
        $infoMessage = 'Email from ' . $messageSender . ' has been sent...';
        Log::info($infoMessage, ['file' => __FILE__, 'line' => __LINE__, 'method' => __METHOD__]);
    }
}
