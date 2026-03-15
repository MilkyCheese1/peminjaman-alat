<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Peminjaman Alat</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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

            <!-- Other sections will be added here -->
        </main>
    </div>

    <script src="{{ asset('js/dashboard-admin.js') }}"></script>
</body>
</html>
