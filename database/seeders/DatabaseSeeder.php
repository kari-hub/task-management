<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Create regular users
        $user1 = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'user',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'role' => 'user',
        ]);

        // Create sample tasks
        Task::create([
            'title' => 'Complete Project Documentation',
            'description' => 'Write comprehensive documentation for the new feature implementation',
            'assigned_to' => $user1->id,
            'assigned_by' => $admin->id,
            'deadline' => Carbon::now()->addDays(7),
            'status' => Task::STATUS_PENDING,
        ]);

        Task::create([
            'title' => 'Review Code Changes',
            'description' => 'Review and approve the latest pull request for the authentication module',
            'assigned_to' => $user2->id,
            'assigned_by' => $admin->id,
            'deadline' => Carbon::now()->addDays(3),
            'status' => Task::STATUS_IN_PROGRESS,
        ]);

        Task::create([
            'title' => 'Database Optimization',
            'description' => 'Optimize database queries and add necessary indexes for better performance',
            'assigned_to' => $user1->id,
            'assigned_by' => $admin->id,
            'deadline' => Carbon::now()->addDays(14),
            'status' => Task::STATUS_PENDING,
        ]);

        Task::create([
            'title' => 'User Interface Testing',
            'description' => 'Test the new user interface components across different browsers',
            'assigned_to' => $user2->id,
            'assigned_by' => $admin->id,
            'deadline' => Carbon::now()->subDays(1), // Overdue task
            'status' => Task::STATUS_PENDING,
        ]);

        Task::create([
            'title' => 'API Integration',
            'description' => 'Integrate third-party API for payment processing',
            'assigned_to' => $user1->id,
            'assigned_by' => $admin->id,
            'deadline' => Carbon::now()->addDays(5),
            'status' => Task::STATUS_COMPLETED,
        ]);
    }
}
