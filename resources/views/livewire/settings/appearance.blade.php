<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $appearance = 'system';
    public string $tempAppearance = 'system';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->appearance = Auth::user()->appearance ?? 'system';
        $this->tempAppearance = $this->appearance;
    }

    /**
     * Update the appearance setting for the currently authenticated user.
     */
    public function updateAppearance(): void
    {
        $user = Auth::user();
        $user->update(['appearance' => $this->tempAppearance]);
        
        // Update the current appearance to match the saved one
        $this->appearance = $this->tempAppearance;
        
        $this->dispatch('appearance-updated', appearance: $this->tempAppearance);
    }

    /**
     * Reset the temporary appearance to the saved value.
     */
    public function resetAppearance(): void
    {
        $this->tempAppearance = $this->appearance;
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
        <div class="space-y-4">
            <flux:radio.group wire:model.live="tempAppearance" variant="segmented">
                <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
                <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
                <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
            </flux:radio.group>
            
            <div class="flex items-center gap-4">
                <button wire:click="updateAppearance" 
                    :disabled="$tempAppearance === $appearance"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors duration-200">
                    Save Changes
                </button>
                
                @if($tempAppearance !== $appearance)
                    <button wire:click="resetAppearance" 
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-lg transition-colors duration-200">
                        Cancel
                    </button>
                @endif
                
                <x-action-message class="me-3" on="appearance-updated">
                    {{ __('Appearance updated successfully!') }}
                </x-action-message>
            </div>
            
            @if($tempAppearance !== $appearance)
                <div class="text-sm text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 p-3 rounded-lg">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        You have unsaved changes. Click "Save Changes" to apply the new appearance.
                    </div>
                </div>
            @endif
        </div>
    </x-settings.layout>
</section>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('appearance-updated', (event) => {
        // Use the global function to apply appearance changes across the entire system
        if (window.applyAppearance) {
            window.applyAppearance(event.appearance);
        } else {
            // Fallback if global function is not available
            const html = document.documentElement;
            const newAppearance = event.appearance;
            
            // Remove existing appearance classes
            html.classList.remove('light', 'dark', 'system');
            
            // Add the new appearance class
            html.classList.add(newAppearance);
            
            // If system preference, detect and apply the correct class
            if (newAppearance === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                html.classList.remove('system');
                html.classList.add(prefersDark ? 'dark' : 'light');
            }
        }
        
        // Show a success notification
        showNotification('Appearance updated successfully!', 'success');
    });
});

// Simple notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}
</script>
