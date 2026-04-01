<template>
  <div class="staff-dashboard">
    <!-- OVERVIEW TAB -->
    <section v-if="activeTab === 'overview'" class="tab-content">
      <div class="welcome-card" :style="{ borderLeftColor: roleColor }">
        <h2>Dashboard Staff 👨‍🔧</h2>
        <p>Kelola peminjaman dan proses pesanan</p>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon">📋</div>
          <div class="stat-info">
            <p class="stat-label">Pesanan Menunggu</p>
            <p class="stat-value">8</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">✅</div>
          <div class="stat-info">
            <p class="stat-label">Pesanan Proses</p>
            <p class="stat-value">12</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">📦</div>
          <div class="stat-info">
            <p class="stat-label">Pengembalian Hari Ini</p>
            <p class="stat-value">5</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">⭐</div>
          <div class="stat-info">
            <p class="stat-label">Rating Support</p>
            <p class="stat-value">4.7/5</p>
          </div>
        </div>
      </div>

      <section class="section-card">
        <h3>📋 Pesanan Menunggu</h3>
        <table class="table">
          <thead>
            <tr>
              <th>Pesanan</th>
              <th>Peminjam</th>
              <th>Alat</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>#001</td>
              <td>Ahmad</td>
              <td>Laptop</td>
              <td><span class="badge pending">Menunggu</span></td>
            </tr>
          </tbody>
        </table>
      </section>
    </section>

    <!-- BORROWINGS TAB -->
    <section v-if="activeTab === 'borrowings'" class="tab-content">
      <div class="section-card">
        <h3>📋 Kelola Peminjaman</h3>
        <p>Proses peminjaman dan pengembalian alat.</p>
      </div>
    </section>

    <!-- ORDERS TAB -->
    <section v-if="activeTab === 'orders'" class="tab-content">
      <div class="section-card">
        <h3>🛒 Pesanan</h3>
        <p>Kelola semua pesanan dari pengguna.</p>
      </div>
    </section>

    <!-- REPORTS TAB -->
    <section v-if="activeTab === 'reports'" class="tab-content">
      <div class="section-card">
        <h3>📊 Laporan</h3>
        <p>Laporan peminjaman dan aktivitas.</p>
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
.staff-dashboard {
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

.badge {
  display: inline-block;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: 600;
}

.badge.pending {
  background: rgba(245, 158, 11, 0.2);
  color: #f59e0b;
}
</style>