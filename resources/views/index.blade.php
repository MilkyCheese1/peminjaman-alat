<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustEquip - Pinjam Alat Konstruksi & Teknik Cepat & Mudah</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body class="landing">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <div class="brand-logo">🛡️</div>
                <h2>TrustEquip</h2>
            </div>
            <button class="burger-menu" id="burgerMenu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="navbar-menu" id="navbarMenu">
                <li><a href="#home">Beranda</a></li>
                <li><a href="#keunggulan">Keunggulan</a></li>
                <li><a href="#kategori">Kategori</a></li>
                <li><a href="/login" class="btn-nav">Login</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>PINJAM ALAT KONSTRUKSI & TEKNIK CEPAT & MUDAH</h1>
            <p>TrustEquip menyediakan peralatan berkualitas tinggi untuk proyek Anda. Proses aman, harga transparan.</p>
            <a href="/login" class="btn btn-cta">MULAI PEMINJAMAN SEKARANG →</a>
        </div>
        <div class="hero-image">
            <div class="placeholder-image">👨‍🔧</div>
        </div>
    </section>

    <!-- Keunggulan Section -->
    <section id="keunggulan" class="keunggulan">
        <h2>KEUNGGULAN KAMI</h2>
        <div class="keunggulan-grid">
            <div class="keunggulan-card">
                <div class="keunggulan-icon">🔧</div>
                <h3>Alat Terawat</h3>
                <p>Semua alat dalam kondisi prima dan terawat dengan baik</p>
            </div>
            <div class="keunggulan-card">
                <div class="keunggulan-icon">⏱️</div>
                <h3>Proses Cepat</h3>
                <p>Peminjaman dan pengembalian dilakukan dengan cepat dan mudah</p>
            </div>
            <div class="keunggulan-card">
                <div class="keunggulan-icon">📞</div>
                <h3>Dukungan 24/7</h3>
                <p>Tim support kami siap membantu kapan pun Anda membutuhkan</p>
            </div>
        </div>
    </section>

    <!-- Kategori Alat Section -->
    <section id="kategori" class="kategori">
        <h2>KATEGORI ALAT POPULER</h2>
        <div class="kategori-container">
            <div class="kategori-grid" id="kategoriGrid">
                <div class="kategori-card">
                    <div class="kategori-image">🔨</div>
                    <h3>Bor Listrik</h3>
                    <p>Peralatan berkualitas untuk pekerjaan presisi</p>
                </div>
                <div class="kategori-card">
                    <div class="kategori-image">🪚</div>
                    <h3>Gergaji Listrik</h3>
                    <p>Potong material dengan cepat dan akurat</p>
                </div>
                <div class="kategori-card">
                    <div class="kategori-image">⚡</div>
                    <h3>Generator</h3>
                    <p>Sumber tenaga listrik mobile untuk lapangan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>TrustEquip</h4>
                <p>Platform terpercaya untuk pinjam alat konstruksi dan teknik berkualitas.</p>
            </div>
            <div class="footer-section">
                <h4>Menu</h4>
                <ul>
                    <li><a href="#home">Beranda</a></li>
                    <li><a href="#keunggulan">Keunggulan</a></li>
                    <li><a href="#kategori">Kategori</a></li>
                    <li><a href="/login">Login</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Kontak</h4>
                <ul>
                    <li>Email: info@trustequip.com</li>
                    <li>Telepon: +62 123 4567 8900</li>
                    <li>Alamat: Jl. Teknologi No. 1</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 TrustEquip. Semua hak cipta dilindungi.</p>
        </div>
    </footer>

    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/landing.js') }}"></script>
</body>
</html>
