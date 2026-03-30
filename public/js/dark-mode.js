// Dark Mode Toggle Script

(function() {
    // Initialize dark mode on page load
    window.addEventListener('DOMContentLoaded', function() {
        initializeDarkMode();
        setupDarkModeToggle();
    });

    function initializeDarkMode() {
        // Get saved preference from localStorage or check system preference
        const savedMode = localStorage.getItem('darkMode');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        if (savedMode === 'enabled' || (savedMode === null && prefersDark)) {
            enableDarkMode();
        } else if (savedMode === 'disabled') {
            disableDarkMode();
        }
    }

    function setupDarkModeToggle() {
        // Find or create toggle button
        let toggleBtn = document.getElementById('darkModeToggle');
        
        if (!toggleBtn) {
            // If toggle doesn't exist, create it
            const navbar = document.querySelector('.navbar-menu');
            if (navbar) {
                toggleBtn = document.createElement('button');
                toggleBtn.id = 'darkModeToggle';
                toggleBtn.className = 'dark-mode-toggle';
                toggleBtn.setAttribute('title', 'Toggle Dark Mode');
                toggleBtn.innerHTML = '🌙';
                navbar.insertBefore(toggleBtn, navbar.firstChild);
            }
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleDarkMode);
            updateToggleIcon(toggleBtn);
        }
    }

    function toggleDarkMode() {
        const html = document.documentElement;
        if (html.classList.contains('dark-mode')) {
            disableDarkMode();
        } else {
            enableDarkMode();
        }
    }

    function enableDarkMode() {
        document.documentElement.classList.add('dark-mode');
        localStorage.setItem('darkMode', 'enabled');
        updateToggleIcon(document.getElementById('darkModeToggle'));
        
        // Trigger custom event for other scripts
        window.dispatchEvent(new CustomEvent('darkModeEnabled'));
    }

    function disableDarkMode() {
        document.documentElement.classList.remove('dark-mode');
        localStorage.setItem('darkMode', 'disabled');
        updateToggleIcon(document.getElementById('darkModeToggle'));
        
        // Trigger custom event for other scripts
        window.dispatchEvent(new CustomEvent('darkModeDisabled'));
    }

    function updateToggleIcon(btn) {
        if (btn) {
            const isDark = document.documentElement.classList.contains('dark-mode');
            btn.innerHTML = isDark ? '☀️' : '🌙';
            btn.setAttribute('title', isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode');
        }
    }

    // Listen for system preference changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        const savedMode = localStorage.getItem('darkMode');
        if (savedMode === null) {
            if (e.matches) {
                enableDarkMode();
            } else {
                disableDarkMode();
            }
        }
    });

    // Expose functions globally for accessibility
    window.darkMode = {
        toggle: toggleDarkMode,
        enable: enableDarkMode,
        disable: disableDarkMode,
        isEnabled: () => document.documentElement.classList.contains('dark-mode')
    };
})();
