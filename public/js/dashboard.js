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
    setupProfileForms();
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
            const tbody = document.getElementById('alatBody');
            tbody.innerHTML = '';

            data.data.forEach(alat => {
                const available = alat.stok - alat.dipinjam;
                const row = document.createElement('tr');
                row.style.borderBottom = '1px solid #ddd';
                
                const kategoriName = alat.kategori ? alat.kategori.nama_kategori : '-';
                const tersediaColor = available > 0 ? 'green' : available === 0 ? 'orange' : 'red';
                
                row.innerHTML = `
                    <td style="padding: 10px;">${alat.id_alat}</td>
                    <td style="padding: 10px;">${alat.nama_alat}</td>
                    <td style="padding: 10px;">${kategoriName}</td>
                    <td style="padding: 10px; text-align: center;">${alat.stok}</td>
                    <td style="padding: 10px; text-align: center;">${alat.dipinjam}</td>
                    <td style="padding: 10px; text-align: center; color: ${tersediaColor}; font-weight: bold;">${available}</td>
                `;
                tbody.appendChild(row);
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
        const response = await fetch('/api/activity-logs', {
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
                tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 20px;">Tidak ada log aktivitas</td></tr>';
                return;
            }
            
            data.data.forEach(log => {
                const row = document.createElement('tr');
                row.style.borderBottom = '1px solid #ddd';
                
                row.innerHTML = `
                    <td style="padding: 10px;">${log.user?.username || 'System'}</td>
                    <td style="padding: 10px;"><strong>${log.action}</strong></td>
                    <td style="padding: 10px;">${log.description || '-'}</td>
                    <td style="padding: 10px;">${new Date(log.created_at).toLocaleString('id-ID')}</td>
                `;
                tbody.appendChild(row);
            });
            
            console.log('✓ Activity logs loaded (' + data.data.length + ' items)');
        }
    } catch (error) {
        console.error('Error loading activity logs:', error);
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

function showDetailModal(alat) {
    console.log('📋 Showing detail for alat:', alat);
    
    // Populate modal fields
    document.getElementById('detailId').textContent = alat.id_alat || '-';
    document.getElementById('detailNama').textContent = alat.nama_alat || '-';
    document.getElementById('detailKategori').textContent = alat.kategori ? alat.kategori.nama_kategori : '-';
    document.getElementById('detailStok').textContent = alat.stok || 0;
    
    const dipinjam = alat.dipinjam || 0;
    const tersedia = (alat.stok || 0) - dipinjam;
    
    document.getElementById('detailDipinjam').textContent = dipinjam;
    document.getElementById('detailTersedia').textContent = tersedia;
    
    // Set color based on availability
    const tersediaEl = document.getElementById('detailTersedia');
    tersediaEl.style.color = tersedia > 0 ? 'green' : 'red';
    
    // Handle image
    const detailImage = document.getElementById('detailImage');
    const detailImagePlaceholder = document.getElementById('detailImagePlaceholder');
    
    if (alat.gambar) {
        detailImage.src = `/api/alat/${alat.id_alat}/image`;
        detailImage.style.display = 'block';
        detailImagePlaceholder.style.display = 'none';
    } else {
        detailImage.style.display = 'none';
        detailImagePlaceholder.style.display = 'flex';
    }
    
    // Show modal
    document.getElementById('detailAlatModal').style.display = 'flex';
}

function closeDetailModal() {
    document.getElementById('detailAlatModal').style.display = 'none';
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
                
                // Load photo if exists
                if (profileData.foto) {
                    const photo = document.getElementById('profilePhoto');
                    if (photo) {
                        photo.src = `/api/users/${profileData.id_user}/photo`;
                        photo.style.display = 'block';
                        const placeholder = document.getElementById('photoPlaceholder');
                        if (placeholder) placeholder.style.display = 'none';
                        console.log('Profile photo loaded:', photo.src);
                    }
                }
            }, 100);
        } else {
            console.warn('API response not successful or no data:', data);
        }
    } catch (error) {
        console.error('Error loading profile:', error);
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
            loadUserProfile(); // Reload profile
            document.getElementById('photoInput').value = ''; // Clear file input
        } else {
            // Display validation errors if available
            let errorMessage = data.message || 'Terjadi kesalahan';
            if (data.errors) {
                let errorList = Object.keys(data.errors).map(field => {
                    return `${field}: ${data.errors[field].join(', ')}`;
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
