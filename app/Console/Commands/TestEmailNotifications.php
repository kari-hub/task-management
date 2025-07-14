<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskStatusUpdatedNotification;
use App\Notifications\TaskCompletedNotification;
use App\Notifications\TaskOverdueNotification;
use Illuminate\Console\Command;

class TestEmailNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:test {--user-id= : Specific user ID to test with}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email notifications by sending sample notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');
        
        // Get a user to test with
        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found.");
                return 1;
            }
        } else {
            $user = User::where('role', 'user')->first();
            if (!$user) {
                $this->error("No regular users found in the system.");
                return 1;
            }
        }

        // Get a sample task
        $task = Task::with(['assignedTo', 'assignedBy'])->first();
        if (!$task) {
            $this->error("No tasks found in the system.");
            return 1;
        }

        $this->info("Testing notifications for user: {$user->name} ({$user->email})");
        $this->info("Using task: {$task->title}");

        // Test TaskAssignedNotification
        $this->info("\n1. Testing TaskAssignedNotification...");
        try {
            $user->notify(new TaskAssignedNotification($task));
            $this->info("✓ TaskAssignedNotification sent successfully");
        } catch (\Exception $e) {
            $this->error("✗ TaskAssignedNotification failed: " . $e->getMessage());
        }

        // Test TaskStatusUpdatedNotification
        $this->info("\n2. Testing TaskStatusUpdatedNotification...");
        try {
            $user->notify(new TaskStatusUpdatedNotification($task, 'pending', 'in_progress'));
            $this->info("✓ TaskStatusUpdatedNotification sent successfully");
        } catch (\Exception $e) {
            $this->error("✗ TaskStatusUpdatedNotification failed: " . $e->getMessage());
        }

        // Test TaskCompletedNotification
        $this->info("\n3. Testing TaskCompletedNotification...");
        try {
            $user->notify(new TaskCompletedNotification($task));
            $this->info("✓ TaskCompletedNotification sent successfully");
        } catch (\Exception $e) {
            $this->error("✗ TaskCompletedNotification failed: " . $e->getMessage());
        }

        // Test TaskOverdueNotification
        $this->info("\n4. Testing TaskOverdueNotification...");
        try {
            $user->notify(new TaskOverdueNotification($task));
            $this->info("✓ TaskOverdueNotification sent successfully");
        } catch (\Exception $e) {
            $this->error("✗ TaskOverdueNotification failed: " . $e->getMessage());
        }

        $this->info("\n🎉 Notification testing completed!");
        $this->info("Check your email at: {$user->email}");
        $this->info("Note: If using 'log' driver, check storage/logs/laravel.log for email content");

        return 0;
    }
} 