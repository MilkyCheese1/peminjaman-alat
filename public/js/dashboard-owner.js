// Dashboard Owner JS

document.addEventListener('DOMContentLoaded', function() {
    console.log('✓ Owner dashboard loaded');
    loadOwnerProfile();
    loadAlatData();
    loadPeminjamanData();
    loadActivityLog();
    setupNavigation();
    setupLogout();
    loadUserGreeting();
    setupProfileForms();
});

// ===== Navigation Setup =====
function setupNavigation() {
    console.log('⚙️ Setting up navigation...');
    const navItems = document.querySelectorAll('.nav-item');
    const sections = document.querySelectorAll('.section');
    
    console.log('Found ' + navItems.length + ' nav items and ' + sections.length + ' sections');

    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();

            // Remove active from all
            navItems.forEach(nav => nav.classList.remove('active'));
            sections.forEach(section => section.classList.remove('active'));

            // Add active to clicked
            this.classList.add('active');
            const sectionId = this.getAttribute('data-section') + '-section';
            console.log('Switching to section:', sectionId);
            const section = document.getElementById(sectionId);
            if (section) {
                section.classList.add('active');
            } else {
                console.error('Section not found:', sectionId);
            }
        });
    });
}

// ===== User Greeting =====
function loadUserGreeting() {
    console.log('👋 Loading user greeting...');
    const user = document.querySelector('meta[name="username"]');
    const greeting = document.getElementById('userGreeting');
    if (greeting) {
        const username = localStorage.getItem('username') || 'User';
        greeting.textContent = `Selamat datang, ${username}!`;
        console.log('✓ Greeting set to:', greeting.textContent);
    } else {
        console.warn('⚠ userGreeting element not found');
    }
}

// ===== Logout =====
function setupLogout() {
    console.log('🚪 Setting up logout...');
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        console.log('✓ Logout button found');
        logoutBtn.addEventListener('click', async function() {
            console.log('Logging out...');
            try {
                const response = await fetch('/api/auth/logout', {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    console.log('✓ Logout successful');
                    localStorage.removeItem('username');
                    window.location.href = '/login';
                }
            } catch (error) {
                console.error('Logout error:', error);
            }
        });
    } else {
        console.warn('⚠ Logout button not found');
    }
}

// ===== Load Alat Data =====
async function loadAlatData() {
    console.log('📦 Loading alat data...');
    try {
        const response = await fetch('/api/alat', {
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        console.log('✓ Alat API response status:', response.status);
        const data = await response.json();
        console.log('✓ Alat API data:', data);
        
        const tbody = document.getElementById('alatBody');
        if (!tbody) {
            console.error('✗ alatBody element not found!');
            return;
        }

        if (data.success && data.data && data.data.length > 0) {
            console.log('✓ Found ' + data.data.length + ' alat(s)');
            
            tbody.innerHTML = data.data.map(alat => {
                const tersedia = alat.stok - alat.dipinjam;
                const tersediaColor = tersedia > 0 ? 'green' : 'red';
                
                return `
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 10px;">${alat.id_alat}</td>
                        <td style="padding: 10px;">${alat.nama_alat}</td>
                        <td style="padding: 10px;">${alat.kategori ? alat.kategori.nama_kategori : '-'}</td>
                        <td style="padding: 10px; text-align: center;">${alat.stok}</td>
                        <td style="padding: 10px; text-align: center;">${alat.dipinjam}</td>
                        <td style="padding: 10px; text-align: center; color: ${tersediaColor}; font-weight: bold;">${tersedia}</td>
                    </tr>
                `;
            }).join('');
        } else {
            console.warn('⚠ No alat data found or success is false', data);
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data alat</td></tr>';
        }
    } catch (error) {
        console.error('✗ Error loading alat:', error);
        const tbody = document.getElementById('alatBody');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 20px; color: red;">Error: ' + error.message + '</td></tr>';
        }
    }
}

// ===== Load Peminjaman Data =====
async function loadPeminjamanData() {
    console.log('📋 Loading peminjaman data...');
    try {
        const response = await fetch('/api/peminjaman', {
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        console.log('✓ Peminjaman API response status:', response.status);
        const data = await response.json();
        console.log('✓ Peminjaman API data:', data);
        
        const tbody = document.getElementById('peminjamanBody');
        if (!tbody) {
            console.error('✗ peminjamanBody element not found!');
            return;
        }

        if (data.success && data.data && data.data.length > 0) {
            console.log('✓ Found ' + data.data.length + ' peminjaman(s)');
            tbody.innerHTML = data.data.map(peminjaman => {
                const statusColor = {
                    'pending': '#ffc107',
                    'approved': '#28a745',
                    'borrowed': '#17a2b8',
                    'returned': '#6c757d',
                    'rejected': '#dc3545'
                };

                return `
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 10px;">${peminjaman.id_peminjaman}</td>
                        <td style="padding: 10px;">${peminjaman.user?.username || '-'}</td>
                        <td style="padding: 10px;">${peminjaman.alat?.nama_alat || '-'}</td>
                        <td style="padding: 10px; text-align: center;">${peminjaman.jumlah}</td>
                        <td style="padding: 10px;">${new Date(peminjaman.tgl_peminjaman).toLocaleDateString('id-ID')}</td>
                        <td style="padding: 10px;">${peminjaman.tgl_kembali ? new Date(peminjaman.tgl_kembali).toLocaleDateString('id-ID') : '-'}</td>
                        <td style="padding: 10px; text-align: center;">
                            <span style="background: ${statusColor[peminjaman.status] || '#6c757d'}; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                ${peminjaman.status || 'unknown'}
                            </span>
                        </td>
                    </tr>
                `;
            }).join('');
        } else {
            console.warn('⚠ No peminjaman data found or success is false', data);
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 20px;">Tidak ada data peminjaman</td></tr>';
        }
    } catch (error) {
        console.error('✗ Error loading peminjaman:', error);
        const tbody = document.getElementById('peminjamanBody');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 20px; color: red;">Error: ' + error.message + '</td></tr>';
        }
    }
}

// ===== Load Activity Log =====
async function loadActivityLog() {
    console.log('🔍 Loading activity log...');
    try {
        const response = await fetch('/api/activity-logs', {
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        console.log('✓ Activity log API response status:', response.status);
        const data = await response.json();
        console.log('✓ Activity log API data:', data);
        
        const tbody = document.getElementById('activityBody');
        if (!tbody) {
            console.error('✗ activityBody element not found!');
            return;
        }

        if (data.success && data.data && data.data.length > 0) {
            console.log('✓ Found ' + data.data.length + ' activity log(s)');
            tbody.innerHTML = data.data.map(log => {
                return `
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 10px;">${log.id_activity || log.id}</td>
                        <td style="padding: 10px;">${log.user?.username || 'System'}</td>
                        <td style="padding: 10px;"><strong>${log.action}</strong></td>
                        <td style="padding: 10px;">${log.description || '-'}</td>
                        <td style="padding: 10px;">${new Date(log.created_at).toLocaleString('id-ID')}</td>
                    </tr>
                `;
            }).join('');
        } else {
            console.warn('⚠ No activity log data found or success is false', data);
            tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px;">Tidak ada log aktivitas</td></tr>';
        }
    } catch (error) {
        console.error('✗ Error loading activity log:', error);
        const tbody = document.getElementById('activityBody');
        if (tbody) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px; color: red;">Error: ' + error.message + '</td></tr>';
        }
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

async function loadOwnerProfile() {
    console.log('👤 Loading owner profile...');
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

        const profileData = data.data || data.user;

        if (data.success && profileData) {
            setTimeout(() => {
                console.log('📋 Populating profile form...');

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

                console.log('✓ All profile fields populated');

                // Save username for greeting
                localStorage.setItem('username', profileData.username);

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

async function saveProfile(e) {
    e.preventDefault();

    const formData = new FormData();

    const namaLengkap = document.getElementById('namaLengkap').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const alamat = document.getElementById('alamat').value.trim();
    const kota = document.getElementById('kota').value.trim();
    const provinsi = document.getElementById('provinsi').value.trim();
    const kodePos = document.getElementById('kodePos').value.trim();

    if (namaLengkap) formData.append('nama_lengkap', namaLengkap);
    if (email) formData.append('email', email);
    if (phone) formData.append('phone', phone);
    if (alamat) formData.append('alamat', alamat);

    // Always append location fields
    formData.append('kota', kota);
    formData.append('provinsi', provinsi);
    formData.append('kode_pos', kodePos);

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
            loadOwnerProfile();
            document.getElementById('photoInput').value = '';
        } else {
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
    const passwordLama = document.getElementById('passwordLama').value;

    if (passwordBaru !== passwordConfirm) {
        alert('Password baru dan konfirmasi tidak cocok');
        return;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch('/api/profile/change-password', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                password_lama: passwordLama,
                password_baru: passwordBaru,
                password_baru_confirmation: passwordConfirm
            })
        });

        const data = await response.json();

        if (data.success) {
            alert('Password berhasil diubah!');
            document.getElementById('changePasswordModal').style.display = 'none';
            document.getElementById('changePasswordForm').reset();
        } else {
            alert('Gagal mengubah password: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
}
