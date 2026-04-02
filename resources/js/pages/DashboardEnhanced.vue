<template>
  <div class="dashboard-wrapper">
    <!-- Header -->
    <header class="dash-header">
      <div class="header-top">
        <div class="logo-section">
          <div class="logo-icon">🎓</div>
          <div class="logo-text">
            <h1>TrustEquip</h1>
            <p>Dashboard Pengguna</p>
          </div>
        </div>
        
        <div class="header-actions">
          <!-- Notifications -->
          <div class="notification-container">
            <button class="notif-btn" @click="showNotifications = !showNotifications">
              🔔
              <span class="notif-badge" v-if="unreadCount > 0">{{ unreadCount }}</span>
            </button>
            <div v-if="showNotifications" class="notif-dropdown">
              <div class="notif-header">
                <h3>Notifikasi ({{ notifications.length }})</h3>
                <button @click="clearNotifications" class="clear-btn">Hapus Semua</button>
              </div>
              <div class="notif-items">
                <div v-for="notif in notifications" :key="notif.id" class="notif-item" :class="{ unread: !notif.read }">
                  <div class="notif-icon">{{ notif.icon }}</div>
                  <div class="notif-text">
                    <p class="notif-title">{{ notif.title }}</p>
                    <p class="notif-desc">{{ notif.desc }}</p>
                    <p class="notif-time">{{ notif.time }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Search -->
          <div class="search-box">
            <input type="text" placeholder="Cari alat...">
            <button>🔍</button>
          </div>

          <!-- User Menu -->
          <div class="user-section">
            <div class="user-info">
              <div class="user-avatar">{{ userInitial }}</div>
              <div class="user-details">
                <p class="user-name">{{ userName }}</p>
                <p class="user-role">Peminjam</p>
              </div>
            </div>
          </div>

          <!-- Logout Button - Top Right -->
          <button @click="logout" class="logout-btn" title="Logout">
            Keluar
          </button>
        </div>
      </div>

      <!-- Navigation Tabs -->
      <nav class="dash-nav">
        <button v-for="tab in tabs" :key="tab.id"
                :class="['tab-btn', { active: activeTab === tab.id }]"
                @click="activeTab = tab.id">
          {{ tab.icon }} {{ tab.label }}
        </button>
      </nav>
    </header>

    <!-- Main Content -->
    <main class="dash-main">
      <!-- TAB 1: OVERVIEW -->
      <section v-if="activeTab === 'overview'" class="tab-content">
        <div class="welcome-card">
          <h2>Selamat datang kembali, {{ userName }}! 👋</h2>
          <p>Anda memiliki {{ stats.active }} alat yang sedang dipinjam</p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-info">
              <p class="stat-label">Alat Dipinjam</p>
              <p class="stat-value">{{ stats.active }}</p>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-info">
              <p class="stat-label">Tersedia untuk Dipinjam</p>
              <p class="stat-value">{{ stats.available }}</p>
            </div>
          </div>

          <div class="stat-card warning">
            <div class="stat-icon">⏰</div>
            <div class="stat-info">
              <p class="stat-label">Pengembalian Tertunda</p>
              <p class="stat-value">{{ stats.overdue }}</p>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-info">
              <p class="stat-label">Riwayat Peminjaman</p>
              <p class="stat-value">{{ stats.history }}</p>
            </div>
          </div>
        </div>

        <!-- Active Borrowings -->
        <section class="section-card">
          <div class="section-header">
            <h3>📋 Peminjaman Aktif</h3>
            <a href="#" class="view-all">Lihat Semua</a>
          </div>
          <div v-if="activeBorrowings.length > 0" class="borrowing-list">
            <div v-for="item in activeBorrowings" :key="item.id" class="borrow-item">
              <div class="borrow-icon">{{ item.icon }}</div>
              <div class="borrow-info">
                <h4>{{ item.name }}</h4>
                <p class="borrow-dates">📅 {{ item.borrowDate }} - {{ item.dueDate }}</p>
                <p class="borrow-owner">👤 {{ item.owner }}</p>
              </div>
              <div class="borrow-actions">
                <button class="btn-small primary">Perpanjang</button>
                <button class="btn-small secondary">Detail</button>
              </div>
            </div>
          </div>
          <div v-else class="empty-state">
            <p>Anda belum meminjam alat. <a href="#">Jelajahi alat sekarang →</a></p>
          </div>
        </section>

        <!-- Recent Activity -->
        <section class="section-card">
          <div class="section-header">
            <h3>⚡ Aktivitas Terbaru</h3>
          </div>
          <div class="activity-timeline">
            <div v-for="activity in activities" :key="activity.id" class="activity-item">
              <div class="activity-dot"></div>
              <div class="activity-content">
                <h4>{{ activity.title }}</h4>
                <p>{{ activity.desc }}</p>
                <p class="activity-time">{{ activity.time }}</p>
              </div>
            </div>
          </div>
        </section>

        <!-- Quick Actions -->
        <section class="section-card">
          <div class="section-header">
            <h3>⚡ Aksi Cepat</h3>
          </div>
          <div class="quick-actions">
            <button class="action-btn">
              <span class="icon">➕</span>
              <span>Pinjam Alat</span>
            </button>
            <button class="action-btn">
              <span class="icon">📝</span>
              <span>Perpanjang</span>
            </button>
            <button class="action-btn">
              <span class="icon">📊</span>
              <span>Laporan</span>
            </button>
            <button class="action-btn">
              <span class="icon">❓</span>
              <span>Bantuan</span>
            </button>
          </div>
        </section>
      </section>

      <!-- TAB 2: PEMINJAMAN -->
      <section v-if="activeTab === 'borrowings'" class="tab-content">
        <div class="section-card">
          <div class="section-header">
            <h3>📦 Riwayat Peminjaman Lengkap</h3>
            <div class="filter-group">
              <button v-for="filter in filters" :key="filter"
                      :class="['filter-btn', { active: activeFilter === filter }]"
                      @click="activeFilter = filter">
                {{ filter }}
              </button>
            </div>
          </div>
          <table class="table-borrowings">
            <thead>
              <tr>
                <th>Alat</th>
                <th>Tanggal Peminjaman</th>
                <th>Tanggal Kembali</th>
                <th>Durasi</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="borrow in filteredBorrowings" :key="borrow.id">
                <td>
                  <div class="item-name">
                    <span class="item-icon">{{ borrow.icon }}</span>
                    {{ borrow.name }}
                  </div>
                </td>
                <td>{{ borrow.borrowDate }}</td>
                <td>{{ borrow.dueDate }}</td>
                <td>{{ borrow.duration }} hari</td>
                <td>
                  <span class="status-badge" :class="borrow.status">
                    {{ borrow.statusText }}
                  </span>
                </td>
                <td>
                  <button class="btn-action">Detail</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- TAB 3: PROFIL -->
      <section v-if="activeTab === 'profile'" class="tab-content">
        <div class="profile-layout">
          <!-- Profile Card -->
          <div class="profile-card main-profile">
            <div class="profile-header">
              <div class="profile-avatar-large">{{ userInitial }}</div>
              <h2>{{ userName }}</h2>
              <p class="profile-role">Peminjam</p>
              <p class="profile-member">Member sejak {{ memberSince }}</p>
            </div>

            <div class="profile-stats">
              <div class="profile-stat">
                <p class="stat-num">{{ stats.history }}</p>
                <p class="stat-name">Peminjaman</p>
              </div>
              <div class="profile-stat">
                <p class="stat-num">{{ stats.active }}</p>
                <p class="stat-name">Aktif</p>
              </div>
              <div class="profile-stat">
                <p class="stat-num">4.8/5</p>
                <p class="stat-name">Rating</p>
              </div>
            </div>

            <button class="btn-large primary" @click="editMode = !editMode">
              {{ editMode ? 'Batal' : '✏️ Edit Profil' }}
            </button>
          </div>

          <!-- Edit Form -->
          <div v-if="editMode" class="profile-card form-card">
            <h3>Edit Informasi Profil</h3>
            <form @submit.prevent="saveProfile">
              <div class="form-group">
                <label>Nama Lengkap</label>
                <input v-model="profileForm.fullname" type="text" placeholder="Nama lengkap">
              </div>
              <div class="form-group">
                <label>Email</label>
                <input v-model="profileForm.email" type="email" placeholder="Email">
              </div>
              <div class="form-group">
                <label>Nomor Telepon</label>
                <input v-model="profileForm.phone" type="tel" placeholder="Nomor telepon">
              </div>
              <div class="form-group">
                <label>Institusi/Sekolah</label>
                <input v-model="profileForm.school" type="text" placeholder="Nama sekolah">
              </div>
              <div class="form-group">
                <label>Alamat</label>
                <textarea v-model="profileForm.address" placeholder="Alamat lengkap"></textarea>
              </div>
              <button type="submit" class="btn-large primary">💾 Simpan Perubahan</button>
            </form>
          </div>

          <!-- Info Card -->
          <div class="profile-card">
            <h3>ℹ️ Informasi Akun</h3>
            <div class="info-group">
              <p><strong>Email:</strong> {{ profileForm.email }}</p>
              <p><strong>Telepon:</strong> {{ profileForm.phone || 'Belum diisi' }}</p>
              <p><strong>Sekolah:</strong> {{ profileForm.school || 'Belum diisi' }}</p>
              <p><strong>Member Sejak:</strong> {{ memberSince }}</p>
            </div>
          </div>

          <!-- Settings Card -->
          <div class="profile-card">
            <h3>⚙️ Pengaturan Keamanan</h3>
            <div class="settings-group">
              <div class="setting-item">
                <div class="setting-info">
                  <p class="setting-title">Ubah Password</p>
                  <p class="setting-desc">Perbarui password akun Anda</p>
                </div>
                <button class="btn-small secondary">Ubah</button>
              </div>
              <div class="setting-item">
                <div class="setting-info">
                  <p class="setting-title">Verifikasi Email</p>
                  <p class="setting-desc">Email belum diverifikasi</p>
                </div>
                <button class="btn-small secondary">Verifikasi</button>
              </div>
              <div class="setting-item">
                <div class="setting-info">
                  <p class="setting-title">Login Terakhir</p>
                  <p class="setting-desc">1 jam yang lalu</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- TAB 4: BANTUAN -->
      <section v-if="activeTab === 'help'" class="tab-content">
        <div class="section-card">
          <h3>❓ Pusat Bantuan</h3>
          
          <!-- FAQ -->
          <div class="help-section">
            <h4>Pertanyaan yang Sering Diajukan</h4>
            <div class="faq-list">
              <div v-for="faq in faqs" :key="faq.id" class="faq-item">
                <button class="faq-question" @click="faq.expanded = !faq.expanded">
                  <span>{{ faq.question }}</span>
                  <span class="faq-icon">{{ faq.expanded ? '▼' : '▶' }}</span>
                </button>
                <div v-if="faq.expanded" class="faq-answer">
                  {{ faq.answer }}
                </div>
              </div>
            </div>
          </div>

          <!-- Contact -->
          <div class="help-section">
            <h4>Hubungi Kami</h4>
            <div class="contact-options">
              <div class="contact-item">
                <p class="contact-method">📧 Email</p>
                <p>support@trustequip.id</p>
              </div>
              <div class="contact-item">
                <p class="contact-method">📱 WhatsApp</p>
                <p>+62 812-3456-7890</p>
              </div>
              <div class="contact-item">
                <p class="contact-method">💬 Live Chat</p>
                <button class="btn-small primary">Mulai Chat</button>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// State
const activeTab = ref('overview')
const activeFilter = ref('Semua')
const showNotifications = ref(false)
const editMode = ref(false)
const userName = ref('User')
const userInitial = ref('U')
const memberSince = ref('01 Januari 2026')

// Tabs
const tabs = ref([
  { id: 'overview', label: 'Beranda', icon: '🏠' },
  { id: 'borrowings', label: 'Peminjaman', icon: '📦' },
  { id: 'profile', label: 'Profil', icon: '👤' },
  { id: 'help', label: 'Bantuan', icon: '❓' }
])

// Stats
const stats = ref({
  active: 3,
  available: 152,
  overdue: 1,
  history: 24
})

// Filters
const filters = ref(['Semua', 'Aktif', 'Selesai', 'Tertunda'])

// Profile Form
const profileForm = ref({
  fullname: 'Ahmad Khoirulloh',
  email: 'ahmad@school.id',
  phone: '081234567890',
  school: 'SMA Negeri 1',
  address: 'Jl. Pendidikan No. 1'
})

// Notifications
const notifications = ref([
  {
    id: 1,
    icon: '📦',
    title: 'Siap Diambil',
    desc: 'Laptop Dell XPS siap untuk diambil',
    time: '2 jam yang lalu',
    read: false
  },
  {
    id: 2,
    icon: '⏰',
    title: 'Pengembalian Tertunda',
    desc: 'Kamera DSLR Canon harus dikembalikan hari ini',
    time: '5 jam yang lalu',
    read: false
  },
  {
    id: 3,
    icon: '✅',
    title: 'Peminjaman Dikonfirmasi',
    desc: 'Proyektor 4K telah dikonfirmasi',
    time: '1 hari yang lalu',
    read: true
  }
])

const unreadCount = computed(() => notifications.value.filter(n => !n.read).length)

// Active Borrowings
const activeBorrowings = ref([
  {
    id: 1,
    name: 'Laptop Dell XPS 15',
    borrowDate: '29 Mar 2026',
    dueDate: '02 Apr 2026',
    owner: 'Pak Bambang',
    icon: '💻',
    status: 'active'
  },
  {
    id: 2,
    name: 'Kamera DSLR Canon 6D',
    borrowDate: '25 Mar 2026',
    dueDate: '31 Mar 2026',
    owner: 'Ibu Diana',
    icon: '📷',
    status: 'overdue'
  }
])

// All Borrowings
const allBorrowings = ref([
  {
    id: 1,
    name: 'Laptop Dell XPS 15',
    borrowDate: '29 Mar 2026',
    dueDate: '02 Apr 2026',
    duration: 4,
    icon: '💻',
    status: 'active',
    statusText: 'Sedang Dipinjam'
  },
  {
    id: 2,
    name: 'Kamera DSLR Canon 6D',
    borrowDate: '25 Mar 2026',
    dueDate: '31 Mar 2026',
    duration: 6,
    icon: '📷',
    status: 'overdue',
    statusText: 'Melewati Batas'
  },
  {
    id: 3,
    name: 'Proyektor 4K',
    borrowDate: '27 Mar 2026',
    dueDate: '30 Mar 2026',
    duration: 3,
    icon: '🎬',
    status: 'returned',
    statusText: 'Sudah Dikembalikan'
  },
  {
    id: 4,
    name: 'Microphone Studio',
    borrowDate: '20 Mar 2026',
    dueDate: '25 Mar 2026',
    duration: 5,
    icon: '🎙️',
    status: 'returned',
    statusText: 'Sudah Dikembalikan'
  }
])

const filteredBorrowings = computed(() => {
  if (activeFilter.value === 'Semua') return allBorrowings.value
  if (activeFilter.value === 'Aktif') return allBorrowings.value.filter(b => b.status === 'active')
  if (activeFilter.value === 'Selesai') return allBorrowings.value.filter(b => b.status === 'returned')
  if (activeFilter.value === 'Tertunda') return allBorrowings.value.filter(b => b.status === 'overdue')
  return allBorrowings.value
})

// Activities
const activities = ref([
  {
    id: 1,
    icon: '📦',
    title: 'Peminjaman Baru',
    desc: 'Anda meminjam Laptop Dell XPS 15',
    time: '29 Mar 2026, 10:30'
  },
  {
    id: 2,
    icon: '⏰',
    title: 'Pengembalian Tertunda',
    desc: 'Kamera DSLR Canon 6D telah melewati batas pengembalian',
    time: '31 Mar 2026, 23:59'
  },
  {
    id: 3,
    icon: '✅',
    title: 'Barang Dikembalikan',
    desc: 'Proyektor 4K berhasil dikembalikan',
    time: '30 Mar 2026, 14:15'
  }
])

// FAQs
const faqs = ref([
  {
    id: 1,
    question: 'Berapa lama saya bisa meminjam alat?',
    answer: 'Durasi peminjaman tergantung pada jenis alat. Umumnya 3-7 hari, dan dapat diperpanjang jika tersedia.',
    expanded: false
  },
  {
    id: 2,
    question: 'Bagaimana cara memperpanjang peminjaman?',
    answer: 'Anda dapat memperpanjang melalui dashboard dengan meneklik tombol "Perpanjang" pada alat yang sedang dipinjam.',
    expanded: false
  },
  {
    id: 3,
    question: 'Apa yang terjadi jika saya terlambat mengembalikan?',
    answer: 'Denda akan dikenakan sesuai dengan durasi keterlambatan. Tim kami akan menghubungi Anda.',
    expanded: false
  },
  {
    id: 4,
    question: 'Bagaimana jika alat rusak?',
    answer: 'Segera laporkan kerusakan kepada tim support kami melalui chat atau email dengan foto dokumentasi.',
    expanded: false
  }
])

// Methods
const clearNotifications = () => {
  notifications.value = []
}

const logout = () => {
  localStorage.removeItem('user')
  router.push('/login')
}

const saveProfile = () => {
  alert('Profil berhasil diperbarui!')
  editMode.value = false
}

// Lifecycle
onMounted(() => {
  const userStr = localStorage.getItem('user')
  if (userStr) {
    try {
      const user = JSON.parse(userStr)
      const email = user.email || 'user@email.com'
      userName.value = email.split('@')[0] || 'User'
      userInitial.value = userName.value.charAt(0).toUpperCase()
      profileForm.value.email = email
    } catch (e) {
      userName.value = 'User'
    }
  }
})
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

:root {
  --primary: #0B7285;
  --primary-light: #089FB3;
  --accent: #FF9F1C;
  --text-primary: #1a1a2e;
  --text-secondary: #666;
  --bg-light: #f8f9fa;
  --border: #e0e0e0;
  --success: #10b981;
  --warning: #f59e0b;
  --error: #ef4444;
}

.dashboard-wrapper {
  min-height: 100vh;
  background: var(--bg-light);
}

/* ===== HEADER ===== */
.dash-header {
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  color: white;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-top {
  max-width: 1400px;
  margin: 0 auto;
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 30px;
  position: relative;
}

.logo-section {
  display: flex;
  align-items: center;
  gap: 15px;
}

.logo-icon {
  font-size: 2.5rem;
}

.logo-text h1 {
  font-size: 1.5rem;
  margin: 0;
}

.logo-text p {
  font-size: 0.85rem;
  opacity: 0.9;
  margin: 0;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 20px;
  flex: 1;
}

.notification-container {
  position: relative;
}

.notif-btn {
  background: rgba(255, 255, 255, 0.2);
  border: none;
  color: white;
  font-size: 1.3rem;
  cursor: pointer;
  padding: 8px 12px;
  border-radius: 6px;
  position: relative;
  transition: all 0.3s ease;
}

.notif-btn:hover {
  background: rgba(255, 255, 255, 0.3);
}

.notif-badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background: var(--accent);
  color: white;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.7rem;
  font-weight: bold;
}

.notif-dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  background: white;
  color: var(--text-primary);
  width: 350px;
  border-radius: 8px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  z-index: 1000;
  margin-top: 10px;
  max-height: 400px;
  display: flex;
  flex-direction: column;
}

.notif-header {
  padding: 15px;
  border-bottom: 1px solid var(--border);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.notif-header h3 {
  margin: 0;
  font-size: 1rem;
}

.clear-btn {
  background: none;
  border: none;
  color: var(--primary);
  cursor: pointer;
  font-size: 0.85rem;
  text-decoration: underline;
}

.notif-items {
  overflow-y: auto;
  max-height: 300px;
}

.notif-item {
  padding: 15px;
  border-bottom: 1px solid var(--border);
  display: flex;
  gap: 12px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.notif-item.unread {
  background: rgba(11, 114, 133, 0.05);
}

.notif-item:hover {
  background: var(--bg-light);
}

.notif-icon {
  font-size: 1.5rem;
  flex-shrink: 0;
}

.notif-text {
  flex: 1;
}

.notif-title {
  margin: 0 0 5px 0;
  font-weight: 600;
  font-size: 0.95rem;
}

.notif-desc {
  margin: 0 0 5px 0;
  font-size: 0.85rem;
  color: var(--text-secondary);
}

.notif-time {
  margin: 0;
  font-size: 0.75rem;
  color: #999;
}

.search-box {
  display: flex;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 6px;
  overflow: hidden;
}

.search-box input {
  background: none;
  border: none;
  color: white;
  padding: 8px 12px;
  width: 250px;
  outline: none;
}

.search-box input::placeholder {
  color: rgba(255, 255, 255, 0.7);
}

.search-box button {
  background: none;
  border: none;
  color: white;
  cursor: pointer;
  padding: 8px 12px;
}

.user-section {
  display: flex;
  align-items: center;
  gap: 15px;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.user-avatar {
  width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
}

.user-details p {
  margin: 0;
  font-size: 0.9rem;
}

.user-name {
  font-weight: 600;
}

.user-role {
  font-size: 0.8rem;
  opacity: 0.9;
}

.logout-btn {
  background: #dc3545;
  border: none;
  color: white;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  padding: 10px 24px;
  border-radius: 6px;
  transition: all 0.3s ease;
  position: absolute;
  top: 20px;
  right: 20px;
}

.logout-btn:hover {
  background: #c82333;
  transform: scale(1.05);
}

/* ===== NAVIGATION ===== */
.dash-nav {
  background: rgba(0, 0, 0, 0.1);
  padding: 0;
  display: flex;
  max-width: 1400px;
  margin: 0 auto;
}

.tab-btn {
  background: none;
  border: none;
  color: rgba(255, 255, 255, 0.7);
  padding: 15px 20px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
  border-bottom: 3px solid transparent;
}

.tab-btn:hover {
  color: white;
}

.tab-btn.active {
  color: white;
  border-bottom-color: var(--accent);
}

/* ===== MAIN CONTENT ===== */
.dash-main {
  max-width: 1400px;
  margin: 0 auto;
  padding: 30px 20px;
}

.tab-content {
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.welcome-card {
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  color: white;
  padding: 30px;
  border-radius: 12px;
  margin-bottom: 30px;
}

.welcome-card h2 {
  margin: 0 0 10px 0;
  font-size: 1.8rem;
}

.welcome-card p {
  margin: 0;
  opacity: 0.95;
}

/* ===== STATS GRID ===== */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.stat-card {
  background: white;
  border-radius: 12px;
  padding: 25px;
  display: flex;
  align-items: center;
  gap: 20px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.stat-card.warning {
  border-left: 4px solid var(--warning);
}

.stat-icon {
  font-size: 2.5rem;
}

.stat-info {
  flex: 1;
}

.stat-label {
  margin: 0;
  color: var(--text-secondary);
  font-size: 0.9rem;
}

.stat-value {
  margin: 5px 0 0 0;
  font-size: 2rem;
  font-weight: 700;
  color: var(--primary);
}

/* ===== SECTION CARD ===== */
.section-card {
  background: white;
  border-radius: 12px;
  padding: 30px;
  margin-bottom: 30px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 2px solid var(--border);
}

.section-header h3 {
  margin: 0;
  color: var(--text-primary);
  font-size: 1.3rem;
}

.view-all {
  color: var(--primary);
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease;
}

.view-all:hover {
  color: var(--primary-light);
}

.filter-group {
  display: flex;
  gap: 10px;
}

.filter-btn {
  background: var(--bg-light);
  border: 2px solid var(--border);
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
}

.filter-btn:hover {
  border-color: var(--primary);
  color: var(--primary);
}

.filter-btn.active {
  background: var(--primary);
  color: white;
  border-color: var(--primary);
}

/* ===== BORROWING LIST ===== */
.borrowing-list {
  display: space-y;
}

.borrow-item {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 20px;
  background: var(--bg-light);
  border-radius: 8px;
  margin-bottom: 15px;
  transition: all 0.3s ease;
}

.borrow-item:hover {
  background: #f0f2f5;
}

.borrow-icon {
  font-size: 2.5rem;
}

.borrow-info {
  flex: 1;
}

.borrow-info h4 {
  margin: 0 0 8px 0;
  color: var(--text-primary);
}

.borrow-dates,
.borrow-owner {
  margin: 5px 0;
  font-size: 0.9rem;
  color: var(--text-secondary);
}

.borrow-actions {
  display: flex;
  gap: 10px;
}

/* ===== TABLE ===== */
.table-borrowings {
  width: 100%;
  border-collapse: collapse;
}

.table-borrowings thead {
  background: var(--bg-light);
}

.table-borrowings th {
  padding: 15px;
  text-align: left;
  font-weight: 600;
  color: var(--text-primary);
  border-bottom: 2px solid var(--border);
}

.table-borrowings td {
  padding: 15px;
  border-bottom: 1px solid var(--border);
}

.table-borrowings tbody tr:hover {
  background: var(--bg-light);
}

.item-name {
  display: flex;
  align-items: center;
  gap: 10px;
}

.item-icon {
  font-size: 1.5rem;
}

.status-badge {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
}

.status-badge.active {
  background: rgba(16, 185, 129, 0.2);
  color: var(--success);
}

.status-badge.returned {
  background: rgba(16, 185, 129, 0.2);
  color: var(--success);
}

.status-badge.overdue {
  background: rgba(239, 68, 68, 0.2);
  color: var(--error);
}

.btn-action {
  background: var(--primary);
  color: white;
  border: none;
  padding: 6px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-action:hover {
  background: var(--primary-light);
}

/* ===== ACTIVITY TIMELINE ===== */
.activity-timeline {
  position: relative;
  padding-left: 30px;
}

.activity-timeline::before {
  content: '';
  position: absolute;
  left: 5px;
  top: 0;
  bottom: 0;
  width: 2px;
  background: var(--border);
}

.activity-item {
  position: relative;
  margin-bottom: 20px;
  padding-bottom: 20px;
}

.activity-dot {
  position: absolute;
  left: -22px;
  top: 5px;
  width: 14px;
  height: 14px;
  background: var(--primary);
  border: 3px solid white;
  border-radius: 50%;
  box-shadow: 0 0 0 2px var(--primary);
}

.activity-content h4 {
  margin: 0 0 5px 0;
  color: var(--text-primary);
}

.activity-content p {
  margin: 0;
  color: var(--text-secondary);
  font-size: 0.9rem;
}

.activity-time {
  color: #999 !important;
  font-size: 0.85rem !important;
  margin-top: 5px !important;
}

/* ===== QUICK ACTIONS ===== */
.quick-actions {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 15px;
}

.action-btn {
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  color: white;
  border: none;
  padding: 20px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
  transition: all 0.3s ease;
}

.action-btn:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(11, 114, 133, 0.3);
}

.action-btn .icon {
  font-size: 2rem;
}

/* ===== PROFILE ===== */
.profile-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 30px;
}

@media (max-width: 768px) {
  .profile-layout {
    grid-template-columns: 1fr;
  }
}

.profile-card {
  background: white;
  border-radius: 12px;
  padding: 30px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.main-profile {
  text-align: center;
}

.profile-header {
  margin-bottom: 20px;
}

.profile-avatar-large {
  width: 100px;
  height: 100px;
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 3rem;
  margin: 0 auto 15px;
}

.profile-card h2 {
  margin: 0 0 5px 0;
  color: var(--text-primary);
}

.profile-role,
.profile-member {
  margin: 5px 0;
  color: var(--text-secondary);
  font-size: 0.95rem;
}

.profile-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 15px;
  margin: 20px 0;
  padding: 20px 0;
  border-top: 1px solid var(--border);
  border-bottom: 1px solid var(--border);
}

.profile-stat {
  text-align: center;
}

.stat-num {
  margin: 0;
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--primary);
}

.stat-name {
  margin: 5px 0 0 0;
  font-size: 0.8rem;
  color: var(--text-secondary);
}

.form-card h3 {
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
  text-align: left;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: 600;
  color: var(--text-primary);
  font-size: 0.95rem;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid var(--border);
  border-radius: 6px;
  font-family: inherit;
  font-size: 0.95rem;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(11, 114, 133, 0.1);
}

.info-group {
  text-align: left;
}

.info-group p {
  margin: 15px 0;
  color: var(--text-secondary);
}

.info-group strong {
  color: var(--text-primary);
}

.settings-group {
  text-align: left;
}

.setting-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 0;
  border-bottom: 1px solid var(--border);
}

.setting-item:last-child {
  border-bottom: none;
}

.setting-title {
  margin: 0 0 5px 0;
  font-weight: 600;
  color: var(--text-primary);
}

.setting-desc {
  margin: 0;
  font-size: 0.9rem;
  color: var(--text-secondary);
}

/* ===== HELP ===== */
.help-section {
  margin-bottom: 30px;
}

.help-section h4 {
  margin-bottom: 20px;
  color: var(--text-primary);
  font-size: 1.1rem;
}

.faq-list {
  display: space-y;
}

.faq-item {
  margin-bottom: 15px;
  border: 1px solid var(--border);
  border-radius: 8px;
  overflow: hidden;
}

.faq-question {
  width: 100%;
  background: var(--bg-light);
  border: none;
  padding: 15px;
  text-align: left;
  cursor: pointer;
  font-weight: 600;
  color: var(--text-primary);
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: all 0.3s ease;
}

.faq-question:hover {
  background: #f0f2f5;
}

.faq-icon {
  font-size: 0.8rem;
}

.faq-answer {
  padding: 15px;
  background: white;
  color: var(--text-secondary);
  line-height: 1.6;
}

.contact-options {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
}

.contact-item {
  background: var(--bg-light);
  padding: 20px;
  border-radius: 8px;
  text-align: center;
}

.contact-method {
  margin: 0 0 10px 0;
  font-weight: 600;
  color: var(--text-primary);
}

.contact-item p {
  margin: 0;
  color: var(--text-secondary);
}

/* ===== BUTTONS ===== */
.btn-small {
  padding: 8px 16px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
  font-size: 0.85rem;
}

.btn-small.primary {
  background: var(--primary);
  color: white;
}

.btn-small.primary:hover {
  background: var(--primary-light);
}

.btn-small.secondary {
  background: var(--bg-light);
  color: var(--primary);
  border: 1px solid var(--border);
}

.btn-small.secondary:hover {
  border-color: var(--primary);
}

.btn-large {
  width: 100%;
  padding: 12px;
  border-radius: 8px;
  border: none;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
  margin-top: 15px;
}

.btn-large.primary {
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  color: white;
}

.btn-large.primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(11, 114, 133, 0.3);
}

/* ===== EMPTY STATE ===== */
.empty-state {
  text-align: center;
  padding: 40px;
  color: var(--text-secondary);
}

.empty-state a {
  color: var(--primary);
  text-decoration: none;
  font-weight: 600;
}

.empty-state a:hover {
  text-decoration: underline;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .header-top {
    flex-direction: column;
    gap: 15px;
  }

  .header-actions {
    flex-direction: column;
    width: 100%;
  }

  .search-box,
  .search-box input {
    width: 100%;
  }

  .dash-nav {
    flex-wrap: wrap;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }

  .dash-main {
    padding: 15px;
  }

  .section-card {
    padding: 20px;
  }

  .table-borrowings {
    font-size: 0.9rem;
  }

  .table-borrowings th,
  .table-borrowings td {
    padding: 10px;
  }
}
</style>
