<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Peminjaman Alat - Kelola Peminjaman dengan Mudah</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body class="landing">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <h2>TrustEquip</h2>
            </div>
            <button class="burger-menu" id="burgerMenu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="navbar-menu" id="navbarMenu">
                <li><a href="#home">Beranda</a></li>
                <li><a href="#fitur">Fitur</a></li>
                <li><a href="#tentang">Tentang</a></li>
                <li><a href="/login" class="btn-nav">Login</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>Kelola Peminjaman Alat dengan Mudah</h1>
            <p>Platform terpadu untuk mengelola peminjaman alat secara efisien dan terorganisir</p>
        </div>
        <div class="hero-image">
            <div class="placeholder-image">📦</div>
        </div>
    </section>

    <!-- Fitur Section -->
    <section id="fitur" class="fitur">
        <h2>Fitur Utama</h2>
        <div class="fitur-grid">
            <div class="fitur-card">
                <div class="fitur-icon">📋</div>
                <h3>Daftar Alat</h3>
                <p>Kelola daftar lengkap alat yang tersedia dengan deskripsi dan status ketersediaan</p>
            </div>

            <div class="fitur-card">
                <div class="fitur-icon">📅</div>
                <h3>Jadwal Peminjaman</h3>
                <p>Kelola peminjaman dengan mudah melalui sistem jadwal yang terintegrasi</p>
            </div>

            <div class="fitur-card">
                <div class="fitur-icon">👥</div>
                <h3>Manajemen Pengguna</h3>
                <p>Kontrol akses peminjam dengan sistem role-based yang fleksibel</p>
            </div>

            <div class="fitur-card">
                <div class="fitur-icon">📊</div>
                <h3>Laporan & Analitik</h3>
                <p>Dapatkan wawasan mendalam tentang penggunaan alat melalui laporan terperinci</p>
            </div>

            <div class="fitur-card">
                <div class="fitur-icon">🔔</div>
                <h3>Notifikasi Otomatis</h3>
                <p>Terima pemberitahuan tentang peminjaman dan pengembalian alat</p>
            </div>

            <div class="fitur-card">
                <div class="fitur-icon">🔒</div>
                <h3>Keamanan Data</h3>
                <p>Data Anda terlindungi dengan sistem keamanan tingkat enterprise</p>
            </div>
        </div>
    </section>

    <!-- Tentang Section -->
    <section id="tentang" class="tentang">
        <h2>Mengapa Memilih Aplikasi Kami?</h2>
        <div class="tentang-content">
            <div class="tentang-item">
                <h3>✓ Mudah Digunakan</h3>
                <p>Interface yang intuitif membuat siapa saja dapat menggunakan aplikasi ini tanpa pelatihan khusus.</p>
            </div>
            <div class="tentang-item">
                <h3>✓ Efisien & Cepat</h3>
                <p>Proses peminjaman yang dipercepat menghemat waktu dan meningkatkan produktivitas.</p>
            </div>
            <div class="tentang-item">
                <h3>✓ Dukungan 24/7</h3>
                <p>Tim support kami siap membantu Anda kapan saja dalam mengatasi masalah teknis.</p>
            </div>
            <div class="tentang-item">
                <h3>✓ Skalabilitas</h3>
                <p>Aplikasi dapat dengan mudah disesuaikan dengan kebutuhan organisasi Anda yang berkembang.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Aplikasi Peminjaman Alat</h4>
                <p>Platform terpadu untuk mengelola peminjaman alat dengan efisien.</p>
            </div>
            <div class="footer-section">
                <h4>Menu</h4>
                <ul>
                    <li><a href="#home">Beranda</a></li>
                    <li><a href="#fitur">Fitur</a></li>
                    <li><a href="#tentang">Tentang</a></li>
                    <li><a href="/login">Login</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Kontak</h4>
                <ul>
                    <li>Email: info@peminjaman-alat.com</li>
                    <li>Telepon: +62 123 4567 8900</li>
                    <li>Alamat: Jl. Teknologi No. 1</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Aplikasi Peminjaman Alat. Semua hak cipta dilindungi.</p>
        </div>
    </footer>

    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/landing.js') }}"></script>
</body>
</html>
