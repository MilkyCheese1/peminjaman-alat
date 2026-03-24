<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Peminjaman Alat</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <h2>Dashboard Admin</h2>
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
                <a href="#" class="nav-item" data-section="users">Manajemen User</a>
                <a href="#" class="nav-item" data-section="alat">Manajemen Alat</a>
                <a href="#" class="nav-item" data-section="peminjaman">Peminjaman</a>
                <a href="#" class="nav-item" data-section="profile">Profil</a>
            </nav>
        </aside>

        <main class="dashboard-content">
            <!-- Overview Section -->
            <section id="overview-section" class="section active">
                <h2>Overview</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Total Users</h3>
                        <p class="stat-value" id="totalUsers">-</p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Alat</h3>
                        <p class="stat-value" id="totalAlat">-</p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Peminjaman</h3>
                        <p class="stat-value" id="totalPeminjaman">-</p>
                    </div>
                    <div class="stat-card">
                        <h3>Pending</h3>
                        <p class="stat-value" id="pendingPeminjaman">-</p>
                    </div>
                </div>
            </section>

            <!-- Manajemen User Section -->
            <section id="users-section" class="section">
                <h2>Manajemen User</h2>
                <div id="usersManagement" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
                                <th style="padding: 10px; text-align: left;">ID</th>
                                <th style="padding: 10px; text-align: left;">Username</th>
                                <th style="padding: 10px; text-align: left;">Email</th>
                                <th style="padding: 10px; text-align: left;">Role</th>
                                <th style="padding: 10px; text-align: left;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="usersBody">
                            <tr><td colspan="5" style="text-align: center; padding: 20px;">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Manajemen Alat Section -->
            <section id="alat-section" class="section">
                <h2>Manajemen Alat</h2>
                <button id="addAlatBtn" class="btn" style="margin-bottom: 15px; padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">+ Tambah Alat Baru</button>
                <div id="alatManagement" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
                                <th style="padding: 10px; text-align: left;">ID</th>
                                <th style="padding: 10px; text-align: left;">Nama Alat</th>
                                <th style="padding: 10px; text-align: left;">Kategori</th>
                                <th style="padding: 10px; text-align: left;">Stock</th>
                                <th style="padding: 10px; text-align: left;">Dipinjam</th>
                                <th style="padding: 10px; text-align: left;">Tersedia</th>
                                <th style="padding: 10px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="alatBody">
                            <tr><td colspan="7" style="text-align: center; padding: 20px;">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Modal Form Alat -->
            <div id="alatModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
                <div style="background: white; padding: 30px; border-radius: 8px; width: 90%; max-width: 500px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <h3 id="modalTitle" style="margin-top: 0; margin-bottom: 20px;">Tambah Alat Baru</h3>
                    <form id="alatForm" style="display: flex; flex-direction: column; gap: 15px;">
                        <input type="hidden" id="alatId" name="alatId">
                        
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nama Alat</label>
                            <input type="text" id="namaAlat" name="nama_alat" placeholder="Contoh: Bor Listrik" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Kategori</label>
                            <select id="kategoriSelect" name="id_kategori" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                                <option value="">-- Pilih Kategori --</option>
                            </select>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Stok</label>
                                <input type="number" id="stok" name="stok" placeholder="0" min="0" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Dipinjam</label>
                                <input type="number" id="dipinjam" name="dipinjam" placeholder="0" min="0" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 20px;">
                            <button type="submit" id="submitBtn" class="btn" style="padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Simpan</button>
                            <button type="button" id="closeModalBtn" class="btn" style="padding: 10px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

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
                                <th style="padding: 10px; text-align: left;">Status</th>
                                <th style="padding: 10px; text-align: left;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="peminjamanBody">
                            <tr><td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Profil Section -->
            <section id="profile-section" class="section">
                <h2>Profil Admin</h2>
                <div id="profileContent" style="background: #f5f5f5; padding: 20px; border-radius: 8px;">
                    <!-- Loaded from API -->
                </div>
            </section>
        </main>
    </div>

    <script src="<?php echo e(asset('js/dashboard-admin.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\laragon\www\peminjaman-alat\resources\views/dashboard-admin.blade.php ENDPATH**/ ?>