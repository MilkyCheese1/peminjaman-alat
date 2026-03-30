// ========================================
// SPLASH SCREEN MANAGER
// ========================================

(function() {
    'use strict';

    // Configuration
    const SPLASH_DURATION = 2000; // 2 seconds
    const SPLASH_STORAGE_KEY = 'splashScreenShown';
    const SPLASH_SESSION_KEY = 'splashScreenCurrentSession';

    /**
     * Initialize splash screen on page load
     */
    window.addEventListener('DOMContentLoaded', function() {
        initSplashScreen();
        initLoadingScreen();
    });

    /**
     * Splash Screen Logic
     */
    function initSplashScreen() {
        const splashScreen = document.getElementById('splashScreen');
        if (!splashScreen) return;

        // Check if splash was already shown in this session
        const shownInSession = sessionStorage.getItem(SPLASH_SESSION_KEY);
        
        if (shownInSession === 'true') {
            // Already shown in this session, hide immediately
            splashScreen.classList.add('hidden');
            return;
        }

        // Show splash screen
        splashScreen.classList.remove('hidden');
        splashScreen.style.display = 'flex';

        // After duration, fade out
        setTimeout(function() {
            splashScreen.classList.add('fade-out');
            
            // After fade animation, hide completely
            setTimeout(function() {
                splashScreen.classList.add('hidden');
                // Mark as shown in this session
                sessionStorage.setItem(SPLASH_SESSION_KEY, 'true');
            }, 500); // Match fade-out transition duration
        }, SPLASH_DURATION);
    }

    // ========================================
    // LOADING SCREEN MANAGER
    // ========================================

    function initLoadingScreen() {
        setupNavigationLoading();
        setupAjaxLoading();
    }

    /**
     * Show loading screen for page navigation
     */
    function setupNavigationLoading() {
        // Show loading screen when clicking links that navigate away
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            
            if (!link) return;

            // Don't show for hash links, external links, or special links
            const href = link.getAttribute('href');
            if (!href || href.startsWith('#') || link.getAttribute('target') === '_blank') {
                return;
            }

            // Don't show for same-page links or non-http links
            if (href.startsWith('javascript:') || href.startsWith('mailto:')) {
                return;
            }

            // Show loading screen
            showLoadingScreen();
        });

        // Also show loading screen on form submit
        document.addEventListener('submit', function(e) {
            const form = e.target;
            // Only show for forms that navigate (not AJAX forms)
            if (!form.getAttribute('data-no-loading')) {
                showLoadingScreen();
            }
        });

        // Hide loading screen when page finishes loading
        window.addEventListener('load', function() {
            hideLoadingScreen();
        });

        // Also hide if user goes back (popstate)
        window.addEventListener('popstate', function() {
            hideLoadingScreen();
        });
    }

    /**
     * Show loading screen for AJAX calls
     */
    function setupAjaxLoading() {
        // Intercept fetch calls
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            // Only show loading for certain API calls (optional)
            const url = args[0];
            const options = args[1] || {};

            // Don't show for fast calls
            const showLoadingTimer = setTimeout(() => {
                showLoadingScreen();
            }, 500); // Show after 500ms

            return originalFetch.apply(this, args)
                .then(response => {
                    clearTimeout(showLoadingTimer);
                    hideLoadingScreen();
                    return response;
                })
                .catch(error => {
                    clearTimeout(showLoadingTimer);
                    hideLoadingScreen();
                    throw error;
                });
        };
    }

    /**
     * Show the loading screen
     */
    function showLoadingScreen() {
        const loadingScreen = document.getElementById('loadingScreen');
        if (loadingScreen && !loadingScreen.classList.contains('show')) {
            loadingScreen.classList.add('show');
        }

        const loadingBar = document.getElementById('loadingBar');
        if (loadingBar) {
            loadingBar.classList.add('show');
        }
    }

    /**
     * Hide the loading screen
     */
    function hideLoadingScreen() {
        const loadingScreen = document.getElementById('loadingScreen');
        if (loadingScreen) {
            loadingScreen.classList.remove('show');
        }

        const loadingBar = document.getElementById('loadingBar');
        if (loadingBar) {
            setTimeout(() => {
                loadingBar.classList.remove('show');
            }, 300);
        }
    }

    /**
     * Expose functions globally
     */
    window.screenLoading = {
        show: showLoadingScreen,
        hide: hideLoadingScreen
    };

})();
