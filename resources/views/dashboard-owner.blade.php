<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Owner - Sistem Peminjaman Alat</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <h2>Dashboard Owner</h2>
        </div>
        <div class="navbar-menu">
            <span id="userGreeting"></span>
            <button id="logoutBtn" class="btn btn-danger">Logout</button>
        </div>
    </nav>

    <div class="dashboard-container">
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <a href="#" class="nav-item active" data-section="alat">Data Alat</a>
                <a href="#" class="nav-item" data-section="peminjaman">Data Peminjaman</a>
                <a href="#" class="nav-item" data-section="activity">Log Aktivitas</a>
                <a href="#" class="nav-item" data-section="profile">Profil</a>
            </nav>
        </aside>

        <main class="dashboard-content">
            <!-- Data Alat Section -->
            <section id="alat-section" class="section active">
                <h2>Data Alat</h2>
                <div id="alatManagement" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
                                <th style="padding: 10px; text-align: left;">ID</th>
                                <th style="padding: 10px; text-align: left;">Nama Alat</th>
                                <th style="padding: 10px; text-align: left;">Kategori</th>
                                <th style="padding: 10px; text-align: center;">Stock Total</th>
                                <th style="padding: 10px; text-align: center;">Dipinjam</th>
                                <th style="padding: 10px; text-align: center;">Tersedia</th>
                            </tr>
                        </thead>
                        <tbody id="alatBody">
                            <tr><td colspan="6" style="text-align: center; padding: 20px;">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Data Peminjaman Section -->
            <section id="peminjaman-section" class="section">
                <h2>Data Peminjaman</h2>
                <div id="peminjamanManagement" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
                                <th style="padding: 10px; text-align: left;">ID</th>
                                <th style="padding: 10px; text-align: left;">Peminjam</th>
                                <th style="padding: 10px; text-align: left;">Alat</th>
                                <th style="padding: 10px; text-align: center;">Jumlah</th>
                                <th style="padding: 10px; text-align: left;">Tgl Peminjaman</th>
                                <th style="padding: 10px; text-align: left;">Tgl Kembali</th>
                                <th style="padding: 10px; text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="peminjamanBody">
                            <tr><td colspan="7" style="text-align: center; padding: 20px;">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Log Aktivitas Section -->
            <section id="activity-section" class="section">
                <h2>Log Aktivitas</h2>
                <div id="activityManagement" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
                                <th style="padding: 10px; text-align: left;">ID</th>
                                <th style="padding: 10px; text-align: left;">User</th>
                                <th style="padding: 10px; text-align: left;">Aksi</th>
                                <th style="padding: 10px; text-align: left;">Deskripsi</th>
                                <th style="padding: 10px; text-align: left;">Waktu</th>
                            </tr>
                        </thead>
                        <tbody id="activityBody">
                            <tr><td colspan="5" style="text-align: center; padding: 20px;">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Profil Section -->
            <section id="profile-section" class="section">
                <h2>Profil Saya</h2>
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px;">
                    <!-- Foto Profil -->
                    <div style="background: #f5f5f5; padding: 20px; border-radius: 8px; text-align: center;">
                        <div id="photoContainer" style="width: 150px; height: 150px; margin: 0 auto 15px; border: 2px solid #ddd; border-radius: 50%; overflow: hidden; background: white; display: flex; align-items: center; justify-content: center;">
                            <img id="profilePhoto" src="" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                            <span id="photoPlaceholder" style="color: #999; font-size: 14px;">Tidak ada foto</span>
                        </div>
                        <button type="button" id="changePhotoBtn" class="btn" style="padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%;">Ubah Foto</button>
                        <input type="file" id="photoInput" accept="image/*" style="display: none;">
                    </div>
                    
                    <!-- Form Profil -->
                    <div style="background: #f5f5f5; padding: 20px; border-radius: 8px;">
                        <form id="profileForm" style="display: flex; flex-direction: column; gap: 15px;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                <div>
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Username</label>
                                    <input type="text" id="username" name="username" disabled style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; background: #e9ecef;">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nama Lengkap</label>
                                    <input type="text" id="namaLengkap" name="nama_lengkap" placeholder="Nama lengkap Anda" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                <div>
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Email</label>
                                    <input type="email" id="email" name="email" placeholder="Email Anda" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">No. Telepon</label>
                                    <input type="text" id="phone" name="phone" placeholder="081234567890" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                                </div>
                            </div>

                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Alamat</label>
                                <textarea id="alamat" name="alamat" placeholder="Alamat lengkap Anda" rows="3" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;"></textarea>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
                                <div>
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Kota</label>
                                    <input type="text" id="kota" name="kota" placeholder="Kota" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Provinsi</label>
                                    <input type="text" id="provinsi" name="provinsi" placeholder="Provinsi" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Kode Pos</label>
                                    <input type="text" id="kodePos" name="kode_pos" placeholder="12345" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px;">
                                <button type="submit" class="btn" style="padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Simpan Perubahan</button>
                                <button type="button" id="changePasswordBtn" class="btn" style="padding: 10px; background: #ffc107; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Ubah Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Modal Ubah Password -->
            <div id="changePasswordModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
                <div style="background: white; padding: 30px; border-radius: 8px; width: 90%; max-width: 400px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
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
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <button type="submit" class="btn" style="padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Ubah</button>
                            <button type="button" id="closePasswordModalBtn" class="btn" style="padding: 10px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>

    </div>

    <script src="{{ asset('js/dashboard-owner.js') }}"></script>
</body>
</html>
