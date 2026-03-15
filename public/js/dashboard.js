// Dashboard.js - User dashboard functionality

document.addEventListener('DOMContentLoaded', function() {
    loadDashboardStats();
    setupNavigation();
    setupLogout();
    loadUserGreeting();
});

async function loadDashboardStats() {
    try {
        const response = await fetch('/api/dashboard/stats', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            const stats = data.stats;
            document.getElementById('totalPeminjaman').textContent = stats.total_peminjaman || 0;
            document.getElementById('pendingPeminjaman').textContent = stats.peminjaman_pending || 0;
            document.getElementById('approvedPeminjaman').textContent = stats.peminjaman_approved || 0;
            document.getElementById('returnedPeminjaman').textContent = stats.peminjaman_returned || 0;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

function setupNavigation() {
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const section = this.getAttribute('data-section');
            switchSection(section);
        });
    });
}

function switchSection(section) {
    const sections = document.querySelectorAll('.section');
    sections.forEach(s => s.classList.remove('active'));
    
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => item.classList.remove('active'));
    
    const targetSection = document.getElementById(section + '-section');
    if (targetSection) {
        targetSection.classList.add('active');
    }
    
    event.target.classList.add('active');
}

function setupLogout() {
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', async function() {
            try {
                const response = await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    window.location.href = '/login';
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    }
}

async function loadUserGreeting() {
    try {
        const response = await fetch('/api/profile', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();
        if (data.success) {
            const userGreeting = document.getElementById('userGreeting');
            if (userGreeting) {
                userGreeting.textContent = 'Selamat datang, ' + data.user.username;
            }
        }
    } catch (error) {
        console.error('Error:', error);
    }
}
