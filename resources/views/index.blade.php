<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustEquip - Sewa Alat Konstruksi & Teknik Mudah</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body class="landing">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <!-- Logo Section -->
            <div class="navbar-brand">
                <div class="brand-icon">🛡️</div>
                <h2 class="brand-text">TrustEquip</h2>
            </div>

            <!-- Burger Menu for Mobile -->
            <button class="burger-menu" id="burgerMenu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Navigation Menu -->
            <ul class="navbar-menu" id="navbarMenu">
                <li><a href="#home">Home</a></li>
                <li><a href="#equipment">Equipment</a></li>
                <li><a href="#how-it-works">How It Works</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="#contact">Contact Us</a></li>
                <li><a href="/login" class="btn-login">Sign In</a></li>
                <li><a href="/register" class="btn-started">Get Started</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-container">
            <div class="hero-left">
                <h1>Sewa Alat Mudah,<br>Percaya TrustEquip.</h1>
                <p>Penyedia sewa peralatan konstruksi, industri, & DIY terpercaya. Aman, Cepat, dan Hemat Biaya.</p>
                <div class="hero-buttons">
                    <a href="#equipment" class="btn btn-primary">Cari Alat Sekarang</a>
                    <a href="/register" class="btn btn-secondary">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Mengapa TrustEquip Section -->
    <section id="why-trustequip" class="why-section">
        <div class="why-container">
            <h2>Mengapa TrustEquip?</h2>
            <div class="why-grid">
                <div class="why-card">
                    <div class="why-icon">🔧</div>
                    <h3>Alat Berkualitas</h3>
                    <p>Peralatan berkualitas tinggi, terawat, dan siap digunakan untuk berbagai kebutuhan.</p>
                    <a href="#" class="lihat-detail">Lihat Detail ></a>
                </div>
                <div class="why-card">
                    <div class="why-icon">⚡</div>
                    <h3>Proses Mudah</h3>
                    <p>Proses peminjaman yang sederhana dan cepat, tanpa ribet dan penuh kepuasan.</p>
                    <a href="#" class="lihat-detail">Lihat Detail ></a>
                </div>
                <div class="why-card">
                    <div class="why-icon">💰</div>
                    <h3>Harga Transparan</h3>
                    <p>Harga yang jelas tanpa biaya tersembunyi, sehingga Anda bisa merencanakan dengan baik.</p>
                    <a href="#" class="lihat-detail">Lihat Detail ></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Cara Sewa Alat Section -->
    <section id="how-it-works" class="how-section">
        <div class="how-container">
            <h2>Cara Sewa Alat</h2>
            <div class="how-grid">
                <div class="how-step">
                    <div class="step-number">1</div>
                    <div class="step-icon">🔍</div>
                    <h3>Pilih Alat</h3>
                    <p>Temukan alat yang anda perlukan di katalog lengkap kami.</p>
                </div>
                <div class="how-step">
                    <div class="step-number">2</div>
                    <div class="step-icon">📋</div>
                    <h3>Jadwalkan Sewa</h3>
                    <p>Pilih tanggal mulai dan akhir peminjaman sesuai kebutuhan.</p>
                </div>
                <div class="how-step">
                    <div class="step-number">3</div>
                    <div class="step-icon">🚚</div>
                    <h3>Ambil/Kirim Alat</h3>
                    <p>Ambil alat di lokasi kami atau kami antar ke lokasi Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials-section">
        <div class="testimonials-container">
            <h2>Siap Membangun Proyek Anda?</h2>
            <div class="testimonials-carousel">
                <div class="carousel-inner">
                    <div class="testimonial-card carousel-slide active">
                        <p class="testimonial-text">"Layanan TrustEquip sangat membantu untuk proyek kami. Prosesnya mudah, alat berkualitas, dan harga sangat kompetitif!"</p>
                        <div class="testimonial-author">
                            <img src="https://via.placeholder.com/50" alt="Client" class="author-avatar">
                            <div class="author-info">
                                <h4>Budi Santoso</h4>
                                <p>Kontraktor Bangunan</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card carousel-slide">
                        <p class="testimonial-text">"Saya sering meminjam alat di TrustEquip untuk project kecil. Selalu puas dengan kualitas dan pelayanannya. Recommended!"</p>
                        <div class="testimonial-author">
                            <img src="https://via.placeholder.com/50" alt="Client" class="author-avatar">
                            <div class="author-info">
                                <h4>Dewi Lestari</h4>
                                <p>Tukang Renovasi Rumah</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card carousel-slide">
                        <p class="testimonial-text">"Kualitas alat terjamin dan pengiriman sangat cepat. Tim support TrustEquip sangat responsif dan profesional!"</p>
                        <div class="testimonial-author">
                            <img src="https://via.placeholder.com/50" alt="Client" class="author-avatar">
                            <div class="author-info">
                                <h4>Ahmad Riyadi</h4>
                                <p>Pengusaha Konstruksi</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-indicators">
                    <button class="indicator active" data-slide="0"></button>
                    <button class="indicator" data-slide="1"></button>
                    <button class="indicator" data-slide="2"></button>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="cta-section">
                <h3>Siap Membangun Proyek Anda?</h3>
                <a href="/register" class="btn btn-primary btn-lg">Mulai Sekarang</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>TrustEquip</h4>
                <p>Platform terpercaya untuk sewa alat konstruksi dan teknik berkualitas tinggi.</p>
            </div>
            <div class="footer-section">
                <h4>Menu Navigasi</h4>
                <ul class="footer-links">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#equipment">Equipment</a></li>
                    <li><a href="#how-it-works">How It Works</a></li>
                    <li><a href="#pricing">Pricing</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Kontak Kami</h4>
                <ul class="footer-links">
                    <li>Email: support@trustequip.com</li>
                    <li>Telepon: +62 812 3456 7890</li>
                    <li>Alamat: Jl. Teknologi No. 1, Jakarta</li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Ikuti Kami</h4>
                <div class="social-links">
                    <a href="#" class="social-icon">f</a>
                    <a href="#" class="social-icon">t</a>
                    <a href="#" class="social-icon">ig</a>
                    <a href="#" class="social-icon">in</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 TrustEquip. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/landing.js') }}"></script>
</body>
</html>
