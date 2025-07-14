<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskOverdueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Task $task)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $daysOverdue = now()->diffInDays($this->task->deadline);

        return (new MailMessage)
            ->subject('⚠️ Task Overdue: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('⚠️ **URGENT: This task is overdue!**')
            ->line('**Task Title:** ' . $this->task->title)
            ->line('**Description:** ' . ($this->task->description ?: 'No description provided'))
            ->line('**Original Deadline:** ' . $this->task->deadline->format('F j, Y \a\t g:i A'))
            ->line('**Days Overdue:** ' . $daysOverdue . ' day' . ($daysOverdue > 1 ? 's' : ''))
            ->line('**Current Status:** ' . ucfirst(str_replace('_', ' ', $this->task->status)))
            ->action('Update Task Status', url('/dashboard'))
            ->line('Please update the task status or contact your administrator if you need an extension.')
            ->line('Thank you for your attention to this matter.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'deadline' => $this->task->deadline,
            'days_overdue' => now()->diffInDays($this->task->deadline),
            'assigned_by' => $this->task->assignedBy->name,
        ];
    }
} 