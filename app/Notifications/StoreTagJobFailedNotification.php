<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StoreTagJobFailedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public $user,
        public $title,
    ){}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Storing by ".$this->user->name." tag with title ".$this->title." failed. Check logs to define error.",
        ];
    }
}
