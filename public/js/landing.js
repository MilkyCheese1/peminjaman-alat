// ===================================
// LANDING PAGE JAVASCRIPT
// ===================================

document.addEventListener('DOMContentLoaded', function () {
    initMobileMenu();
    initSmoothScroll();
    initNavbarEffect();
    observeElements();
    initCarousel();
    initParallaxScrolling();
});

/**
 * Initialize Mobile Menu (Burger Menu)
 */
function initMobileMenu() {
    const burgerMenu = document.getElementById('burgerMenu');
    const navbarMenu = document.getElementById('navbarMenu');
    const navLinks = document.querySelectorAll('.navbar-menu a');

    if (!burgerMenu || !navbarMenu) return;

    // Toggle menu on burger click
    burgerMenu.addEventListener('click', function (e) {
        e.stopPropagation();
        burgerMenu.classList.toggle('active');
        navbarMenu.classList.toggle('active');
    });

    // Close menu when link clicked
    navLinks.forEach(link => {
        link.addEventListener('click', function () {
            burgerMenu.classList.remove('active');
            navbarMenu.classList.remove('active');
        });
    });

    // Close menu when clicking outside
    document.addEventListener('click', function (event) {
        const isClickInsideNav = burgerMenu.contains(event.target) || navbarMenu.contains(event.target);
        if (!isClickInsideNav && navbarMenu.classList.contains('active')) {
            burgerMenu.classList.remove('active');
            navbarMenu.classList.remove('active');
        }
    });
}

/**
 * Smooth Scroll for anchor links
 */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            
            // Skip if href is just '#'
            if (href === '#') return;
            
            const target = document.querySelector(href);
            if (!target) return;

            e.preventDefault();

            const navbar = document.querySelector('.navbar');
            const navbarHeight = navbar ? navbar.offsetHeight : 0;
            const targetTop = target.offsetTop - navbarHeight;

            // Close mobile menu if open
            const burgerMenu = document.getElementById('burgerMenu');
            const navbarMenu = document.getElementById('navbarMenu');
            if (burgerMenu && navbarMenu) {
                burgerMenu.classList.remove('active');
                navbarMenu.classList.remove('active');
            }

            window.scrollTo({
                top: targetTop,
                behavior: 'smooth'
            });
        });
    });
}

/**
 * Navbar Effect on Scroll
 */
function initNavbarEffect() {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;

    window.addEventListener('scroll', function () {
        if (window.scrollY > 50) {
            navbar.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.12)';
        } else {
            navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.08)';
        }
    });
}

/**
 * Initialize Testimonials Carousel
 */
function initCarousel() {
    const indicators = document.querySelectorAll('.indicator');
    const slides = document.querySelectorAll('.carousel-slide');
    
    if (indicators.length === 0 || slides.length === 0) return;
    
    let currentSlide = 0;
    
    // Click handler for indicators
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', function () {
            showSlide(index);
            currentSlide = index;
        });
    });
    
    function showSlide(index) {
        // Remove active class from all slides and indicators
        slides.forEach(slide => slide.classList.remove('active'));
        indicators.forEach(indicator => indicator.classList.remove('active'));
        
        // Add active class to current slide and indicator
        if (slides[index]) {
            slides[index].classList.add('active');
        }
        if (indicators[index]) {
            indicators[index].classList.add('active');
        }
    }
    
    // Optional: Auto-rotate carousel every 5 seconds
    setInterval(() => {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }, 5000);
}

/**
 * Initialize Parallax Scrolling Effect
 */
function initParallaxScrolling() {
    let rafId;
    const parallaxElements = {
        hero: document.querySelector('.hero'),
        whySection: document.querySelector('.why-section'),
        howSection: document.querySelector('.how-section'),
        testimonialsSection: document.querySelector('.testimonials-section')
    };

    // Check if device supports parallax (not mobile with small screen)
    const supportsParallax = window.innerWidth > 768 && 'requestAnimationFrame' in window;

    if (!supportsParallax) return;

    window.addEventListener('scroll', function () {
        if (rafId) cancelAnimationFrame(rafId);
        
        rafId = requestAnimationFrame(function () {
            const scrollY = window.scrollY;

            // Hero parallax with subtle movement
            if (parallaxElements.hero) {
                const heroRect = parallaxElements.hero.getBoundingClientRect();
                if (heroRect.top < window.innerHeight && heroRect.bottom > 0) {
                    const yOffset = scrollY * 0.5;
                    parallaxElements.hero.style.backgroundPosition = `center ${yOffset * 0.3}px`;
                }
            }

            // Fade in parallax elements as they come into view
            const fadeElements = document.querySelectorAll('.why-card, .how-step, .testimonial-card');
            fadeElements.forEach(element => {
                const rect = element.getBoundingClientRect();
                const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
                
                if (isVisible) {
                    const distance = window.innerHeight - rect.top;
                    const opacity = Math.min(distance / 300, 1);
                    const translateY = Math.max(0, (window.innerHeight - rect.top) * -0.02);
                    
                    element.style.opacity = opacity;
                    element.style.transform = `translateY(${translateY}px)`;
                }
            });
        });
    }, { passive: true });

    // Initial setup for parallax elements
    const hero = parallaxElements.hero;
    if (hero) {
        hero.style.backgroundAttachment = 'fixed';
        hero.style.backgroundSize = 'cover';
        hero.style.backgroundPosition = 'center';
    }
}

/**
 * Observe Elements for Entrance Animation
 */
function observeElements() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe card elements
    const elements = document.querySelectorAll('.why-card, .testimonial-card, .how-step');
    elements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'all 0.6s ease';
        observer.observe(element);
    });
}

/**
 * Load data from API (if needed later)
 */
async function loadDataFromAPI(endpoint) {
    try {
        const response = await fetch(endpoint, {
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) throw new Error(`Failed to load ${endpoint}`);
        
        return await response.json();
    } catch (error) {
        console.error(`Error loading from ${endpoint}:`, error);
        return null;
    }
}

console.log('✓ Landing page initialized');
function animateCounter(element, target, duration = 2000) {
    let current = 0;
    const step = target / (duration / 16);
    
    const timer = setInterval(() => {
        current += step;
        if (current >= target) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current);
        }
    }, 16);
}

/**
 * Form submission handler untuk CTA
 */
document.addEventListener('DOMContentLoaded', function () {
    // Event listeners untuk buttons
    const registerButtons = document.querySelectorAll('a[href="/register"]');
    const loginButtons = document.querySelectorAll('a[href="/login"]');

    registerButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            console.log('Navigating to register page');
        });
    });

    loginButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            console.log('Navigating to login page');
        });
    });

    // Add animation to hero on page load
    const heroContent = document.querySelector('.hero-content');
    if (heroContent) {
        heroContent.style.opacity = '0';
        heroContent.style.transform = 'translateX(-30px)';
        heroContent.style.transition = 'all 0.8s ease';
        
        setTimeout(() => {
            heroContent.style.opacity = '1';
            heroContent.style.transform = 'translateX(0)';
        }, 100);
    }

    const heroImage = document.querySelector('.hero-image');
    if (heroImage) {
        heroImage.style.opacity = '0';
        heroImage.style.transform = 'translateX(30px)';
        heroImage.style.transition = 'all 0.8s ease';
        
        setTimeout(() => {
            heroImage.style.opacity = '1';
            heroImage.style.transform = 'translateX(0)';
        }, 200);
    }
});

/**
 * Mobile menu toggle (jika ada hamburger menu di masa depan)
 */
function toggleMobileMenu() {
    const navbarMenu = document.querySelector('.navbar-menu');
    if (navbarMenu) {
        navbarMenu.classList.toggle('active');
    }
}

// No carousel needed - kategori shows 3 cards per row

console.log('Landing page loaded successfully');
