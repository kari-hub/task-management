<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskStatusUpdatedNotification;
use App\Notifications\TaskCompletedNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Get tasks assigned to the authenticated user
     */
    public function getMyTasks(): JsonResponse
    {
        $tasks = Task::with(['assignedBy'])
            ->where('assigned_to', Auth::id())
            ->orderBy('deadline', 'asc')
            ->get();

        return response()->json(['tasks' => $tasks]);
    }

    /**
     * Get a specific task assigned to the user
     */
    public function getTask(Task $task): JsonResponse
    {
        // Check if the task is assigned to the authenticated user
        if ($task->assigned_to !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'task' => $task->load(['assignedTo', 'assignedBy'])
        ]);
    }

    /**
     * Update task status (users can only update their assigned tasks)
     */
    public function updateTaskStatus(Request $request, Task $task): JsonResponse
    {
        // Check if the task is assigned to the authenticated user
        if ($task->assigned_to !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $oldStatus = $task->status;
        $newStatus = $validated['status'];

        $task->update(['status' => $newStatus]);

        // Send notifications
        try {
            // Notify admin about status update
            if ($oldStatus !== $newStatus) {
                $admin = User::find($task->assigned_by);
                if ($admin) {
                    $admin->notify(new TaskStatusUpdatedNotification($task, $oldStatus, $newStatus));
                }

                // If task was completed, send completion notification
                if ($newStatus === 'completed') {
                    $admin->notify(new TaskCompletedNotification($task));
                }
            }
        } catch (\Exception $e) {
            // Log the error but don't fail the status update
            Log::error('Failed to send task status update notification: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Task status updated successfully',
            'task' => $task->load(['assignedTo', 'assignedBy'])
        ]);
    }

    /**
     * Get user's task statistics
     */
    public function getMyTaskStats(): JsonResponse
    {
        $userId = Auth::id();
        
        $stats = [
            'total_assigned' => Task::where('assigned_to', $userId)->count(),
            'pending' => Task::where('assigned_to', $userId)->where('status', Task::STATUS_PENDING)->count(),
            'in_progress' => Task::where('assigned_to', $userId)->where('status', Task::STATUS_IN_PROGRESS)->count(),
            'completed' => Task::where('assigned_to', $userId)->where('status', Task::STATUS_COMPLETED)->count(),
            'overdue' => Task::where('assigned_to', $userId)->overdue()->count(),
            'due_soon' => Task::where('assigned_to', $userId)->dueSoon()->count(),
        ];

        return response()->json(['stats' => $stats]);
    }

    /**
     * Get tasks by status for the authenticated user
     */
    public function getTasksByStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $tasks = Task::with(['assignedBy'])
            ->where('assigned_to', Auth::id())
            ->where('status', $validated['status'])
            ->orderBy('deadline', 'asc')
            ->get();

        return response()->json(['tasks' => $tasks]);
    }

    /**
     * Get overdue tasks for the authenticated user
     */
    public function getOverdueTasks(): JsonResponse
    {
        $tasks = Task::with(['assignedBy'])
            ->where('assigned_to', Auth::id())
            ->overdue()
            ->orderBy('deadline', 'asc')
            ->get();

        return response()->json(['tasks' => $tasks]);
    }

    /**
     * Get tasks due soon for the authenticated user
     */
    public function getDueSoonTasks(): JsonResponse
    {
        $tasks = Task::with(['assignedBy'])
            ->where('assigned_to', Auth::id())
            ->dueSoon()
            ->orderBy('deadline', 'asc')
            ->get();

        return response()->json(['tasks' => $tasks]);
    }
}
