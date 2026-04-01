<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustEquip - Sewa Alat Konstruksi & Teknik Mudah</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/landing-new.css')); ?>">
</head>
<body class="landing-page">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-content">
            <div class="navbar-brand">
                <span class="brand-logo">⚙️</span>
                <h1 class="brand-name">TrustEquip</h1>
            </div>
            <button class="navbar-toggle" id="navToggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="navbar-menu" id="navMenu">
                <li><a href="#hero">Beranda</a></li>
                <li><a href="#value">Keuntungan</a></li>
                <li><a href="#cta">Mulai Sekarang</a></li>
                <li><button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">🌙</button></li>
            </ul>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="landing-container">
        <!-- Hero Section -->
        <section id="hero" class="section hero-section">
            <div class="section-content">
                <div class="hero-content">
                    <span class="hero-badge">Solusi Sewa Alat Lokal</span>
                    <h1 class="hero-title">Alat Berkualitas Kapan Saja,<br><span class="gradient-text">Dimana Saja</span></h1>
                    <p class="hero-description">Sewa alat konstruksi, industri & DIY di sekitar Anda tanpa ribet. Simple, terpercaya, dan terjangkau.</p>
                    <div class="hero-cta">
                        <a href="/register" class="btn btn-primary">Daftar Gratis</a>
                        <a href="#value" class="btn btn-secondary">Pelajari Lebih Lanjut</a>
                    </div>
                    <div class="hero-stats">
                        <div class="stat">
                            <div class="stat-value">5000+</div>
                            <div class="stat-label">Pengguna Aktif</div>
                        </div>
                        <div class="stat">
                            <div class="stat-value">1000+</div>
                            <div class="stat-label">Alat Tersedia</div>
                        </div>
                        <div class="stat">
                            <div class="stat-value">50+</div>
                            <div class="stat-label">Area Layanan</div>
                        </div>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="floating-card card-1">
                        <div class="card-icon">🔧</div>
                        <div class="card-text">Alat Berkualitas</div>
                    </div>
                    <div class="floating-card card-2">
                        <div class="card-icon">⚡</div>
                        <div class="card-text">Proses Cepat</div>
                    </div>
                    <div class="floating-card card-3">
                        <div class="card-icon">💳</div>
                        <div class="card-text">Pembayaran Mudah</div>
                    </div>
                </div>
            </div>
            <div class="scroll-hint">
                <span class="hint-text">Scroll untuk lanjut</span>
                <span class="hint-arrow">↓</span>
            </div>
        </section>

        <!-- Value Section -->
        <section id="value" class="section value-section">
            <div class="section-content">
                <div class="section-header">
                    <h2 class="section-title">Mengapa Pilih TrustEquip?</h2>
                    <p class="section-subtitle">Kami menyediakan solusi sewa alat yang dirancang untuk kebutuhan lokal Anda</p>
                </div>
                <div class="value-grid">
                    <div class="value-card">
                        <div class="value-icon">🛡️</div>
                        <h3 class="value-title">100% Terpercaya</h3>
                        <p class="value-text">Semua alat terverifikasi dan terawat dengan standar kualitas tinggi</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">🎯</div>
                        <h3 class="value-title">Lokal & Efisien</h3>
                        <p class="value-text">Layanan di sekitar lokasi Anda, tanpa biaya pengiriman jauh</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">💰</div>
                        <h3 class="value-title">Harga Kompetitif</h3>
                        <p class="value-text">Tarif transparan dan fleksibel sesuai kebutuhan durasi Anda</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">🚀</div>
                        <h3 class="value-title">Proses Mudah</h3>
                        <p class="value-text">Booking online, pickup/delivery cepat, pengembalian tanpa ribet</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">🤝</div>
                        <h3 class="value-title">Dukungan H24</h3>
                        <p class="value-text">Tim customer service siap membantu kapan pun Anda butuh</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">✨</div>
                        <h3 class="value-title">Asuransi Lengkap</h3>
                        <p class="value-text">Setiap penyewaan dilindungi dengan jaminan asuransi komprehensif</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section id="cta" class="section cta-section">
            <div class="section-content">
                <div class="cta-card">
                    <h2 class="cta-title">Siap Mulai Penyewaan?</h2>
                    <p class="cta-description">Bergabunglah dengan ribuan pengguna yang sudah merasakan kemudahan sewa alat lokal bersama TrustEquip</p>
                    <div class="cta-buttons">
                        <a href="/register" class="btn btn-primary btn-large">Daftar Sekarang</a>
                        <a href="/login" class="btn btn-outline btn-large">Masuk ke Akun</a>
                    </div>
                    <div class="cta-features">
                        <div class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span>Proses pendaftaran hanya 2 menit</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span>Tersedia untuk kelas, kantor & proyek personal</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span>Garansi uang kembali 100% jika tidak puas</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>TrustEquip</h4>
                <p>Solusi sewa alat terpercaya untuk kebutuhan lokal Anda</p>
            </div>
            <div class="footer-section">
                <h5>Tautan Cepat</h5>
                <ul>
                    <li><a href="#hero">Beranda</a></li>
                    <li><a href="#value">Keuntungan</a></li>
                    <li><a href="/register">Daftar</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h5>Kontak</h5>
                <ul>
                    <li>Email: info@trustequip.id</li>
                    <li>Telepon: +62 800 1234 5678</li>
                    <li>Alamat: Jakarta, Indonesia</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 TrustEquip. Semua hak dilindungi.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="<?php echo e(asset('js/landing-new.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\laragon\www\peminjaman-alat\resources\views/landing.blade.php ENDPATH**/ ?>