// JavaScript untuk Landing Page

document.addEventListener('DOMContentLoaded', function () {
    initBurgerMenu();
    loadCategoriesFromDatabase();
    loadEquipmentFromDatabase();
    smoothScrolling();
});

/**
 * Toggle Burger Menu
 */
function initBurgerMenu() {
    const burgerMenu = document.getElementById('burgerMenu');
    const navbarMenu = document.getElementById('navbarMenu');
    const navLinks = document.querySelectorAll('.navbar-menu a');

    if (burgerMenu) {
        burgerMenu.addEventListener('click', function () {
            burgerMenu.classList.toggle('active');
            navbarMenu.classList.toggle('active');
        });
    }

    navLinks.forEach(link => {
        link.addEventListener('click', function () {
            if (burgerMenu) {
                burgerMenu.classList.remove('active');
            }
            if (navbarMenu) {
                navbarMenu.classList.remove('active');
            }
        });
    });

    document.addEventListener('click', function (event) {
        if (burgerMenu && navbarMenu) {
            const isClickInsideNav = burgerMenu.contains(event.target) || navbarMenu.contains(event.target);
            if (!isClickInsideNav && navbarMenu.classList.contains('active')) {
                burgerMenu.classList.remove('active');
                navbarMenu.classList.remove('active');
            }
        }
    });
}

/**
 * Load categories from database API
 */
async function loadCategoriesFromDatabase() {
    try {
        const response = await fetch('/api/kategoris', {
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) throw new Error('Failed to load categories');

        const data = await response.json();
        
        if (data.success && data.data) {
            const kategoriGrid = document.getElementById('kategoriGrid');
            kategoriGrid.innerHTML = '';

            const icons = ['🔨', '🪚', '⚡', '🛠️', '🦺'];
            
            data.data.forEach((kategori, index) => {
                const card = document.createElement('div');
                card.className = 'kategori-card';
                card.innerHTML = `
                    <div class="kategori-image">${icons[index % icons.length]}</div>
                    <h3>${kategori.nama_kategori}</h3>
                    <p>Peralatan berkualitas dari kategori ini tersedia untuk disewa</p>
                `;
                kategoriGrid.appendChild(card);
            });

            console.log('✓ Categories loaded from database');
        }
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

/**
 * Load equipment from database API
 */
async function loadEquipmentFromDatabase() {
    try {
        const response = await fetch('/api/alat', {
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) throw new Error('Failed to load equipment');

        const data = await response.json();
        
        if (data.success && data.data) {
            const alatGrid = document.getElementById('alatGrid');
            alatGrid.innerHTML = '';

            data.data.forEach((alat) => {
                const available = alat.stok - alat.dipinjam;
                const availability = available > 0 ? `<span style="color: green;">✓ Tersedia (${available})</span>` : '<span style="color: red;">✗ Tidak Tersedia</span>';
                
                const card = document.createElement('div');
                card.style.cssText = 'border: 1px solid #ddd; border-radius: 8px; padding: 20px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);';
                card.innerHTML = `
                    <h3 style="margin-top: 0;">${alat.nama_alat}</h3>
                    <p><strong>Jumlah Stock:</strong> ${alat.stok} unit</p>
                    <p><strong>Tersedia:</strong> ${available} unit</p>
                    <p><strong>Status:</strong> ${availability}</p>
                    <p style="font-size: 12px; color: #666; margin-bottom: 0;">Kategori ID: ${alat.id_kategori}</p>
                `;
                alatGrid.appendChild(card);
            });

            console.log('✓ Equipment loaded from database (' + data.data.length + ' items)');
        }
    } catch (error) {
        console.error('Error loading equipment:', error);
    }
}

/**
 * Smooth scrolling untuk navbar links
 */
document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && document.querySelector(href)) {
            e.preventDefault();
            const target = document.querySelector(href);
            const headerHeight = document.querySelector('.navbar').offsetHeight;
            const targetPosition = target.offsetTop - headerHeight;

            // Close burger menu if open
            const burgerMenu = document.getElementById('burgerMenu');
            const navbarMenu = document.getElementById('navbarMenu');
            if (burgerMenu && navbarMenu) {
                burgerMenu.classList.remove('active');
                navbarMenu.classList.remove('active');
            }

            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        }
    });
});

/**
 * Navbar sticky effect pada scroll
 */
window.addEventListener('scroll', function () {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.15)';
    } else {
        navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
    }
});

/**
 * Intersection Observer untuk animasi saat scroll
 */
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function (entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe fitur cards
document.querySelectorAll('.fitur-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'all 0.6s ease';
    observer.observe(card);
});

/**
 * Counter animation untuk statistik (jika ditambahkan di masa depan)
 */
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
