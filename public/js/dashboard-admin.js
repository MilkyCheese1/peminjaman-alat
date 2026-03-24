// Dashboard Admin JS

document.addEventListener('DOMContentLoaded', function() {
    loadDashboardStats();
    loadUsersManagement();
    loadAlatManagement();
    loadPeminjamanManagement();
    loadAdminProfile();
    setupNavigation();
    setupLogout();
    loadUserGreeting();
    setupAlatCRUD();
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
            document.getElementById('totalUsers').textContent = stats.total_users || 0;
            document.getElementById('totalAlat').textContent = stats.total_alat || 0;
            document.getElementById('totalPeminjaman').textContent = stats.total_peminjaman || 0;
            document.getElementById('pendingPeminjaman').textContent = stats.peminjaman_pending || 0;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

async function loadUsersManagement() {
    try {
        const response = await fetch('/api/users', {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success && data.data) {
            const tbody = document.getElementById('usersBody');
            tbody.innerHTML = '';
            
            data.data.forEach(user => {
                const row = document.createElement('tr');
                row.style.borderBottom = '1px solid #ddd';
                const statusColor = user.is_active ? 'green' : 'red';
                const statusText = user.is_active ? 'Aktif' : 'Tidak Aktif';
                
                row.innerHTML = `
                    <td style="padding: 10px;">${user.id_user}</td>
                    <td style="padding: 10px;">${user.username}</td>
                    <td style="padding: 10px;">${user.email}</td>
                    <td style="padding: 10px;"><span style="background: #e0e0e0; padding: 4px 8px; border-radius: 4px;">${user.role}</span></td>
                    <td style="padding: 10px; color: ${statusColor}; font-weight: bold;">${statusText}</td>
                `;
                tbody.appendChild(row);
            });
            
            console.log('✓ Users loaded (' + data.data.length + ' users)');
        }
    } catch (error) {
        console.error('Error loading users:', error);
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
                await fetch('/api/logout', {
                    method: 'POST',
                    credentials: 'include',
                    headers: { 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
                window.location.href = '/login';
            } catch (error) {
                console.error('Error:', error);
            }
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

async function loadAlatManagement() {
    try {
        const response = await fetch('/api/alat', {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success && data.data) {
            const tbody = document.getElementById('alatBody');
            tbody.innerHTML = '';
            
            data.data.forEach(alat => {
                const tersedia = alat.stok - alat.dipinjam;
                const kategoriNama = alat.kategori ? alat.kategori.nama_kategori : '-';
                const row = document.createElement('tr');
                row.style.borderBottom = '1px solid #ddd';
                row.innerHTML = `
                    <td style="padding: 10px;">${alat.id_alat}</td>
                    <td style="padding: 10px;">${alat.nama_alat}</td>
                    <td style="padding: 10px;">${kategoriNama}</td>
                    <td style="padding: 10px;">${alat.stok}</td>
                    <td style="padding: 10px;">${alat.dipinjam}</td>
                    <td style="padding: 10px; color: ${tersedia > 0 ? 'green' : 'red'}; font-weight: bold;">${tersedia}</td>
                    <td style="padding: 10px; text-align: center;">
                        <button onclick="editAlat(${alat.id_alat})" style="padding: 5px 10px; background: #ffc107; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">Edit</button>
                        <button onclick="deleteAlat(${alat.id_alat})" style="padding: 5px 10px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Hapus</button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }
    } catch (error) {
        console.error('Error loading alat:', error);
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
                tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data peminjaman</td></tr>';
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
                    <td style="padding: 10px; color: ${statusColor}; font-weight: bold;">${peminjaman.status}</td>
                    <td style="padding: 10px;">
                        ${peminjaman.status === 'pending' ? `<button onclick="updatePeminjamanStatus(${peminjamanId}, 'disetujui')" style="padding: 5px 10px; background: green; color: white; border: none; border-radius: 4px; cursor: pointer;">Approve</button>` : ''}
                        ${peminjaman.status === 'disetujui' ? `<button onclick="updatePeminjamanStatus(${peminjamanId}, 'dikembalikan')" style="padding: 5px 10px; background: blue; color: white; border: none; border-radius: 4px; cursor: pointer;">Kembalikan</button>` : ''}
                    </td>
                `;
                tbody.appendChild(row);
            });
        }
    } catch (error) {
        console.error('Error loading peminjaman:', error);
    }
}

async function loadAdminProfile() {
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
                profileContent.innerHTML = `
                    <p><strong>Username:</strong> ${data.user.username}</p>
                    <p><strong>Email:</strong> ${data.user.email}</p>
                    <p><strong>Role:</strong> ${data.user.role}</p>
                    <p><strong>Joined:</strong> ${new Date(data.user.created_at).toLocaleDateString('id-ID')}</p>
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

// ===== CRUD Functions for Alat Management =====

function setupAlatCRUD() {
    // Button untuk tambah alat baru
    document.getElementById('addAlatBtn').addEventListener('click', openAlatModal);
    
    // Button untuk close modal
    document.getElementById('closeModalBtn').addEventListener('click', closeAlatModal);
    
    // Form submit
    document.getElementById('alatForm').addEventListener('submit', saveAlat);
    
    // Close modal saat klik di luar modal
    document.getElementById('alatModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAlatModal();
        }
    });
}

async function loadKategori() {
    try {
        const response = await fetch('/api/kategoris', {
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success && data.data) {
            const select = document.getElementById('kategoriSelect');
            select.innerHTML = '<option value="">-- Pilih Kategori --</option>';
            
            data.data.forEach(kategori => {
                const option = document.createElement('option');
                option.value = kategori.id_kategori;
                option.textContent = kategori.nama_kategori;
                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error loading kategori:', error);
    }
}

function openAlatModal(alatData = null) {
    const modal = document.getElementById('alatModal');
    const form = document.getElementById('alatForm');
    const title = document.getElementById('modalTitle');
    
    // Reset form
    form.reset();
    document.getElementById('alatId').value = '';
    
    // Load kategori
    loadKategori();
    
    // Set title dan data jika edit
    if (alatData && alatData.id_alat) {
        title.textContent = 'Edit Alat';
        document.getElementById('alatId').value = alatData.id_alat;
        document.getElementById('namaAlat').value = alatData.nama_alat;
        document.getElementById('kategoriSelect').value = alatData.id_kategori;
        document.getElementById('stok').value = alatData.stok;
        document.getElementById('dipinjam').value = alatData.dipinjam;
    } else {
        title.textContent = 'Tambah Alat Baru';
        document.getElementById('stok').value = 0;
        document.getElementById('dipinjam').value = 0;
    }
    
    // Show modal
    modal.style.display = 'flex';
}

function closeAlatModal() {
    document.getElementById('alatModal').style.display = 'none';
    document.getElementById('alatForm').reset();
    document.getElementById('alatId').value = '';
}

async function saveAlat(e) {
    e.preventDefault();
    
    const alatId = document.getElementById('alatId').value;
    const formData = {
        nama_alat: document.getElementById('namaAlat').value,
        id_kategori: document.getElementById('kategoriSelect').value,
        stok: parseInt(document.getElementById('stok').value),
        dipinjam: parseInt(document.getElementById('dipinjam').value)
    };
    
    try {
        let url = '/api/alat';
        let method = 'POST';
        
        if (alatId) {
            url = `/api/alat/${alatId}`;
            method = 'PUT';
        }
        
        const response = await fetch(url, {
            method: method,
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message || 'Sukses!');
            closeAlatModal();
            loadAlatManagement();
        } else {
            alert('Gagal: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
}

async function editAlat(alatId) {
    try {
        const response = await fetch(`/api/alat/${alatId}`, {
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success && data.data) {
            openAlatModal(data.data);
        } else {
            alert('Gagal memuat data alat');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
}

async function deleteAlat(alatId) {
    if (!confirm('Yakin ingin menghapus alat ini? Data tidak bisa dipulihkan.')) {
        return;
    }
    
    try {
        const response = await fetch(`/api/alat/${alatId}`, {
            method: 'DELETE',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Alat berhasil dihapus');
            loadAlatManagement();
        } else {
            alert('Gagal menghapus alat: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
}
