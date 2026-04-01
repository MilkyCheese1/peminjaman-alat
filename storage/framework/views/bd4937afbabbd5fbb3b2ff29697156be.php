<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustEquip - Sewa Alat Konstruksi & Teknik Mudah</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/landing-fullpage.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/dark-mode.css')); ?>">
</head>
<body class="landing fullpage-scroll">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <div class="brand-icon">🛡️</div>
                <h2 class="brand-text">TrustEquip</h2>
            </div>

            <button class="burger-menu" id="burgerMenu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <ul class="navbar-menu" id="navbarMenu">
                <li><a href="#home">Beranda</a></li>
                <li><a href="#features">Fitur</a></li>
                <li><a href="/register" class="btn-started">Daftar</a></li>
                <li><a href="/login" class="btn-login">Masuk</a></li>
                <li><button class="navbar-dark-mode" id="navbarDarkModeToggle">
                    <span class="sun">☀️</span>
                    <span class="moon">🌙</span>
                </button></li>
            </ul>
        </div>
    </nav>

    <div class="fullpage-container">
        <!-- Main Content -->
        <main class="fullpage-main">
            <!-- Hero Section -->
            <section id="home" class="fullpage-section hero-section active">
                <div class="hero-wrapper">
                    <div class="hero-content">
                        <div class="hero-label">TrustEquip Solutions</div>
                        <h1 class="hero-title">
                            Sewa Alat Berkualitas,<br>
                            <span class="gradient-text">Percaya TrustEquip.</span>
                        </h1>
                        <p class="hero-description">
                            Penyedia sewa peralatan konstruksi, industri, & DIY terpercaya dengan system peminjaman yang aman dan mudah.
                        </p>
                        <div class="hero-buttons">
                            <a href="#features" class="btn btn-primary">Mulai Sewa</a>
                            <a href="#features" class="btn btn-outline">Pelajari Lebih Lanjut</a>
                        </div>
                        <div class="hero-features">
                            <div class="hero-feature-item">
                                <span class="feature-icon">🔧</span>
                                <span class="feature-text">Alat Berkualitas</span>
                            </div>
                            <div class="hero-feature-item">
                                <span class="feature-icon">⚡</span>
                                <span class="feature-text">Proses Cepat</span>
                            </div>
                            <div class="hero-feature-item">
                                <span class="feature-icon">💰</span>
                                <span class="feature-text">Harga Transparan</span>
                            </div>
                        </div>
                    </div>
                    <div class="hero-visual">
                        <div class="visual-placeholder">
                            <div class="neon-accent"></div>
                            <div class="glow-orb"></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section id="features" class="fullpage-section features-section">
                <div class="features-wrapper">
                    <div class="section-header">
                        <h2 class="section-title">Kenapa Pilih TrustEquip?</h2>
                        <p class="section-subtitle">Solusi lengkap untuk kebutuhan sewa peralatan Anda</p>
                    </div>
                    <div class="features-grid">
                        <div class="feature-card">
                            <div class="feature-icon-box">🔒</div>
                            <h3 class="feature-title">Aman & Terpercaya</h3>
                            <p class="feature-description">Semua alat terverifikasi dan terawat dengan baik untuk keamanan Anda</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon-box">📱</div>
                            <h3 class="feature-title">Mudah Digunakan</h3>
                            <p class="feature-description">Aplikasi intuitif untuk booking, tracking, dan pengembalian alat</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon-box">💳</div>
                            <h3 class="feature-title">Pembayaran Fleksibel</h3>
                            <p class="feature-description">Berbagai metode pembayaran dengan harga yang kompetitif dan transparan</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon-box">🚀</div>
                            <h3 class="feature-title">Pengiriman Cepat</h3>
                            <p class="feature-description">Pengiriman gratis untuk area tertentu dengan jaminan tepat waktu</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section id="cta" class="fullpage-section cta-section">
                <div class="cta-wrapper">
                    <div class="cta-content">
                        <h2 class="cta-title">Siap Mulai Menyewa?</h2>
                        <p class="cta-description">
                            Bergabunglah dengan ribuan pelanggan yang puas dan rasakan kemudahan sewa alat berkualitas
                        </p>
                        <div class="cta-buttons">
                            <a href="/register" class="btn btn-primary btn-lg">Daftar Sekarang</a>
                            <a href="/login" class="btn btn-outline btn-lg">Login</a>
                        </div>
                        <div class="cta-stats">
                            <div class="stat-item">
                                <div class="stat-number">5000+</div>
                                <div class="stat-label">Pelanggan Aktif</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">10000+</div>
                                <div class="stat-label">Alat Tersedia</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">150+</div>
                                <div class="stat-label">Area Layanan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Dark Mode Toggle -->
    <button class="dark-mode-toggle" id="darkModeToggle">
        <span class="sun"></span>
        <span class="moon"></span>
    </button>

    <!-- Scroll Indicator -->
    <div class="scroll-indicator">
        <span class="indicator-dot active" data-section="0"></span>
        <span class="indicator-dot" data-section="1"></span>
        <span class="indicator-dot" data-section="2"></span>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2026 TrustEquip. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script src="<?php echo e(asset('js/landing.js')); ?>"></script>
    <script src="<?php echo e(asset('js/fullpage-scroll.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\laragon\www\peminjaman-alat\resources\views/index.blade.php ENDPATH**/ ?>