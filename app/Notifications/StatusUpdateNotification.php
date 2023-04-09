<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusUpdateNotification extends Notification
{
    use Queueable;

    protected $data_email;

    /**
     * Create a new notification instance.
     */
    public function __construct($data_email)
    {
        $this->data_email = $data_email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->greeting($this->data_email['greeting'])
                    ->line($this->data_email['body'])
                    ->action($this->data_email['action_text'], $this->data_email['action_url'])
                    ->line($this->data_email['thanks']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
