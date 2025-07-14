<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskCompletedNotification extends Notification implements ShouldQueue
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
        $completionTime = $this->task->updated_at->format('F j, Y \a\t g:i A');
        $wasOverdue = $this->task->deadline < $this->task->updated_at;

        return (new MailMessage)
            ->subject('✅ Task Completed: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('🎉 **Great news! A task has been completed.**')
            ->line('**Task Title:** ' . $this->task->title)
            ->line('**Description:** ' . ($this->task->description ?: 'No description provided'))
            ->line('**Completed By:** ' . $this->task->assignedTo->name)
            ->line('**Completed At:** ' . $completionTime)
            ->line('**Original Deadline:** ' . $this->task->deadline->format('F j, Y \a\t g:i A'))
            ->line($wasOverdue ? '⚠️ **Note: This task was completed after the deadline.**' : '✅ **Task completed on time!**')
            ->action('View Task Details', url('/admin/tasks/' . $this->task->id))
            ->line('Thank you for using our task management system!');
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
            'completed_by' => $this->task->assignedTo->name,
            'completed_at' => $this->task->updated_at,
            'deadline' => $this->task->deadline,
            'was_overdue' => $this->task->deadline < $this->task->updated_at,
        ];
    }
} 