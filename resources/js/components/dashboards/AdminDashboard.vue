<template>
  <div class="admin-dashboard">
    <!-- OVERVIEW TAB -->
    <section v-if="activeTab === 'overview'" class="tab-content">
      <div class="welcome-card" :style="{ borderLeftColor: roleColor }">
        <h2>Dashboard Administrator 👨‍💻</h2>
        <p>Kelola sistem dan monitor keseluruhan aktivitas</p>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon">👥</div>
          <div class="stat-info">
            <p class="stat-label">Total Pengguna</p>
            <p class="stat-value">{{ totalUsers }}</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">📦</div>
          <div class="stat-info">
            <p class="stat-label">Total Alat</p>
            <p class="stat-value">{{ totalEquipment }}</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">🔄</div>
          <div class="stat-info">
            <p class="stat-label">Transaksi Aktif</p>
            <p class="stat-value">{{ activeTransactions }}</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">✅</div>
          <div class="stat-info">
            <p class="stat-label">Kesehatan Sistem</p>
            <p class="stat-value">{{ systemHealth }}%</p>
          </div>
        </div>
      </div>

      <section class="section-card">
        <h3>� Statistik Peminjaman</h3>
        <div class="stats-grid stats-small">
          <div class="stat-mini">
            <p class="stat-label">Total Peminjaman</p>
            <p class="stat-value">{{ totalBorrowings }}</p>
          </div>
          <div class="stat-mini">
            <p class="stat-label">Menunggu Persetujuan</p>
            <p class="stat-value">{{ pendingApprovalsCount }}</p>
          </div>
          <div class="stat-mini">
            <p class="stat-label">Terlambat</p>
            <p class="stat-value">{{ overdueCount }}</p>
          </div>
          <div class="stat-mini">
            <p class="stat-label">Total Denda</p>
            <p class="stat-value">Rp {{ totalFines.toLocaleString('id-ID') }}</p>
          </div>
        </div>
      </section>

      <section class="section-card">
        <h3>�👥 Aktivitas Pengguna Terbaru</h3>
        <table class="table">
          <thead>
            <tr>
              <th>Pengguna</th>
              <th>Role</th>
              <th>Aktivitas</th>
              <th>Waktu</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Ahmad</td>
              <td><span class="role-badge customer">Customer</span></td>
              <td>Meminjam Laptop</td>
              <td>10:30</td>
            </tr>
          </tbody>
        </table>
      </section>
    </section>

    <!-- USERS TAB -->
    <section v-if="activeTab === 'users'" class="tab-content">
      <div class="section-card">
        <h3>👥 Manajemen Pengguna</h3>
        <p>Kelola pengguna sistem, role, dan permission.</p>
      </div>
    </section>

    <!-- ITEMS TAB -->
    <section v-if="activeTab === 'items'" class="tab-content">
      <div class="section-card">
        <h3>📦 Manajemen Alat</h3>
        <p>Kelola semua alat dalam sistem.</p>
      </div>
    </section>

    <!-- REPORTS TAB -->
    <section v-if="activeTab === 'reports'" class="tab-content">
      <div class="section-card">
        <h3>📊 Laporan Sistem</h3>
        <p>Analisis laporan lengkap aktivitas sistem.</p>
      </div>
    </section>

    <!-- SETTINGS TAB -->
    <section v-if="activeTab === 'settings'" class="tab-content">
      <div class="section-card">
        <h3>⚙️ Pengaturan Sistem</h3>
        <p>Konfigurasi sistem dan parameter aplikasi.</p>
      </div>
    </section>

    <!-- LOGS TAB -->
    <section v-if="activeTab === 'logs'" class="tab-content">
      <div class="section-card">
        <h3>📝 Log Sistem</h3>
        <p>Lihat log aktivitas dan error sistem.</p>
      </div>
    </section>
  </div>
</template>

<script setup>
import { defineProps, computed } from 'vue'
import { borrowingRecords } from '../../data/borrowingData.js'

defineProps({
  activeTab: String,
  roleColor: String
})

const totalBorrowings = computed(() => borrowingRecords.length)

const totalUsers = computed(() => {
  // Estimate based on borrowing records (unique customers)
  const users = new Set(borrowingRecords.map(b => b.customerId))
  return Math.max(users.size, 50) // Minimum 50 for demo
})

const totalEquipment = computed(() => {
  // Estimate based on unique equipment in system
  const equipment = new Set(borrowingRecords.map(b => b.equipmentId))
  return Math.max(equipment.size * 20, 100) // Rough estimate
})

const activeTransactions = computed(() => {
  return borrowingRecords.filter(b => b.status === 'picked_up' || b.status === 'approved').length
})

const systemHealth = computed(() => {
  // Calculate based on error rate
  const errorRate = (overdueCount.value / Math.max(totalBorrowings.value, 1)) * 100
  const health = Math.max(0, 100 - errorRate)
  return health.toFixed(1)
})

const pendingApprovalsCount = computed(() => {
  return borrowingRecords.filter(b => b.status === 'applied').length
})

const overdueCount = computed(() => {
  return borrowingRecords.filter(b => {
    const now = new Date()
    const planned = new Date(b.plannedReturnDate)
    return b.status === 'picked_up' && now > planned
  }).length
})

const totalFines = computed(() => {
  return borrowingRecords.reduce((sum, b) => sum + (b.fineAmount || 0), 0)
})
</script>

<style scoped>
.admin-dashboard {
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
  background: white;
  padding: 30px;
  border-radius: 12px;
  margin-bottom: 30px;
  border-left: 4px solid;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.welcome-card h2 {
  margin-bottom: 10px;
  color: #1a1a2e;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.stat-card {
  background: white;
  padding: 25px;
  border-radius: 12px;
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

.stat-icon {
  font-size: 2.5rem;
}

.stat-label {
  margin: 0;
  color: #666;
  font-size: 0.9rem;
}

.stat-value {
  margin: 5px 0 0 0;
  font-size: 2rem;
  font-weight: 700;
  color: #0B7285;
}

.section-card {
  background: white;
  border-radius: 12px;
  padding: 30px;
  margin-bottom: 30px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.section-card h3 {
  margin: 0 0 20px 0;
  color: #1a1a2e;
}

.table {
  width: 100%;
  border-collapse: collapse;
}

.table th {
  background: #f8f9fa;
  padding: 12px;
  text-align: left;
  font-weight: 600;
  border-bottom: 2px solid #e0e0e0;
}

.table td {
  padding: 12px;
  border-bottom: 1px solid #e0e0e0;
}

.role-badge {
  display: inline-block;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: 600;
}

.role-badge.customer {
  background: rgba(150, 206, 180, 0.3);
  color: #10b981;
}

.stats-small {
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 15px;
}

.stat-mini {
  background: white;
  padding: 15px;
  border-radius: 8px;
  text-align: center;
  border: 1px solid #e0e0e0;
}

.stat-mini .stat-label {
  margin: 0;
  color: #666;
  font-size: 0.85rem;
  font-weight: 600;
}

.stat-mini .stat-value {
  margin: 8px 0 0 0;
  font-size: 1.5rem;
  font-weight: 700;
  color: #0B7285;
}
</style>