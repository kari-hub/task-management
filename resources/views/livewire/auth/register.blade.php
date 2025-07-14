<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => [
                'required', 
                'string', 
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]).+$/'
            ],
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (!@#$%^&*()_+-=[]{}|;:,.<>?).',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }

     // get password strength score
    public function getPasswordStrength(): array
    {
        if (empty($this->password)) {
            return ['score' => 0, 'label' => '', 'color' => 'gray'];
        }

        $score = 0;
        $checks = [];

        // Length check
        if (strlen($this->password) >= 8) {
            $score += 1;
            $checks['length'] = true;
        } else {
            $checks['length'] = false;
        }

        // lowercase check
        if (preg_match('/[a-z]/', $this->password)) {
            $score += 1;
            $checks['lowercase'] = true;
        } else {
            $checks['lowercase'] = false;
        }

        // uppercase check
        if (preg_match('/[A-Z]/', $this->password)) {
            $score += 1;
            $checks['uppercase'] = true;
        } else {
            $checks['uppercase'] = false;
        }

        // number check
        if (preg_match('/\d/', $this->password)) {
            $score += 1;
            $checks['number'] = true;
        } else {
            $checks['number'] = false;
        }

        // special character check
        if (preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $this->password)) {
            $score += 1;
            $checks['special'] = true;
        } else {
            $checks['special'] = false;
        }

        // determine strength label and color
        $label = '';
        $color = 'gray';
        
        if ($score <= 1) {
            $label = 'Very Weak';
            $color = 'red';
        } elseif ($score == 2) {
            $label = 'Weak';
            $color = 'orange';
        } elseif ($score == 3) {
            $label = 'Fair';
            $color = 'yellow';
        } elseif ($score == 4) {
            $label = 'Good';
            $color = 'blue';
        } else {
            $label = 'Strong';
            $color = 'green';
        }

        return [
            'score' => $score,
            'label' => $label,
            'color' => $color,
            'checks' => $checks
        ];
    }

    // get password confirmation status
    public function getPasswordConfirmationStatus(): array
    {
        if (empty($this->password_confirmation)) {
            return ['status' => 'empty', 'message' => '', 'color' => 'gray'];
        }

        if ($this->password === $this->password_confirmation) {
            return ['status' => 'match', 'message' => 'Passwords match', 'color' => 'green'];
        } else {
            return ['status' => 'mismatch', 'message' => 'Passwords do not match', 'color' => 'red'];
        }
    }
} ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input
            wire:model="name"
            :label="__('Name')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Full name')"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Password -->
        <div class="space-y-2">
            <flux:input
                wire:model.live="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
            />
            
            <!-- Password Strength Indicator -->
            @if($password)
                @php $strength = $this->getPasswordStrength(); @endphp
                <div class="space-y-2">
                    <!-- Strength Bar -->
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="h-2 rounded-full transition-all duration-300 {{ 
                            $strength['color'] === 'red' ? 'bg-red-500' :
                            ($strength['color'] === 'orange' ? 'bg-orange-500' :
                            ($strength['color'] === 'yellow' ? 'bg-yellow-500' :
                            ($strength['color'] === 'blue' ? 'bg-blue-500' :
                            ($strength['color'] === 'green' ? 'bg-green-500' : 'bg-gray-500'))))
                        }}"
                            style="width: {{ ($strength['score'] / 5) * 100 }}%">
                        </div>
                    </div>
                    
                    <!-- Strength Label -->
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Password Strength:</span>
                        <span class="font-medium {{ 
                            $strength['color'] === 'red' ? 'text-red-600 dark:text-red-400' :
                            ($strength['color'] === 'orange' ? 'text-orange-600 dark:text-orange-400' :
                            ($strength['color'] === 'yellow' ? 'text-yellow-600 dark:text-yellow-400' :
                            ($strength['color'] === 'blue' ? 'text-blue-600 dark:text-blue-400' :
                            ($strength['color'] === 'green' ? 'text-green-600 dark:text-green-400' : 'text-gray-600 dark:text-gray-400'))))
                        }}">
                            {{ $strength['label'] }}
                        </span>
                    </div>
                    
                    <!-- Requirements List -->
                                            <div class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                            <div class="flex items-center gap-2">
                                <svg class="w-3 h-3 {{ $strength['checks']['length'] ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                    @if($strength['checks']['length'])
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    @else
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    @endif
                                </svg>
                                At least 8 characters long
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-3 h-3 {{ $strength['checks']['lowercase'] ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                    @if($strength['checks']['lowercase'])
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    @else
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    @endif
                                </svg>
                                At least one lowercase letter
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-3 h-3 {{ $strength['checks']['uppercase'] ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                    @if($strength['checks']['uppercase'])
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    @else
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    @endif
                                </svg>
                                At least one uppercase letter
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-3 h-3 {{ $strength['checks']['number'] ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                    @if($strength['checks']['number'])
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    @else
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    @endif
                                </svg>
                                At least one number
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-3 h-3 {{ $strength['checks']['special'] ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                    @if($strength['checks']['special'])
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    @else
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    @endif
                                </svg>
                                At least one special character (!@#$%^&*()_+-=[]{}|;:,.<>?)
                            </div>
                        </div>
                </div>
            @endif
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2">
            <flux:input
                wire:model.live="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                viewable
            />
            
            <!-- Password Confirmation Status -->
            @if($password_confirmation)
                @php $confirmationStatus = $this->getPasswordConfirmationStatus(); @endphp
                <div class="flex items-center gap-2">
                    @if($confirmationStatus['status'] === 'match')
                        <span class="text-sm text-green-600 dark:text-green-400">✓ Passwords match</span>
                    @else
                        <span class="text-sm text-red-600 dark:text-red-400">Passwords do not match</span>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">Create Account</flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>Already have an account?</span>
        <flux:link :href="route('login')" wire:navigate>Log in</flux:link>
    </div>
</div>
