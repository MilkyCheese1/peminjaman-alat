<template>
  <div class="demo-info-container">
    <!-- Header -->
    <header class="demo-header">
      <div class="header-content">
        <h1>🎓 TrustEquip - Demo User Credentials</h1>
        <p>Gunakan akun berikut untuk testing aplikasi</p>
      </div>
      <router-link to="/login" class="btn-login">
        🚀 Masuk ke Aplikasi
      </router-link>
    </header>

    <!-- Info Cards -->
    <main class="demo-content">
      <!-- OWNERS -->
      <section class="role-section">
        <div class="section-header">
          <h2>👨‍💼 Owner (Pemilik Alat/Sistem)</h2>
          <p>Dapat mengelola alat pribadi dan melihat peminjaman</p>
        </div>
        <div class="users-grid">
          <div v-for="user in getOwners" :key="user.id" class="user-card owner">
            <div class="user-header">
              <span class="avatar">{{ user.avatar }}</span>
              <div class="user-info">
                <h3>{{ user.fullname }}</h3>
                <p class="role-badge">{{ user.role.toUpperCase() }}</p>
              </div>
            </div>
            <div class="credentials">
              <div class="cred-item">
                <span class="label">Email:</span>
                <code>{{ user.email }}</code>
              </div>
              <div class="cred-item">
                <span class="label">Password:</span>
                <code>{{ user.password }}</code>
              </div>
            </div>
            <div class="user-details">
              <p>📱 {{ user.phone }}</p>
              <p>🏫 {{ user.school }}</p>
              <p>⭐ Rating: {{ user.rating }}/5</p>
              <p>📦 Total Borrowings: {{ user.totalBorrowings }}</p>
            </div>
            <div class="copy-section">
              <button @click="copyCredentials(user.email, user.password)" class="btn-copy">
                📋 Salin Credentials
              </button>
              <router-link :to="`/login?email=${encodeURIComponent(user.email)}`" class="btn-quick-login">
                🚀 Quick Login
              </router-link>
            </div>
          </div>
        </div>
      </section>

      <!-- ADMINS -->
      <section class="role-section">
        <div class="section-header">
          <h2>👨‍💻 Admin (Administrator Sistem)</h2>
          <p>Dapat mengelola seluruh sistem, pengguna, dan laporan</p>
        </div>
        <div class="users-grid">
          <div v-for="user in getAdmins" :key="user.id" class="user-card admin">
            <div class="user-header">
              <span class="avatar">{{ user.avatar }}</span>
              <div class="user-info">
                <h3>{{ user.fullname }}</h3>
                <p class="role-badge">{{ user.role.toUpperCase() }}</p>
              </div>
            </div>
            <div class="credentials">
              <div class="cred-item">
                <span class="label">Email:</span>
                <code>{{ user.email }}</code>
              </div>
              <div class="cred-item">
                <span class="label">Password:</span>
                <code>{{ user.password }}</code>
              </div>
            </div>
            <div class="user-details">
              <p>📱 {{ user.phone }}</p>
              <p>🏢 {{ user.school }}</p>
              <p>👥 Total Users: {{ user.totalUsers }}</p>
              <p>📊 Active Transactions: {{ user.activeTransactions }}</p>
            </div>
            <div class="copy-section">
              <button @click="copyCredentials(user.email, user.password)" class="btn-copy">
                📋 Salin Credentials
              </button>
              <router-link :to="`/login?email=${encodeURIComponent(user.email)}`" class="btn-quick-login">
                🚀 Quick Login
              </router-link>
            </div>
          </div>
        </div>
      </section>

      <!-- STAFFS -->
      <section class="role-section">
        <div class="section-header">
          <h2>👨‍🔧 Staff (Pekerja/Karyawan)</h2>
          <p>Dapat mengelola peminjaman, memproses pesanan, dan melihat laporan</p>
        </div>
        <div class="users-grid">
          <div v-for="user in getStaffs" :key="user.id" class="user-card staff">
            <div class="user-header">
              <span class="avatar">{{ user.avatar }}</span>
              <div class="user-info">
                <h3>{{ user.fullname }}</h3>
                <p class="role-badge">{{ user.role.toUpperCase() }}</p>
              </div>
            </div>
            <div class="credentials">
              <div class="cred-item">
                <span class="label">Email:</span>
                <code>{{ user.email }}</code>
              </div>
              <div class="cred-item">
                <span class="label">Password:</span>
                <code>{{ user.password }}</code>
              </div>
            </div>
            <div class="user-details">
              <p>📱 {{ user.phone }}</p>
              <p>🏫 {{ user.school }}</p>
              <p>🏢 Department: {{ user.department }}</p>
              <p>⭐ Rating: {{ user.rating }}/5</p>
            </div>
            <div class="copy-section">
              <button @click="copyCredentials(user.email, user.password)" class="btn-copy">
                📋 Salin Credentials
              </button>
              <router-link :to="`/login?email=${encodeURIComponent(user.email)}`" class="btn-quick-login">
                🚀 Quick Login
              </router-link>
            </div>
          </div>
        </div>
      </section>

      <!-- CUSTOMERS -->
      <section class="role-section">
        <div class="section-header">
          <h2>👨‍🎓 Customer (Pengguna/Peminjam)</h2>
          <p>Dapat meminjam alat, melihat status peminjaman, dan riwayat</p>
        </div>
        <div class="users-grid">
          <div v-for="user in getCustomers" :key="user.id" class="user-card customer">
            <div class="user-header">
              <span class="avatar">{{ user.avatar }}</span>
              <div class="user-info">
                <h3>{{ user.fullname }}</h3>
                <p class="role-badge">{{ user.role.toUpperCase() }}</p>
              </div>
            </div>
            <div class="credentials">
              <div class="cred-item">
                <span class="label">Email:</span>
                <code>{{ user.email }}</code>
              </div>
              <div class="cred-item">
                <span class="label">Password:</span>
                <code>{{ user.password }}</code>
              </div>
            </div>
            <div class="user-details">
              <p>📱 {{ user.phone }}</p>
              <p>🏫 {{ user.school }}</p>
              <p>⭐ Rating: {{ user.rating }}/5</p>
              <p>📦 Total Borrowings: {{ user.totalBorrowings }}</p>
            </div>
            <div class="copy-section">
              <button @click="copyCredentials(user.email, user.password)" class="btn-copy">
                📋 Salin Credentials
              </button>
              <router-link :to="`/login?email=${encodeURIComponent(user.email)}`" class="btn-quick-login">
                🚀 Quick Login
              </router-link>
            </div>
          </div>
        </div>
      </section>

      <!-- Testing Guide -->
      <section class="guide-section">
        <h2>📖 Panduan Testing</h2>
        <div class="guide-content">
          <div class="guide-card">
            <h3>🔍 Untuk Testing Login</h3>
            <ol>
              <li>Pilih akun dari salah satu role di atas</li>
              <li>Klik tombol "📋 Salin Credentials" untuk menyalin email & password</li>
              <li>Atau klik tombol "🚀 Quick Login" untuk langsung login</li>
              <li>Masukkan credentials di halaman login</li>
              <li>Anda akan diarahkan ke dashboard sesuai role</li>
            </ol>
          </div>

          <div class="guide-card">
            <h3>🎭 Role Testing Detail</h3>
            <ul>
              <li><strong>Owner:</strong> Akses dashboard dengan data kepemilikan alat</li>
              <li><strong>Admin:</strong> Akses ke pengelolaan sistem dan pengguna</li>
              <li><strong>Staff:</strong> Akses ke manajemen peminjaman dan support</li>
              <li><strong>Customer:</strong> Akses ke katalog alat dan peminjaman pribadi</li>
            </ul>
          </div>

          <div class="guide-card">
            <h3>💡 Testing Password</h3>
            <p>Semua akun customer menggunakan password standar: <code>customer123456</code></p>
            <p style="margin-top: 10px;">Setiap role memiliki password unik sesuai formatnya.</p>
          </div>
        </div>
      </section>

      <!-- Notification -->
      <div class="info-banner">
        <p>✨ Ini adalah akun demo untuk testing. Semua data akan direset setelah session berakhir.</p>
      </div>
    </main>

    <!-- Back Button -->
    <div class="back-link">
      <router-link to="/">← Kembali ke Halaman Utama</router-link>
    </div>

    <!-- Notification Toast -->
    <div v-if="showCopiedNotif" class="toast-notification">
      ✅ Credentials berhasil disalin!
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { dummyUsers } from '../data/dummyUsers.js'

const showCopiedNotif = ref(false)

const getOwners = computed(() => dummyUsers.filter(u => u.role === 'owner'))
const getAdmins = computed(() => dummyUsers.filter(u => u.role === 'admin'))
const getStaffs = computed(() => dummyUsers.filter(u => u.role === 'staff'))
const getCustomers = computed(() => dummyUsers.filter(u => u.role === 'customer'))

const copyCredentials = (email, password) => {
  const text = `Email: ${email}\nPassword: ${password}`
  navigator.clipboard.writeText(text).then(() => {
    showCopiedNotif.value = true
    setTimeout(() => {
      showCopiedNotif.value = false
    }, 2000)
  })
}
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
  --owner-color: #FF6B6B;
  --admin-color: #4ECDC4;
  --staff-color: #45B7D1;
  --customer-color: #96CEB4;
  --text-primary: #1a1a2e;
  --text-secondary: #666;
  --bg-light: #f8f9fa;
  --border: #e0e0e0;
}

.demo-info-container {
  min-height: 100vh;
  background: linear-gradient(135deg, var(--bg-light) 0%, #f0f2f5 100%);
  padding-bottom: 40px;
}

/* ===== HEADER ===== */
.demo-header {
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  color: white;
  padding: 40px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  flex-wrap: wrap;
  gap: 20px;
}

.header-content h1 {
  margin-bottom: 10px;
  font-size: 2rem;
}

.header-content p {
  opacity: 0.95;
  font-size: 1rem;
}

.btn-login {
  background: var(--accent);
  color: var(--text-primary);
  padding: 12px 30px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
  display: inline-block;
}

.btn-login:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

/* ===== MAIN CONTENT ===== */
.demo-content {
  max-width: 1400px;
  margin: 0 auto;
  padding: 40px 20px;
}

/* ===== ROLE SECTIONS ===== */
.role-section {
  margin-bottom: 50px;
}

.section-header {
  margin-bottom: 30px;
}

.section-header h2 {
  color: var(--text-primary);
  margin-bottom: 8px;
  font-size: 1.5rem;
}

.section-header p {
  color: var(--text-secondary);
  font-size: 0.95rem;
}

/* ===== USERS GRID ===== */
.users-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 20px;
}

.user-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  border-top: 4px solid var(--primary);
  animation: slideUp 0.5s ease-out;
}

.user-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.user-card.owner {
  border-top-color: var(--owner-color);
}

.user-card.admin {
  border-top-color: var(--admin-color);
}

.user-card.staff {
  border-top-color: var(--staff-color);
}

.user-card.customer {
  border-top-color: var(--customer-color);
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

.user-header {
  padding: 20px;
  background: var(--bg-light);
  display: flex;
  align-items: center;
  gap: 15px;
}

.avatar {
  font-size: 2.5rem;
}

.user-info h3 {
  margin: 0 0 5px 0;
  color: var(--text-primary);
  font-size: 1.1rem;
}

.role-badge {
  margin: 0;
  font-size: 0.75rem;
  font-weight: 600;
  padding: 4px 8px;
  border-radius: 4px;
  display: inline-block;
  background: rgba(11, 114, 133, 0.2);
  color: var(--primary);
}

/* ===== CREDENTIALS ===== */
.credentials {
  padding: 15px 20px;
  background: #f8f9fa;
  border-bottom: 1px solid var(--border);
}

.cred-item {
  margin-bottom: 10px;
  font-size: 0.9rem;
}

.cred-item:last-child {
  margin-bottom: 0;
}

.label {
  color: var(--text-secondary);
  font-weight: 600;
  display: block;
  margin-bottom: 3px;
}

code {
  background: white;
  border: 1px solid var(--border);
  padding: 8px 12px;
  border-radius: 4px;
  display: block;
  font-family: 'Courier New', monospace;
  color: var(--primary);
  font-weight: 600;
  word-break: break-all;
  user-select: all;
}

/* ===== USER DETAILS ===== */
.user-details {
  padding: 15px 20px;
  font-size: 0.9rem;
  color: var(--text-secondary);
}

.user-details p {
  margin: 8px 0;
}

/* ===== COPY SECTION ===== */
.copy-section {
  padding: 15px 20px;
  background: var(--bg-light);
  border-top: 1px solid var(--border);
  display: flex;
  gap: 10px;
}

.btn-copy,
.btn-quick-login {
  flex: 1;
  padding: 10px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  font-weight: 600;
  font-size: 0.85rem;
  transition: all 0.3s ease;
  text-decoration: none;
  text-align: center;
  display: block;
}

.btn-copy {
  background: var(--primary);
  color: white;
}

.btn-copy:hover {
  background: var(--primary-light);
  transform: translateY(-2px);
}

.btn-quick-login {
  background: var(--accent);
  color: var(--text-primary);
}

.btn-quick-login:hover {
  background: #FFB547;
  transform: translateY(-2px);
}

/* ===== GUIDE SECTION ===== */
.guide-section {
  background: white;
  border-radius: 12px;
  padding: 30px;
  margin-bottom: 30px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.guide-section h2 {
  color: var(--text-primary);
  margin-bottom: 20px;
  font-size: 1.3rem;
}

.guide-content {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
}

.guide-card {
  background: var(--bg-light);
  padding: 20px;
  border-radius: 8px;
  border-left: 4px solid var(--primary);
}

.guide-card h3 {
  margin-bottom: 12px;
  color: var(--text-primary);
}

.guide-card ol,
.guide-card ul {
  margin-left: 20px;
  color: var(--text-secondary);
}

.guide-card li {
  margin-bottom: 8px;
  line-height: 1.6;
}

.guide-card p {
  color: var(--text-secondary);
  line-height: 1.6;
}

/* ===== INFO BANNER ===== */
.info-banner {
  background: linear-gradient(135deg, rgba(11, 114, 133, 0.1) 0%, rgba(255, 159, 28, 0.1) 100%);
  border: 2px solid var(--primary);
  border-radius: 8px;
  padding: 20px;
  text-align: center;
  color: var(--primary);
  font-weight: 600;
  margin-bottom: 30px;
}

/* ===== BACK LINK ===== */
.back-link {
  text-align: center;
  margin-bottom: 20px;
}

.back-link a {
  color: var(--primary);
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
}

.back-link a:hover {
  color: var(--primary-light);
  transform: translateX(-5px);
  display: inline-block;
}

/* ===== TOAST NOTIFICATION ===== */
.toast-notification {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: var(--primary);
  color: white;
  padding: 15px 25px;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  animation: slideInRight 0.3s ease;
  z-index: 1000;
}

@keyframes slideInRight {
  from {
    opacity: 0;
    transform: translateX(100px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .demo-header {
    flex-direction: column;
    text-align: center;
  }

  .demo-header h1 {
    font-size: 1.5rem;
  }

  .users-grid {
    grid-template-columns: 1fr;
  }

  .copy-section {
    flex-direction: column;
  }

  .btn-copy,
  .btn-quick-login {
    font-size: 0.9rem;
  }
}
</style>
