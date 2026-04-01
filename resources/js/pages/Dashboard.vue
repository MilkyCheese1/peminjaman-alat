<template>
  <div class="dashboard-container">
    <!-- Header -->
    <header class="dashboard-header">
      <div class="header-container">
        <div class="logo">🎓 TrustEquip Dashboard</div>
        <div class="user-menu">
          <button class="notification-bell" @click="toggleNotifications">
            🔔
            <span v-if="unreadNotifications > 0" class="notification-badge">{{ unreadNotifications }}</span>
          </button>
          <div v-if="showNotifications" class="notifications-dropdown">
            <div class="notifications-header">
              <h3>Notifikasi</h3>
              <button @click="clearAllNotifications" class="clear-btn">Hapus Semua</button>
            </div>
            <div class="notifications-list">
              <div v-for="notif in notifications" :key="notif.id" class="notification-item" :class="{ unread: !notif.read }">
                <div class="notif-icon">{{ notif.icon }}</div>
                <div class="notif-content">
                  <p class="notif-title">{{ notif.title }}</p>
                  <p class="notif-message">{{ notif.message }}</p>
                  <p class="notif-time">{{ notif.time }}</p>
                </div>
              </div>
            </div>
          </div>
          <span class="user-name">{{ userName }}</span>
          <button @click="handleLogout" class="logout-button">
            Keluar
          </button>
        </div>
      </div>
    </header>

    <!-- Navigation Tabs -->
    <nav class="dashboard-nav">
      <div class="nav-container">
        <button v-for="tab in tabs" :key="tab.id" 
                :class="['nav-tab', { active: activeTab === tab.id }]"
                @click="activeTab = tab.id">
          {{ tab.icon }} {{ tab.label }}
        </button>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="dashboard-content">
      <div class="welcome-section">
        <h1>Selamat datang, {{ userName }}!</h1>
        <p>Kelola peminjaman alat Anda dengan mudah</p>
      </div>

      <div class="dashboard-grid">
        <!-- Card: Alat Dipinjam -->
        <div class="dashboard-card">
          <div class="card-icon">📦</div>
          <h3>Alat Dipinjam</h3>
          <p class="card-number">{{ stats.borrowed }}</p>
          <a href="#" class="card-link">Lihat detail →</a>
        </div>

        <!-- Card: Alat Tersedia -->
        <div class="dashboard-card">
          <div class="card-icon">✅</div>
          <h3>Alat Tersedia</h3>
          <p class="card-number">{{ stats.available }}</p>
          <a href="#" class="card-link">Jelajahi →</a>
        </div>

        <!-- Card: Pengembalian Tertunda -->
        <div class="dashboard-card warning">
          <div class="card-icon">⏰</div>
          <h3>Pengembalian Tertunda</h3>
          <p class="card-number">{{ stats.overdue }}</p>
          <a href="#" class="card-link">Perpanjang →</a>
        </div>

        <!-- Card: Riwayat Transaksi -->
        <div class="dashboard-card">
          <div class="card-icon">📋</div>
          <h3>Riwayat Transaksi</h3>
          <p class="card-number">{{ stats.transactions }}</p>
          <a href="#" class="card-link">Lihat →</a>
        </div>
      </div>

      <!-- Recent Borrowings -->
      <section class="recent-section">
        <h2>Peminjaman Terbaru</h2>
        <table class="borrowing-table">
          <thead>
            <tr>
              <th>Alat</th>
              <th>Tanggal Peminjaman</th>
              <th>Tanggal Pengembalian</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in recentBorrowings" :key="item.id">
              <td>{{ item.tool }}</td>
              <td>{{ item.borrowDate }}</td>
              <td>{{ item.returnDate }}</td>
              <td>
                <span class="status-badge" :class="item.status">
                  {{ item.statusText }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </section>

      <!-- Quick Actions -->
      <section class="quick-actions">
        <h2>Aksi Cepat</h2>
        <div class="actions-grid">
          <button class="action-button">
            <span class="icon">➕</span>
            Pinjam Alat Baru
          </button>
          <button class="action-button">
            <span class="icon">📝</span>
            Perpanjang Peminjaman
          </button>
          <button class="action-button">
            <span class="icon">⚙️</span>
            Pengaturan Profil
          </button>
          <button class="action-button">
            <span class="icon">❓</span>
            Bantuan & FAQ
          </button>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const userName = ref('User')

const stats = ref({
  borrowed: 3,
  available: 152,
  overdue: 1,
  transactions: 24
})

const recentBorrowings = ref([
  {
    id: 1,
    tool: 'Laptop Dell XPS 15',
    borrowDate: '29 Maret 2026',
    returnDate: '02 April 2026',
    status: 'active',
    statusText: 'Sedang Dipinjam'
  },
  {
    id: 2,
    tool: 'Proyektor 4K',
    borrowDate: '27 Maret 2026',
    returnDate: '30 Maret 2026',
    status: 'returned',
    statusText: 'Sudah Dikembalikan'
  },
  {
    id: 3,
    tool: 'Kamera DSLR Canon 6D',
    borrowDate: '25 Maret 2026',
    returnDate: '31 Maret 2026',
    status: 'overdue',
    statusText: 'Melewati Batas'
  }
])

onMounted(() => {
  // Ambil nama user dari localStorage
  const userStr = localStorage.getItem('user')
  if (userStr) {
    try {
      const user = JSON.parse(userStr)
      userName.value = user.email.split('@')[0] || 'User'
    } catch (e) {
      userName.value = 'User'
    }
  }
})

const handleLogout = () => {
  localStorage.removeItem('user')
  router.push('/login')
}
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.dashboard-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #f8f9fa 0%, #f0f2f5 100%);
}

.dashboard-header {
  background: linear-gradient(135deg, #0B7285 0%, #089FB3 100%);
  color: white;
  padding: 20px 0;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo {
  font-size: 1.3rem;
  font-weight: 700;
}

.user-menu {
  display: flex;
  align-items: center;
  gap: 20px;
}

.user-name {
  font-weight: 500;
}

.logout-button {
  background: rgba(255, 255, 255, 0.2);
  color: white;
  border: 2px solid white;
  padding: 8px 20px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
}

.logout-button:hover {
  background: white;
  color: #0B7285;
}

.dashboard-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 40px 20px;
}

.welcome-section {
  margin-bottom: 40px;
  animation: slideUp 0.6s ease-out;
}

.welcome-section h1 {
  font-size: 2rem;
  color: #1a1a2e;
  margin-bottom: 10px;
}

.welcome-section p {
  font-size: 1.1rem;
  color: #666;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 40px;
}

.dashboard-card {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  border-left: 4px solid #0B7285;
  animation: slideUp 0.6s ease-out;
}

.dashboard-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
}

.dashboard-card.warning {
  border-left-color: #FF9F1C;
}

.card-icon {
  font-size: 2.5rem;
  margin-bottom: 15px;
}

.dashboard-card h3 {
  color: #1a1a2e;
  margin-bottom: 10px;
  font-size: 1rem;
}

.card-number {
  font-size: 2rem;
  font-weight: 700;
  color: #0B7285;
  margin-bottom: 15px;
}

.card-link {
  color: #0B7285;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease;
}

.card-link:hover {
  color: #089FB3;
}

.recent-section {
  background: white;
  border-radius: 12px;
  padding: 25px;
  margin-bottom: 40px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  animation: slideUp 0.6s ease-out;
  animation-delay: 0.1s;
}

.recent-section h2 {
  color: #1a1a2e;
  margin-bottom: 20px;
  font-size: 1.3rem;
}

.borrowing-table {
  width: 100%;
  border-collapse: collapse;
}

.borrowing-table th {
  background: #f8f9fa;
  color: #666;
  padding: 12px;
  text-align: left;
  font-weight: 600;
  border-bottom: 2px solid #e5e5e5;
}

.borrowing-table td {
  padding: 12px;
  border-bottom: 1px solid #e5e5e5;
  color: #666;
}

.borrowing-table tbody tr:hover {
  background: #f8f9fa;
}

.status-badge {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
}

.status-badge.active {
  background: #d4edda;
  color: #155724;
}

.status-badge.returned {
  background: #cce5ff;
  color: #004085;
}

.status-badge.overdue {
  background: #f8d7da;
  color: #721c24;
}

.quick-actions {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  animation: slideUp 0.6s ease-out;
  animation-delay: 0.2s;
}

.quick-actions h2 {
  color: #1a1a2e;
  margin-bottom: 20px;
  font-size: 1.3rem;
}

.actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
}

.action-button {
  background: linear-gradient(135deg, #0B7285 0%, #089FB3 100%);
  color: white;
  border: none;
  padding: 15px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
}

.action-button:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 20px rgba(11, 114, 133, 0.3);
}

.icon {
  font-size: 1.5rem;
}

@media (max-width: 768px) {
  .dashboard-content {
    padding: 20px 15px;
  }

  .welcome-section h1 {
    font-size: 1.5rem;
  }

  .dashboard-grid {
    grid-template-columns: 1fr;
  }

  .borrowing-table {
    font-size: 0.9rem;
  }

  .borrowing-table th,
  .borrowing-table td {
    padding: 8px;
  }

  .header-container {
    flex-direction: column;
    gap: 15px;
  }

  .user-menu {
    width: 100%;
    justify-content: space-between;
  }
}
</style>
