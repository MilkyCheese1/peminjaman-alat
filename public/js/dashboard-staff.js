// Dashboard Staff JS
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardStats();
    loadPeminjamanManagement();
    loadStaffProfile();
    setupNavigation();
    setupLogout();
    loadUserGreeting();
});

async function loadDashboardStats() {
    try {
        const response = await fetch('/api/dashboard/stats', {
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        const data = await response.json();

        if (data.success) {
            const stats = data.stats;
            document.getElementById('totalPeminjaman').textContent = stats.total_peminjaman || 0;
            document.getElementById('pendingPeminjaman').textContent = stats.peminjaman_pending || 0;
            document.getElementById('approvedPeminjaman').textContent = stats.peminjaman_approved || 0;
            document.getElementById('availableAlat').textContent = stats.alat_tersedia || 0;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

async function loadPeminjamanManagement() {
    try {
        const response = await fetch('/api/peminjaman', {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success && data.data) {
            const tbody = document.getElementById('peminjamanBody');
            tbody.innerHTML = '';
            
            if (data.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 20px;">Tidak ada data peminjaman</td></tr>';
                return;
            }
            
            data.data.forEach(peminjaman => {
                const row = document.createElement('tr');
                row.style.borderBottom = '1px solid #ddd';
                let statusColor = 'gray';
                if (peminjaman.status === 'pending') statusColor = 'orange';
                if (peminjaman.status === 'disetujui') statusColor = 'green';
                if (peminjaman.status === 'dikembalikan') statusColor = 'blue';
                
                const username = peminjaman.user ? peminjaman.user.username : 'Unknown';
                const namaAlat = peminjaman.alat ? peminjaman.alat.nama_alat : 'Unknown';
                const peminjamanId = peminjaman.id_peminjaman || peminjaman.id;
                
                row.innerHTML = `
                    <td style="padding: 10px;">${peminjamanId}</td>
                    <td style="padding: 10px;">${username}</td>
                    <td style="padding: 10px;">${namaAlat}</td>
                    <td style="padding: 10px;">${new Date(peminjaman.tgl_peminjaman).toLocaleDateString('id-ID')}</td>
                    <td style="padding: 10px;">${new Date(peminjaman.tgl_kembali).toLocaleDateString('id-ID')}</td>
                    <td style="padding: 10px; color: ${statusColor}; font-weight: bold;">${peminjaman.status}</td>
                    <td style="padding: 10px;">
                        ${peminjaman.status === 'pending' ? `<button onclick="updatePeminjamanStatus(${peminjamanId}, 'disetujui')" style="padding: 5px 10px; background: green; color: white; border: none; border-radius: 4px; cursor: pointer;">Approve</button>` : ''}
                        ${peminjaman.status === 'disetujui' ? `<button onclick="updatePeminjamanStatus(${peminjamanId}, 'dikembalikan')" style="padding: 5px 10px; background: blue; color: white; border: none; border-radius: 4px; cursor: pointer;">Kembalikan</button>` : ''}
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            console.log('✓ Peminjaman loaded (' + data.data.length + ' items)');
        }
    } catch (error) {
        console.error('Error loading peminjaman:', error);
    }
}

async function loadStaffProfile() {
    try {
        const response = await fetch('/api/profile', {
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        if (data.success) {
            const profileContent = document.getElementById('profileContent');
            if (profileContent) {
                const user = data.user;
                profileContent.innerHTML = `
                    <p><strong>Username:</strong> ${user.username}</p>
                    <p><strong>Email:</strong> ${user.email}</p>
                    <p><strong>Telepon:</strong> ${user.phone}</p>
                    <p><strong>Alamat:</strong> ${user.alamat}</p>
                    <p><strong>Role:</strong> <span style="background: #e0e0e0; padding: 4px 8px; border-radius: 4px;">${user.role}</span></p>
                    <p><strong>Bergabung:</strong> ${new Date(user.created_at).toLocaleDateString('id-ID')}</p>
                `;
            }
        }
    } catch (error) {
        console.error('Error loading profile:', error);
    }
}

async function updatePeminjamanStatus(peminjamanId, newStatus) {
    try {
        const response = await fetch(`/api/peminjaman/${peminjamanId}`, {
            method: 'PUT',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        });

        const data = await response.json();
        
        if (data.success) {
            alert('Status peminjaman berhasil diubah');
            loadPeminjamanManagement();
        } else {
            alert('Gagal mengubah status peminjaman: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
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
            await fetch('/api/logout', { 
                method: 'POST', 
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            window.location.href = '/login';
        });
    }
}

async function loadUserGreeting() {
    try {
        const response = await fetch('/api/profile', {
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
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
