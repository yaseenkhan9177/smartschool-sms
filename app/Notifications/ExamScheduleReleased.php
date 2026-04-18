<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExamScheduleReleased extends Notification
{
    use Queueable;

    protected $termName;

    /**
     * Create a new notification instance.
     */
    public function __construct($termName)
    {
        $this->termName = $termName;
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
            'title' => 'Date Sheet Released',
            'message' => "The Final Exam Date Sheet for {$this->termName} has been released. Download your Admit Card now.",
            'action_url' => route('student.exams.admit-card'),
            'icon' => 'fa-solid fa-file-contract',
            'color' => 'bg-purple-500' // Assuming frontend handles this
        ];
    }
}
