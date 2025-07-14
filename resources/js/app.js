// Global function to apply appearance changes across the entire system
window.applyAppearance = function(appearance) {
    const html = document.documentElement;
    
    // Remove existing appearance classes
    html.classList.remove('light', 'dark', 'system');
    
    // Add the new appearance class
    html.classList.add(appearance);
    
    // If system preference, detect and apply the correct class
    if (appearance === 'system') {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        html.classList.remove('system');
        html.classList.add(prefersDark ? 'dark' : 'light');
    }
    
    // Dispatch a custom event so other parts of the app can listen for appearance changes
    window.dispatchEvent(new CustomEvent('appearance-changed', { 
        detail: { appearance: appearance } 
    }));
};

// Handle system appearance preference on page load
document.addEventListener('DOMContentLoaded', function() {
    const html = document.documentElement;
    
    // Check if the current class is 'system'
    if (html.classList.contains('system')) {
        // Detect system preference
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        // Remove system class and add the appropriate class
        html.classList.remove('system');
        html.classList.add(prefersDark ? 'dark' : 'light');
    }
    
    // Listen for system preference changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
        // Only apply if the current appearance is set to 'system'
        if (html.classList.contains('system') || 
            (html.classList.contains('dark') && !e.matches) || 
            (html.classList.contains('light') && e.matches)) {
            
            // Check if we should be in system mode
            const currentAppearance = html.classList.contains('dark') ? 'dark' : 
                                    html.classList.contains('light') ? 'light' : 'system';
            
            if (currentAppearance === 'system') {
                html.classList.remove('system');
                html.classList.add(e.matches ? 'dark' : 'light');
            }
        }
    });
});

// Listen for appearance changes from other parts of the app
window.addEventListener('appearance-changed', function(event) {
    console.log('Appearance changed to:', event.detail.appearance);
});
