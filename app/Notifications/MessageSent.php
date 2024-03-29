<?php

namespace App\Notifications;

use App\Mail\MessageSent as Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MessageSent extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param  array  $data
     * @return void
     */
    public function __construct(private array $data)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new Mailable($this->data))->to($notifiable->routes['mail']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
