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
        $tasks = Task::with(['assignedBy:id,name,email'])
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

    // update task status (users can only update their assigned tasks)
    public function updateTaskStatus(Request $request, Task $task): JsonResponse
    {
        // check if the task is assigned to the authenticated user
        if ($task->assigned_to !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $oldStatus = $task->status;
        $newStatus = $validated['status'];

        $task->update(['status' => $newStatus]);

        // send notifications
        try {
            // notify admin about status update
            if ($oldStatus !== $newStatus) {
                $admin = User::find($task->assigned_by);
                if ($admin) {
                    $admin->notify(new TaskStatusUpdatedNotification($task, $oldStatus, $newStatus));
                }

                // if task was completed, send completion notification
                if ($newStatus === 'completed') {
                    $admin->notify(new TaskCompletedNotification($task));
                }
            }
        } catch (\Exception $e) {
            // log the error but don't fail the status update
            Log::error('Failed to send task status update notification: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Task status updated successfully',
            'task' => $task->load(['assignedTo', 'assignedBy'])
        ]);
    }

    // get user's task statistics
    public function getMyTaskStats(): JsonResponse
    {
        $userId = Auth::id();
        
        // Use a single query with conditional counting for better performance
        $taskCounts = Task::where('assigned_to', $userId)
            ->selectRaw('
                COUNT(*) as total_assigned,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as in_progress,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN deadline < NOW() AND status != ? THEN 1 ELSE 0 END) as overdue,
                SUM(CASE WHEN deadline BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 3 DAY) AND status != ? THEN 1 ELSE 0 END) as due_soon
            ', [
                Task::STATUS_PENDING,
                Task::STATUS_IN_PROGRESS,
                Task::STATUS_COMPLETED,
                Task::STATUS_COMPLETED,
                Task::STATUS_COMPLETED
            ])
            ->first();

        $stats = [
            'total_assigned' => (int) $taskCounts->total_assigned,
            'pending' => (int) $taskCounts->pending,
            'in_progress' => (int) $taskCounts->in_progress,
            'completed' => (int) $taskCounts->completed,
            'overdue' => (int) $taskCounts->overdue,
            'due_soon' => (int) $taskCounts->due_soon,
        ];

        return response()->json(['stats' => $stats]);
    }

    // get tasks by status for the authenticated user
    public function getTasksByStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $tasks = Task::with(['assignedBy:id,name,email'])
            ->where('assigned_to', Auth::id())
            ->where('status', $validated['status'])
            ->orderBy('deadline', 'asc')
            ->get();

        return response()->json(['tasks' => $tasks]);
    }

    // get overdue tasks for the authenticated user
    public function getOverdueTasks(): JsonResponse
    {
        $tasks = Task::with(['assignedBy:id,name,email'])
            ->where('assigned_to', Auth::id())
            ->overdue()
            ->orderBy('deadline', 'asc')
            ->get();

        return response()->json(['tasks' => $tasks]);
    }

    // get tasks due soon for the authenticated user
    public function getDueSoonTasks(): JsonResponse
    {
        $tasks = Task::with(['assignedBy:id,name,email'])
            ->where('assigned_to', Auth::id())
            ->dueSoon()
            ->orderBy('deadline', 'asc')
            ->get();

        return response()->json(['tasks' => $tasks]);
    }
}
