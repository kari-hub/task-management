<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // get all users (excluding admin)
    public function getUsers(): JsonResponse
    {
        $users = User::where('role', 'user')
            ->withCount('assignedTasks')
            ->get();
        return response()->json(['users' => $users]);
    }

    // get a single user with their assigned tasks
    public function getUser(User $user): JsonResponse
    {
        $user->load(['assignedTasks' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);
        
        return response()->json(['user' => $user]);
    }

    // create a new user
    public function createUser(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required', 
                'string', 
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]).+$/'
            ],
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (!@#$%^&*()_+-=[]{}|;:,.<>?).',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    // update a user
    public function updateUser(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => [
                'nullable', 
                'string', 
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]).+$/'
            ],
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (!@#$%^&*()_+-=[]{}|;:,.<>?).',
        ]);

        // only hash password if provided
        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    // delete a user
    public function deleteUser(User $user): JsonResponse
    {
        // check if user has assigned tasks
        $assignedTasks = $user->assignedTasks()->where('status', '!=', Task::STATUS_COMPLETED)->count();
        
        if ($assignedTasks > 0) {
            return response()->json([
                'message' => 'Cannot delete user with active tasks. Please reassign or complete their tasks first.'
            ], 400);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }

    // get all tasks (admin view)
    public function getAllTasks(): JsonResponse
    {
        $tasks = Task::with(['assignedTo', 'assignedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['tasks' => $tasks]);
    }

    // create and assign a new task
    public function assignTask(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'deadline' => 'required|date|after:now',
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'assigned_to' => $validated['assigned_to'],
            'assigned_by' => Auth::id(),
            'deadline' => $validated['deadline'],
            'status' => Task::STATUS_PENDING,
        ]);

        // send email notification to assigned user (with error handling)
        try {
            $assignedUser = User::find($validated['assigned_to']);
            $assignedUser->notify(new TaskAssignedNotification($task));
        } catch (\Exception $e) {
            // log the error but don't fail the task creation
            Log::error('Failed to send task assignment notification: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Task assigned successfully',
            'task' => $task->load(['assignedTo', 'assignedBy'])
        ], 201);
    }

    // update a task (admin can update any task)
    public function updateTask(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'assigned_to' => 'sometimes|required|exists:users,id',
            'deadline' => 'sometimes|required|date',
            'status' => 'sometimes|required|in:pending,in_progress,completed',
        ]);

        $oldAssignedTo = $task->assigned_to;
        $task->update($validated);

        // send notification if task was reassigned to a different user
        if (isset($validated['assigned_to']) && $validated['assigned_to'] != $oldAssignedTo) {
            try {
                $newAssignedUser = User::find($validated['assigned_to']);
                if ($newAssignedUser) {
                    $newAssignedUser->notify(new TaskAssignedNotification($task));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send task reassignment notification: ' . $e->getMessage());
            }
        }

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task->load(['assignedTo', 'assignedBy'])
        ]);
    }

    // delete a task
    public function deleteTask(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }

    // get dashboard statistics
    public function getDashboardStats(): JsonResponse
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_tasks' => Task::count(),
            'pending_tasks' => Task::where('status', Task::STATUS_PENDING)->count(),
            'in_progress_tasks' => Task::where('status', Task::STATUS_IN_PROGRESS)->count(),
            'completed_tasks' => Task::where('status', Task::STATUS_COMPLETED)->count(),
            'overdue_tasks' => Task::overdue()->count(),
        ];

        return response()->json(['stats' => $stats]);
    }
}
