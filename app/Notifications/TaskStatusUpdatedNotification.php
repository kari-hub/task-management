<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Task $task,
        public string $oldStatus,
        public string $newStatus
    ) {
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
        $statusLabels = [
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Completed'
        ];

        return (new MailMessage)
            ->subject('Task Status Updated: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A task you assigned has been updated.')
            ->line('**Task Title:** ' . $this->task->title)
            ->line('**Assigned To:** ' . $this->task->assignedTo->name)
            ->line('**Status Changed:** ' . $statusLabels[$this->oldStatus] . ' → ' . $statusLabels[$this->newStatus])
            ->line('**Updated By:** ' . $this->task->assignedTo->name)
            ->line('**Updated At:** ' . now()->format('F j, Y \a\t g:i A'))
            ->action('View Task', url('/admin/tasks/' . $this->task->id))
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
            'assigned_to' => $this->task->assignedTo->name,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'updated_by' => $this->task->assignedTo->name,
        ];
    }
} 