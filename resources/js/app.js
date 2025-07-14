    // global function to apply appearance changes across the entire system
window.applyAppearance = function(appearance) {
    const html = document.documentElement;
    
    // remove existing appearance classes
    html.classList.remove('light', 'dark', 'system');
    
    // add the new appearance class
    html.classList.add(appearance);
    
    // if system preference, detect and apply the correct class
    if (appearance === 'system') {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        html.classList.remove('system');
        html.classList.add(prefersDark ? 'dark' : 'light');
    }
    
    // dispatch a custom event so other parts of the app can listen for appearance changes
    window.dispatchEvent(new CustomEvent('appearance-changed', { 
        detail: { appearance: appearance } 
    }));
};

// handle system appearance preference on page load
document.addEventListener('DOMContentLoaded', function() {
    const html = document.documentElement;
    
    // check if the current class is 'system'
    if (html.classList.contains('system')) {
        // detect system preference
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        // remove system class and add the appropriate class
        html.classList.remove('system');
        html.classList.add(prefersDark ? 'dark' : 'light');
    }
    
    // listen for system preference changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
        // only apply if the current appearance is set to 'system'
        if (html.classList.contains('system') || 
            (html.classList.contains('dark') && !e.matches) || 
            (html.classList.contains('light') && e.matches)) {
            
            // check for system mode
            const currentAppearance = html.classList.contains('dark') ? 'dark' : 
                                    html.classList.contains('light') ? 'light' : 'system';
            
            if (currentAppearance === 'system') {
                html.classList.remove('system');
                html.classList.add(e.matches ? 'dark' : 'light');
            }
        }
    });
});

// listen for appearance changes from other parts of the app
window.addEventListener('appearance-changed', function(event) {
    console.log('Appearance changed to:', event.detail.appearance);
});
