<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Add indexes for frequently queried columns (only if they don't exist)
            if (!Schema::hasIndex('tasks', 'tasks_assigned_to_index')) {
                $table->index('assigned_to');
            }
            if (!Schema::hasIndex('tasks', 'tasks_deadline_index')) {
                $table->index('deadline');
            }
            if (!Schema::hasIndex('tasks', 'tasks_assigned_to_status_index')) {
                $table->index(['assigned_to', 'status']);
            }
            if (!Schema::hasIndex('tasks', 'tasks_assigned_to_deadline_index')) {
                $table->index(['assigned_to', 'deadline']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['assigned_to']);
            $table->dropIndex(['status']);
            $table->dropIndex(['deadline']);
            $table->dropIndex(['assigned_to', 'status']);
            $table->dropIndex(['assigned_to', 'deadline']);
        });
    }
};
