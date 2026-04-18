<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $senderName;
    public $senderType;
    public $senderId;
    public $type;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $message, $senderName = 'System', $senderType = null, $senderId = null, $type = 'info')
    {
        $this->title = $title;
        $this->message = $message;
        $this->senderName = $senderName;
        $this->senderType = $senderType;
        $this->senderId = $senderId;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'sender' => $this->senderName,
            'sender_type' => $this->senderType,
            'sender_id' => $this->senderId,
            'type' => $this->type,
            'time' => now(),
        ];
    }
}
