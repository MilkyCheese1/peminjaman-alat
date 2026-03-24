// Dashboard.js - User dashboard functionality

document.addEventListener('DOMContentLoaded', function() {
    loadDashboardStats();
    loadEquipmentList();
    loadMyBorrowings();
    loadBorrowHistory();
    setupNavigation();
    setupLogout();
    loadUserGreeting();
    loadUserProfile();
});

async function loadDashboardStats() {
    try {
        const response = await fetch('/api/dashboard/stats', {
            method: 'GET',
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
            document.getElementById('returnedPeminjaman').textContent = stats.peminjaman_returned || 0;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

async function loadEquipmentList() {
    try {
        const response = await fetch('/api/alat', {
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success && data.data) {
            const alatList = document.getElementById('alatList');
            alatList.innerHTML = '';

            data.data.forEach(alat => {
                const available = alat.stok - alat.dipinjam;
                const card = document.createElement('div');
                card.style.cssText = 'border: 1px solid #ddd; padding: 15px; border-radius: 8px; background: white;';
                card.innerHTML = `
                    <h3 style="margin-top: 0;">${alat.nama_alat}</h3>
                    <p><strong>Stock Total:</strong> ${alat.stok} unit</p>
                    <p><strong>Tersedia:</strong> <span style="color: ${available > 0 ? 'green' : 'red'};">${available} unit</span></p>
                    <p><strong>Dipinjam:</strong> ${alat.dipinjam} unit</p>
                    <button class="btn" style="width: 100%; margin-top: 10px; ${available <= 0 ? 'opacity: 0.5; cursor: not-allowed;' : ''}" ${available <= 0 ? 'disabled' : ''} onclick="showBorrowModal(${alat.id_alat}, '${alat.nama_alat}')">Pinjam Sekarang</button>
                `;
                alatList.appendChild(card);
            });

            console.log('✓ Equipment list loaded from database (' + data.data.length + ' items)');
        }
    } catch (error) {
        console.error('Error loading equipment:', error);
    }
}

async function loadMyBorrowings() {
    try {
        const response = await fetch('/api/my-borrowings', {
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
                tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 20px;">Tidak ada peminjaman</td></tr>';
                return;
            }
            
            data.data.forEach(peminjaman => {
                const row = document.createElement('tr');
                row.style.borderBottom = '1px solid #ddd';
                let statusColor = 'gray';
                if (peminjaman.status === 'pending') statusColor = 'orange';
                if (peminjaman.status === 'disetujui') statusColor = 'green';
                if (peminjaman.status === 'dikembalikan') statusColor = 'blue';
                
                const namaAlat = peminjaman.alat ? peminjaman.alat.nama_alat : 'Unknown';
                
                row.innerHTML = `
                    <td style="padding: 10px;">${namaAlat}</td>
                    <td style="padding: 10px;">${new Date(peminjaman.tgl_peminjaman).toLocaleDateString('id-ID')}</td>
                    <td style="padding: 10px;">${new Date(peminjaman.tgl_kembali).toLocaleDateString('id-ID')}</td>
                    <td style="padding: 10px; color: ${statusColor}; font-weight: bold;">${peminjaman.status}</td>
                `;
                tbody.appendChild(row);
            });
            
            console.log('✓ My borrowings loaded (' + data.data.length + ' items)');
        }
    } catch (error) {
        console.error('Error loading my borrowings:', error);
    }
}

async function loadBorrowHistory() {
    try {
        const response = await fetch('/api/borrow-history', {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success && data.data) {
            const tbody = document.getElementById('historyBody');
            tbody.innerHTML = '';
            
            if (data.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 20px;">Tidak ada riwayat peminjaman</td></tr>';
                return;
            }
            
            data.data.forEach(peminjaman => {
                const row = document.createElement('tr');
                row.style.borderBottom = '1px solid #ddd';
                const namaAlat = peminjaman.alat ? peminjaman.alat.nama_alat : 'Unknown';
                
                row.innerHTML = `
                    <td style="padding: 10px;">${namaAlat}</td>
                    <td style="padding: 10px;">${new Date(peminjaman.tgl_peminjaman).toLocaleDateString('id-ID')}</td>
                    <td style="padding: 10px;">${new Date(peminjaman.tgl_kembali).toLocaleDateString('id-ID')}</td>
                    <td style="padding: 10px; color: blue; font-weight: bold;">${peminjaman.status}</td>
                `;
                tbody.appendChild(row);
            });
            
            console.log('✓ Borrow history loaded (' + data.data.length + ' items)');
        }
    } catch (error) {
        console.error('Error loading borrow history:', error);
    }
}

function showBorrowModal(alatId, namaAlat) {
    const tglPinjam = new Date().toISOString().split('T')[0];
    const tglKembali = new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
    
    const tanggalPinjam = prompt(`Tanggal Peminjaman ${namaAlat}:\n(format: YYYY-MM-DD)\n\nDefault: ${tglPinjam}`, tglPinjam);
    if (!tanggalPinjam) return;
    
    const tanggalKembali = prompt(`Tanggal Pengembalian ${namaAlat}:\n(format: YYYY-MM-DD)\n\nDefault: ${tglKembali}`, tglKembali);
    if (!tanggalKembali) return;
    
    borrowEquipment(alatId, tanggalPinjam, tanggalKembali);
}

async function borrowEquipment(alatId, tglPinjam, tglKembali) {
    try {
        const response = await fetch('/api/peminjaman', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                id_alat: alatId,
                tgl_peminjaman: tglPinjam,
                tgl_kembali: tglKembali
            })
        });

        const data = await response.json();
        
        if (data.success) {
            alert('Permintaan peminjaman berhasil dibuat! Status akan ditinjau oleh admin.');
            loadMyBorrowings();
        } else {
            alert('Gagal membuat permintaan peminjaman: ' + (data.message || 'Unknown error'));
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
            try {
                const response = await fetch('/api/logout', {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
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

async function loadUserProfile() {
    try {
        const response = await fetch('/api/profile', {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        if (data.success) {
            const user = data.user;
            const profileContent = document.getElementById('profileContent');
            if (profileContent) {
                profileContent.innerHTML = `
                    <p><strong>Username:</strong> ${user.username}</p>
                    <p><strong>Email:</strong> ${user.email}</p>
                    <p><strong>Telepon:</strong> ${user.phone}</p>
                    <p><strong>Alamat:</strong> ${user.alamat}</p>
                    <p><strong>Role:</strong> ${user.role}</p>
                    <p><strong>Email Verified:</strong> ${user.email_verified ? 'Ya' : 'Belum'}</p>
                `;
            }
        }
    } catch (error) {
        console.error('Error loading profile:', error);
    }
}
