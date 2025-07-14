<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Role-based dashboard routing
Route::get('/dashboard', function () {
    if (Auth::check()) {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }
    return redirect()->route('login');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin dashboard
Route::view('admin/dashboard', 'admin.dashboard')
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.dashboard');

// User dashboard
Route::view('user/dashboard', 'user.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('user.dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Admin API Routes
Route::middleware(['auth', 'admin'])->prefix('api/admin')->group(function () {
    // User management
    Route::get('/users', [AdminController::class, 'getUsers'])->name('admin.users.index');
    Route::get('/users/{user}', [AdminController::class, 'getUser'])->name('admin.users.show');
    Route::post('/users', [AdminController::class, 'createUser'])->name('admin.users.store');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.destroy');
    
    // Task management
    Route::get('/tasks', [AdminController::class, 'getAllTasks'])->name('admin.tasks.index');
    Route::post('/tasks', [AdminController::class, 'assignTask'])->name('admin.tasks.store');
    Route::put('/tasks/{task}', [AdminController::class, 'updateTask'])->name('admin.tasks.update');
    Route::delete('/tasks/{task}', [AdminController::class, 'deleteTask'])->name('admin.tasks.destroy');
    
    // Dashboard statistics
    Route::get('/stats', [AdminController::class, 'getDashboardStats'])->name('admin.stats');
});

// User API Routes
Route::middleware(['auth'])->prefix('api/tasks')->group(function () {
    // Task operations for regular users
    Route::get('/my-tasks', [TaskController::class, 'getMyTasks'])->name('tasks.my-tasks');
    Route::get('/my-tasks/{task}', [TaskController::class, 'getTask'])->name('tasks.show');
    Route::patch('/my-tasks/{task}/status', [TaskController::class, 'updateTaskStatus'])->name('tasks.update-status');
    Route::get('/my-stats', [TaskController::class, 'getMyTaskStats'])->name('tasks.my-stats');
    Route::get('/my-tasks/status/{status}', [TaskController::class, 'getTasksByStatus'])->name('tasks.by-status');
    Route::get('/my-tasks/overdue', [TaskController::class, 'getOverdueTasks'])->name('tasks.overdue');
    Route::get('/my-tasks/due-soon', [TaskController::class, 'getDueSoonTasks'])->name('tasks.due-soon');
});

require __DIR__.'/auth.php';
