<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActionAlertNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected string $subject,
        protected string $headline,
        protected array $lines = []
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->subject)
            ->greeting('Namaste ' . $notifiable->name . ',')
            ->line($this->headline);

        foreach ($this->lines as $line) {
            $mail->line($line);
        }

        return $mail->line('Agar aapko koi issue lage to support se contact karein.');
    }
}
