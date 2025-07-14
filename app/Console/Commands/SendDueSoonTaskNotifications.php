<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Notification;

class SendDueSoonTaskNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:send-due-soon-notifications {--days=3 : Number of days ahead to check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send due soon task notifications to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        
        // get tasks due within the specified days that haven't been completed
        $dueSoonTasks = Task::with(['assignedTo', 'assignedBy'])
            ->where('status', '!=', 'completed')
            ->where('deadline', '>=', now())
            ->where('deadline', '<=', now()->addDays($days))
            ->get();

        $this->info("Found {$dueSoonTasks->count()} tasks due within {$days} days.");

        $notifiedCount = 0;
        $errorCount = 0;

        foreach ($dueSoonTasks as $task) {
            try {
                // send custom due soon notification
                $task->assignedTo->notify(new class($task) extends \Illuminate\Notifications\Notification {
                    use \Illuminate\Bus\Queueable;
                    use \Illuminate\Contracts\Queue\ShouldQueue;

                    public function __construct(public $task) {}

                    public function via($notifiable): array
                    {
                        return ['mail'];
                    }

                    public function toMail($notifiable): MailMessage
                    {
                        $daysUntilDue = now()->diffInDays($this->task->deadline, false);
                        $dueText = $daysUntilDue === 0 ? 'today' : "in {$daysUntilDue} day" . ($daysUntilDue > 1 ? 's' : '');

                        return (new MailMessage)
                            ->subject('📅 Task Due Soon: ' . $this->task->title)
                            ->greeting('Hello ' . $notifiable->name . '!')
                            ->line('📅 **Reminder: You have a task due soon!**')
                            ->line('**Task Title:** ' . $this->task->title)
                            ->line('**Description:** ' . ($this->task->description ?: 'No description provided'))
                            ->line('**Due:** ' . $this->task->deadline->format('F j, Y \a\t g:i A') . " ({$dueText})")
                            ->line('**Current Status:** ' . ucfirst(str_replace('_', ' ', $this->task->status)))
                            ->action('Update Task Status', url('/dashboard'))
                            ->line('Please review and update the task status as needed.');
                    }
                });
                
                $notifiedCount++;
                $this->line("✓ Notified {$task->assignedTo->name} about task due soon: {$task->title}");
            } catch (\Exception $e) {
                $errorCount++;
                Log::error("Failed to send due soon notification for task {$task->id}: " . $e->getMessage());
                $this->error("✗ Failed to notify {$task->assignedTo->name} about task: {$task->title}");
            }
        }

        $this->info("Notification summary:");
        $this->info("- Successfully sent: {$notifiedCount}");
        $this->info("- Errors: {$errorCount}");

        return 0;
    }
} 