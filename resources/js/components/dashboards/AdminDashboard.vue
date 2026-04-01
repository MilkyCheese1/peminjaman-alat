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
            <p class="stat-value">1,523</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">📦</div>
          <div class="stat-info">
            <p class="stat-label">Total Alat</p>
            <p class="stat-value">856</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">🔄</div>
          <div class="stat-info">
            <p class="stat-label">Transaksi Aktif</p>
            <p class="stat-value">456</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">✅</div>
          <div class="stat-info">
            <p class="stat-label">Kesehatan Sistem</p>
            <p class="stat-value">99.8%</p>
          </div>
        </div>
      </div>

      <section class="section-card">
        <h3>👥 Aktivitas Pengguna Terbaru</h3>
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
import { defineProps } from 'vue'

defineProps({
  activeTab: String,
  roleColor: String
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
</style>