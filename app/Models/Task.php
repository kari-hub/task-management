<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Task extends Model
{
    // task status constants
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';

    // the attributes that are mass assignable
    protected $fillable = [
        'title',
        'description',
        'assigned_to',
        'assigned_by',
        'deadline',
        'status',
    ];

    // get the attributes that should be cast
    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
        ];
    }

    // get the user this task is assigned to
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // get the user who created/assigned this task
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // check if the task is overdue
    public function isOverdue(): bool
    {
        return $this->deadline < Carbon::now() && $this->status !== self::STATUS_COMPLETED;
    }

    // check if the task is due today
    public function isDueToday(): bool
    {
        return $this->deadline->isToday();
    }

    // check if the task is due soon (within 3 days)
    public function isDueSoon(): bool
    {
        return $this->deadline->diffInDays(Carbon::now()) <= 3 && $this->status !== self::STATUS_COMPLETED;
    }

    // get CSS class for status display
    public function getStatusColor(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_IN_PROGRESS => 'bg-blue-100 text-blue-800',
            self::STATUS_COMPLETED => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // check if a user can update this task
    public function canBeUpdatedBy(User $user): bool
    {
        return $user->isAdmin() || $this->assigned_to === $user->id;
    }

    // get all available statuses
    public static function getAvailableStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_COMPLETED => 'Completed',
        ];
    }

    // scope to get tasks assigned to a specific user
    public function scopeAssignedTo($query, $userId): object
    {
        return $query->where('assigned_to', $userId);
    }

    // scope to get tasks by status
    public function scopeByStatus($query, $status): object
    {
        return $query->where('status', $status);
    }

    // scope to get overdue tasks
    public function scopeOverdue($query)
    {
        return $query->where('deadline', '<', Carbon::now())
                    ->where('status', '!=', self::STATUS_COMPLETED);
    }

    // scope to get tasks due soon
    public function scopeDueSoon($query)
    {
        return $query->where('deadline', '<=', Carbon::now()->addDays(3))
                    ->where('deadline', '>=', Carbon::now())
                    ->where('status', '!=', self::STATUS_COMPLETED);
    }
}
