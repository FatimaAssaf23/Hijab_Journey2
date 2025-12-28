<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class TeacherReassignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $className;
    public $originalTeacher;

    public function __construct($className, $originalTeacher)
    {
        $this->className = $className;
        $this->originalTeacher = $originalTeacher;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('You have been assigned as a substitute teacher')
            ->greeting('Hello ' . $notifiable->first_name . ',')
            ->line('You have been assigned as a substitute teacher for the class: ' . $this->className)
            ->line('Original teacher: ' . $this->originalTeacher)
            ->line('Please check your dashboard for more details.');
    }

    public function toArray($notifiable)
    {
        return [
            'class_name' => $this->className,
            'original_teacher' => $this->originalTeacher,
            'message' => 'You have been assigned as a substitute teacher for the class: ' . $this->className,
        ];
    }
}
