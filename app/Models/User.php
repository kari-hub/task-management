<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'appearance',
        'two-factor-code',
        'two-factor-expires-at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two-factor-expires-at' => 'datetime',
        ];
    }
    // Two-Factor Authentication
    public function generateTwoFactorCode(): void
    {
        $this->timestamps = false; //prevents updating the timestamp
        $this->two_factor_code = rand(100000, 999999); //generate a random 6-digit code
        $this->two_factor_expires_at = now()->addMinutes(10); //code expires in 10 minutes
        $this->save();
    }

    // Reset the two-factor code
    public function resetTwoFactorCode(): void
    {
        $this->timestamps = false;
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }

    // get the user's initials
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    // check if the user is an admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // check if the user is a regular user
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // get tasks assigned to this user
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    // get tasks created by this user (for admins)
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }
}
