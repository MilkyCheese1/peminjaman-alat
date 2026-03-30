<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Staff - Sistem Peminjaman Alat</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <h2>Dashboard Staff</h2>
        </div>
        <div class="navbar-menu">
            <span id="userGreeting"></span>
            <button id="logoutBtn" class="btn btn-danger">Logout</button>
        </div>
    </nav>

    <div class="dashboard-container">
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <a href="#" class="nav-item active" data-section="overview">Overview</a>
                <a href="#" class="nav-item" data-section="peminjaman">Verifikasi Peminjaman</a>
                <a href="#" class="nav-item" data-section="profile">Profil</a>
            </nav>
        </aside>

        <main class="dashboard-content">
            <!-- Overview Section -->
            <section id="overview-section" class="section active">
                <h2>Overview</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Total Peminjaman</h3>
                        <p class="stat-value" id="totalPeminjaman">-</p>
                    </div>
                    <div class="stat-card">
                        <h3>Pending</h3>
                        <p class="stat-value" id="pendingPeminjaman">-</p>
                    </div>
                    <div class="stat-card">
                        <h3>Disetujui</h3>
                        <p class="stat-value" id="approvedPeminjaman">-</p>
                    </div>
                    <div class="stat-card">
                        <h3>Alat Tersedia</h3>
                        <p class="stat-value" id="availableAlat">-</p>
                    </div>
                </div>
            </section>

            <!-- Manajemen Peminjaman Section -->
            <section id="peminjaman-section" class="section">
                <h2>Manajemen Peminjaman</h2>
                <div id="peminjamanManagement" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
                                <th style="padding: 10px; text-align: left;">ID</th>
                                <th style="padding: 10px; text-align: left;">User</th>
                                <th style="padding: 10px; text-align: left;">Alat</th>
                                <th style="padding: 10px; text-align: left;">Tgl Pinjam</th>
                                <th style="padding: 10px; text-align: left;">Tgl Kembali</th>
                                <th style="padding: 10px; text-align: left;">Status</th>
                                <th style="padding: 10px; text-align: left;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="peminjamanBody">
                            <tr><td colspan="7" style="text-align: center; padding: 20px;">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Profil Section -->
            <section id="profile-section" class="section">
                <h2 style="margin-bottom: 20px; font-size: 20px; color: #333;">Profil Saya</h2>
                <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Profile Grid: Photo Left, Form Right -->
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px; margin-bottom: 20px;">
                        <!-- Left: Photo Section -->
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            <div style="width: 150px; height: 150px; border-radius: 50%; border: 2px solid #ddd; overflow: hidden; background: #f9f9f9; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <img id="profilePhoto" src="" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                <span id="photoPlaceholder" style="color: #999; font-size: 14px;">Tidak ada foto</span>
                            </div>
                            <button type="button" id="changePhotoBtn" class="btn" style="padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%;">Ubah Foto</button>
                            <input type="file" id="photoInput" accept="image/*" style="display: none;">
                        </div>

                        <!-- Right: Profile Form -->
                        <div>
                            <form id="profileForm" style="display: flex; flex-direction: column; gap: 15px;">
                                <div>
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Username</label>
                                    <input type="text" id="username" name="username" placeholder="Username Anda" disabled style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; background: #f9f9f9; color: #666;">
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nama Lengkap</label>
                                    <input type="text" id="namaLengkap" name="nama_lengkap" placeholder="Nama lengkap Anda" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Email</label>
                                    <input type="email" id="email" name="email" placeholder="Email Anda" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">No. Telpon</label>
                                    <input type="text" id="phone" name="phone" placeholder="081234567890" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                                </div>

                                <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Alamat</label>
                                <textarea id="alamat" name="alamat" placeholder="Alamat lengkap Anda" rows="3" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;"></textarea>
                                </div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                    <div>
                                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Kota</label>
                                        <input type="text" id="kota" name="kota" placeholder="Kota" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                                    </div>
                                    <div>
                                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Provinsi</label>
                                        <input type="text" id="provinsi" name="provinsi" placeholder="Provinsi" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                                    </div>
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Kode Pos</label>
                                    <input type="text" id="kodePos" name="kode_pos" placeholder="12345" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                                </div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px;">
                                    <button type="submit" class="btn" style="padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Simpan Perubahan</button>
                                    <button type="button" id="changePasswordBtn" class="btn" style="padding: 10px; background: #ffc107; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Ubah Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Change Password Modal -->
                <div id="changePasswordModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
                    <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); width: 90%; max-width: 500px;">
                        <h3 style="margin-top: 0; margin-bottom: 20px;">Ubah Password</h3>
                        <form id="changePasswordForm" style="display: flex; flex-direction: column; gap: 15px;">
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Password Lama</label>
                                <input type="password" id="passwordLama" name="password_lama" placeholder="Masukkan password lama" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Password Baru</label>
                                <input type="password" id="passwordBaru" name="password_baru" placeholder="Masukkan password baru" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Konfirmasi Password</label>
                                <input type="password" id="passwordConfirm" name="password_baru_confirmation" placeholder="Konfirmasi password baru" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px;">
                                <button type="submit" class="btn" style="padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Ubah Password</button>
                                <button type="button" id="closePasswordModalBtn" class="btn" style="padding: 10px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="{{ asset('js/dark-mode.js') }}"></script>
    <script src="{{ asset('js/dashboard-staff.js') }}"></script>
</body>
</html>
