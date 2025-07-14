<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskOverdueNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendOverdueTaskNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:send-overdue-notifications {--days=1 : Number of days to wait before sending notification}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send overdue task notifications to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        
        // Get overdue tasks that haven't been completed
        $overdueTasks = Task::with(['assignedTo', 'assignedBy'])
            ->where('status', '!=', 'completed')
            ->where('deadline', '<', now()->subDays($days))
            ->get();

        $this->info("Found {$overdueTasks->count()} overdue tasks to notify about.");

        $notifiedCount = 0;
        $errorCount = 0;

        foreach ($overdueTasks as $task) {
            try {
                // Send notification to the assigned user
                $task->assignedTo->notify(new TaskOverdueNotification($task));
                $notifiedCount++;
                
                $this->line("✓ Notified {$task->assignedTo->name} about overdue task: {$task->title}");
            } catch (\Exception $e) {
                $errorCount++;
                Log::error("Failed to send overdue notification for task {$task->id}: " . $e->getMessage());
                $this->error("✗ Failed to notify {$task->assignedTo->name} about task: {$task->title}");
            }
        }

        $this->info("Notification summary:");
        $this->info("- Successfully sent: {$notifiedCount}");
        $this->info("- Errors: {$errorCount}");

        return 0;
    }
} 