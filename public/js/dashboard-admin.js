// Dashboard Admin JS

document.addEventListener('DOMContentLoaded', function() {
    loadDashboardStats();
    loadUsersManagement();
    loadKategoriManagement();
    loadAlatManagement();
    loadPeminjamanManagement();
    loadAdminProfile();
    setupNavigation();
    setupLogout();
    loadUserGreeting();
    setupAlatCRUD();
    setupKategoriCRUD();
    setupProfileForms();
    setupDetailModalAdmin();
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

async function loadKategoriManagement() {
    try {
        const response = await fetch('/api/kategoris', {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success && data.data) {
            const tbody = document.getElementById('kategoriBody');
            tbody.innerHTML = '';
            
            data.data.forEach(kategori => {
                const row = document.createElement('tr');
                row.style.borderBottom = '1px solid #ddd';
                row.innerHTML = `
                    <td style="padding: 10px;">${kategori.id_kategori}</td>
                    <td style="padding: 10px;">${kategori.nama_kategori}</td>
                    <td style="padding: 10px; text-align: center;">
                        <span style="background: #e3f2fd; padding: 4px 8px; border-radius: 4px;">${kategori.alat_count || 0}</span>
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        <button onclick="editKategori(${kategori.id_kategori})" style="padding: 5px 10px; background: #ffc107; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">Edit</button>
                        <button onclick="deleteKategori(${kategori.id_kategori})" style="padding: 5px 10px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Hapus</button>
                    </td>
                `;
                tbody.appendChild(row);
            });

            console.log('✓ Kategori loaded (' + data.data.length + ' kategori)');
        }
    } catch (error) {
        console.error('Error loading kategori:', error);
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
                const imageDisplay = alat.gambar ? `<img src="/api/alat/${alat.id_alat}/image" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">` : '<span style="color: #999;">-</span>';
                const row = document.createElement('tr');
                row.style.borderBottom = '1px solid #ddd';
                row.innerHTML = `
                    <td style="padding: 10px;">${alat.id_alat}</td>
                    <td style="padding: 10px;">${alat.nama_alat}</td>
                    <td style="padding: 10px; text-align: center;">${imageDisplay}</td>
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
        console.log('✓ Profile API Response:', data);
        
        // API returns either 'data' or 'user' property
        const profileData = data.data || data.user;
        
        if (data.success && profileData) {
            // Add small delay to ensure DOM is ready
            setTimeout(() => {
                console.log('📋 Populating form with:', {
                    username: profileData.username,
                    nama_lengkap: profileData.nama_lengkap,
                    kota: profileData.kota,
                    provinsi: profileData.provinsi,
                    kode_pos: profileData.kode_pos
                });
                
                // Check if elements exist before populating
                const usernameEl = document.getElementById('username');
                const namaLengkapEl = document.getElementById('namaLengkap');
                const emailEl = document.getElementById('email');
                const phoneEl = document.getElementById('phone');
                const alamatEl = document.getElementById('alamat');
                const kotaEl = document.getElementById('kota');
                const provinsiEl = document.getElementById('provinsi');
                const kodePosEl = document.getElementById('kodePos');
                
                console.log('Field elements found:', {
                    username: !!usernameEl,
                    namaLengkap: !!namaLengkapEl,
                    kota: !!kotaEl,
                    provinsi: !!provinsiEl,
                    kodePos: !!kodePosEl
                });
                
                if (usernameEl) {
                    usernameEl.value = profileData.username || '';
                    console.log('✓ Set username:', usernameEl.value);
                }
                if (namaLengkapEl) {
                    namaLengkapEl.value = profileData.nama_lengkap || '';
                    console.log('✓ Set nama_lengkap:', namaLengkapEl.value);
                }
                if (emailEl) {
                    emailEl.value = profileData.email || '';
                    console.log('✓ Set email:', emailEl.value);
                }
                if (phoneEl) {
                    phoneEl.value = profileData.phone || '';
                    console.log('✓ Set phone:', phoneEl.value);
                }
                if (alamatEl) {
                    alamatEl.value = profileData.alamat || '';
                    console.log('✓ Set alamat:', alamatEl.value);
                }
                if (kotaEl) {
                    kotaEl.value = profileData.kota || '';
                    console.log('✓ Set kota:', kotaEl.value);
                }
                if (provinsiEl) {
                    provinsiEl.value = profileData.provinsi || '';
                    console.log('✓ Set provinsi:', provinsiEl.value);
                }
                if (kodePosEl) {
                    kodePosEl.value = profileData.kode_pos || '';
                    console.log('✓ Set kode_pos:', kodePosEl.value);
                }
                
                console.log('✓ All form fields populated');
                
                // Load profile photo if exists
                if (profileData.foto) {
                    const photo = document.getElementById('profilePhoto');
                    if (photo) {
                        photo.src = `/api/users/${profileData.id_user}/photo`;
                        photo.style.display = 'block';
                        const placeholder = document.getElementById('photoPlaceholder');
                        if (placeholder) placeholder.style.display = 'none';
                        console.log('✓ Profile photo loaded:', photo.src);
                    }
                }
            }, 100);
        } else {
            console.warn('✗ API response not successful or no data:', data);
        }
    } catch (error) {
        console.error('✗ Error loading profile:', error);
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
    
    // Image preview
    document.getElementById('gambarAlat').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.style.backgroundImage = 'url(' + event.target.result + ')';
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
    
    // Close modal saat klik di luar modal
    document.getElementById('alatModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAlatModal();
        }
    });
    
    // Kategori management
    document.getElementById('addKategoriBtn').addEventListener('click', openKategoriModal);
    document.getElementById('closeKategoriModalBtn').addEventListener('click', closeKategoriModal);
    document.getElementById('kategoriForm').addEventListener('submit', saveKategori);
    
    document.getElementById('kategoriModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeKategoriModal();
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
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('imagePreview').style.backgroundImage = 'none';
    
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
        
        // Show existing image if available
        if (alatData.gambar) {
            const preview = document.getElementById('imagePreview');
            preview.style.backgroundImage = 'url(/api/alat/' + alatData.id_alat + '/image)';
            preview.style.display = 'block';
        }
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
    const gambarFile = document.getElementById('gambarAlat').files[0];
    
    // Use FormData untuk handle file upload
    const formData = new FormData();
    formData.append('nama_alat', document.getElementById('namaAlat').value);
    formData.append('id_kategori', document.getElementById('kategoriSelect').value);
    formData.append('stok', parseInt(document.getElementById('stok').value));
    formData.append('dipinjam', parseInt(document.getElementById('dipinjam').value));
    
    // Add image if selected
    if (gambarFile) {
        formData.append('gambar', gambarFile);
    }
    
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
            body: formData
            // Note: Don't set Content-Type header for FormData, browser will set it automatically
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

// ===== Kategori Management Functions =====

function openKategoriModal() {
    const modal = document.getElementById('kategoriModal');
    document.getElementById('kategoriForm').reset();
    modal.style.display = 'flex';
}

function closeKategoriModal() {
    document.getElementById('kategoriModal').style.display = 'none';
    document.getElementById('kategoriForm').reset();
}

async function saveKategori(e) {
    e.preventDefault();
    
    const namaKategori = document.getElementById('namaKategori').value;
    
    try {
        const response = await fetch('/api/kategoris', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                nama_kategori: namaKategori
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Kategori berhasil ditambahkan!');
            closeKategoriModal();
            loadKategori(); // Refresh kategori dropdown
        } else {
            alert('Gagal menambah kategori: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
}

// ===== Kategori Management CRUD Functions =====

function setupKategoriCRUD() {
    // Button untuk tambah kategori baru
    document.getElementById('addKategoriNewBtn').addEventListener('click', openKategoriModalManagement);
    
    // Button untuk close modal
    document.getElementById('closeKategoriModalManagementBtn').addEventListener('click', closeKategoriModalManagement);
    
    // Form submit
    document.getElementById('kategoriFormManagement').addEventListener('submit', saveKategoriManagement);
    
    // Close modal saat klik di luar modal
    document.getElementById('kategoriModalManagement').addEventListener('click', function(e) {
        if (e.target === this) {
            closeKategoriModalManagement();
        }
    });
}

function openKategoriModalManagement(kategoriData = null) {
    const modal = document.getElementById('kategoriModalManagement');
    const form = document.getElementById('kategoriFormManagement');
    const title = document.getElementById('kategoriModalTitle');
    
    // Reset form
    form.reset();
    document.getElementById('kategoriIdManagement').value = '';
    
    // Set title dan data jika edit
    if (kategoriData && kategoriData.id_kategori) {
        title.textContent = 'Edit Kategori';
        document.getElementById('kategoriIdManagement').value = kategoriData.id_kategori;
        document.getElementById('namaKategoriManagement').value = kategoriData.nama_kategori;
    } else {
        title.textContent = 'Tambah Kategori Baru';
    }
    
    // Show modal
    modal.style.display = 'flex';
}

function closeKategoriModalManagement() {
    document.getElementById('kategoriModalManagement').style.display = 'none';
    document.getElementById('kategoriFormManagement').reset();
    document.getElementById('kategoriIdManagement').value = '';
}

async function saveKategoriManagement(e) {
    e.preventDefault();
    
    const kategoriId = document.getElementById('kategoriIdManagement').value;
    const namaKategori = document.getElementById('namaKategoriManagement').value;
    
    try {
        let url = '/api/kategoris';
        let method = 'POST';
        
        if (kategoriId) {
            url = `/api/kategoris/${kategoriId}`;
            method = 'PUT';
        }
        
        const response = await fetch(url, {
            method: method,
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ nama_kategori: namaKategori })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message || 'Sukses!');
            closeKategoriModalManagement();
            loadKategoriManagement();
            loadKategori(); // Refresh kategori dropdown in alat form
        } else {
            alert('Gagal: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
}

async function editKategori(kategoriId) {
    try {
        const response = await fetch(`/api/kategoris/${kategoriId}`, {
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success && data.data) {
            openKategoriModalManagement(data.data);
        } else {
            alert('Gagal memuat data kategori');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
}

async function deleteKategori(kategoriId) {
    if (!confirm('Yakin ingin menghapus kategori ini? (Hanya bisa dihapus jika tidak ada alat yang menggunakan kategori ini)')) {
        return;
    }
    
    try {
        const response = await fetch(`/api/kategoris/${kategoriId}`, {
            method: 'DELETE',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Kategori berhasil dihapus');
            loadKategoriManagement();
            loadKategori(); // Refresh kategori dropdown
        } else {
            alert('Gagal menghapus kategori: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
}

// ===== Profile Management Functions =====

function setupProfileForms() {
    // Change photo button
    document.getElementById('changePhotoBtn').addEventListener('click', function() {
        document.getElementById('photoInput').click();
    });
    
    // Photo input change
    document.getElementById('photoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const photo = document.getElementById('profilePhoto');
                photo.src = event.target.result;
                photo.style.display = 'block';
                document.getElementById('photoPlaceholder').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Profile form submit
    document.getElementById('profileForm').addEventListener('submit', saveProfile);
    
    // Change password button
    document.getElementById('changePasswordBtn').addEventListener('click', function() {
        document.getElementById('changePasswordModal').style.display = 'flex';
    });
    
    // Close password modal
    document.getElementById('closePasswordModalBtn').addEventListener('click', function() {
        document.getElementById('changePasswordModal').style.display = 'none';
    });
    
    // Change password form
    document.getElementById('changePasswordForm').addEventListener('submit', changePassword);
    
    // Close modal on background click
    document.getElementById('changePasswordModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
}

async function saveProfile(e) {
    e.preventDefault();
    
    const formData = new FormData();
    
    // Get all field values (always include, even if empty for database update)
    const namaLengkap = document.getElementById('namaLengkap').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const alamat = document.getElementById('alamat').value.trim();
    const kota = document.getElementById('kota').value.trim();
    const provinsi = document.getElementById('provinsi').value.trim();
    const kodePos = document.getElementById('kodePos').value.trim();
    
    // Always append non-empty fields
    if (namaLengkap) formData.append('nama_lengkap', namaLengkap);
    if (email) formData.append('email', email);
    if (phone) formData.append('phone', phone);
    if (alamat) formData.append('alamat', alamat);
    
    // Always append location fields even if empty (to update database)
    formData.append('kota', kota);
    formData.append('provinsi', provinsi);
    formData.append('kode_pos', kodePos);
    
    // Add photo if changed
    const photoInput = document.getElementById('photoInput');
    if (photoInput.files.length > 0) {
        formData.append('foto', photoInput.files[0]);
    }
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch('/api/profile/update', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Profil berhasil diperbarui!');
            loadAdminProfile(); // Reload profile
            document.getElementById('photoInput').value = ''; // Clear file input
        } else {
            // Display validation errors if available
            let errorMessage = data.message || 'Terjadi kesalahan';
            if (data.errors && typeof data.errors === 'object') {
                let errorList = Object.keys(data.errors).map(field => {
                    let fieldError = data.errors[field];
                    let errorMsg = Array.isArray(fieldError) ? fieldError.join(', ') : String(fieldError);
                    return `${field}: ${errorMsg}`;
                }).join('\n');
                errorMessage += '\n\n' + errorList;
            }
            alert('Gagal memperbarui profil: ' + errorMessage);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
}

async function changePassword(e) {
    e.preventDefault();
    
    const passwordBaru = document.getElementById('passwordBaru').value;
    const passwordConfirm = document.getElementById('passwordConfirm').value;
    
    if (passwordBaru !== passwordConfirm) {
        alert('Password baru tidak sesuai!');
        return;
    }
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch('/api/profile/change-password', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                password_lama: document.getElementById('passwordLama').value,
                password_baru: passwordBaru,
                password_baru_confirmation: passwordConfirm
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Password berhasil diubah!');
            document.getElementById('changePasswordForm').reset();
            document.getElementById('changePasswordModal').style.display = 'none';
        } else {
            alert('Gagal mengubah password: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
}

// ===== Setup Detail Modal Admin =====
function setupDetailModalAdmin() {
    console.log('⚙️ Setting up admin detail modal handlers...');
    
    const modal = document.getElementById('detailAlatModalAdmin');
    const closeXBtn = document.getElementById('detailModalCloseXAdmin');
    const closeBtn = document.getElementById('detailModalCloseBtnAdmin');
    
    if (!modal) {
        console.error('✗ detailAlatModalAdmin element not found');
        return;
    }
    
    // Close button X
    if (closeXBtn) {
        closeXBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            modal.style.display = 'none';
            console.log('✓ Admin detail modal closed via X button');
        });
    }
    
    // Close button Tutup
    if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            modal.style.display = 'none';
            console.log('✓ Admin detail modal closed via Tutup button');
        });
    }
    
    // Close when clicking outside modal (on background)
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
            console.log('✓ Admin detail modal closed via background click');
        }
    });
}

// ===== Show Detail Alat Admin =====
function showDetailAlatAdmin(alat) {
    // Populate modal fields
    document.getElementById('detailIdAdmin').textContent = alat.id_alat || '-';
    document.getElementById('detailNamaAdmin').textContent = alat.nama_alat || '-';
    document.getElementById('detailKategoriAdmin').textContent = alat.kategori ? alat.kategori.nama_kategori : '-';
    document.getElementById('detailStokAdmin').textContent = alat.stok || 0;
    
    const dipinjam = alat.dipinjam || 0;
    const tersedia = (alat.stok || 0) - dipinjam;
    
    document.getElementById('detailDipinjamAdmin').textContent = dipinjam;
    document.getElementById('detailTersediaAdmin').textContent = tersedia;
    
    // Set color based on availability
    const tersediaEl = document.getElementById('detailTersediaAdmin');
    tersediaEl.style.color = tersedia > 0 ? 'green' : 'red';
    
    // Handle image
    const detailImage = document.getElementById('detailImageAdmin');
    const detailImagePlaceholder = document.getElementById('detailImagePlaceholderAdmin');
    
    if (alat.gambar) {
        detailImage.src = `/api/alat/${alat.id_alat}/image`;
        detailImage.style.display = 'block';
        detailImagePlaceholder.style.display = 'none';
    } else {
        detailImage.style.display = 'none';
        detailImagePlaceholder.style.display = 'flex';
    }
    
    // Show modal
    document.getElementById('detailAlatModalAdmin').style.display = 'flex';
}
