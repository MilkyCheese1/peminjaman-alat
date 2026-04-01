<template>
  <div class="owner-dashboard">
    <!-- OVERVIEW TAB -->
    <section v-if="activeTab === 'overview'" class="tab-content">
      <div class="welcome-card" :style="{ borderLeftColor: roleColor }">
        <h2>Dashboard Pemilik 👨‍💼</h2>
        <p>Kelola alat-alat Anda dan monitor peminjaman</p>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon">📦</div>
          <div class="stat-info">
            <p class="stat-label">Alat Dimiliki</p>
            <p class="stat-value">45</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">🔄</div>
          <div class="stat-info">
            <p class="stat-label">Sedang Dipinjam</p>
            <p class="stat-value">12</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">💰</div>
          <div class="stat-info">
            <p class="stat-label">Pendapatan Bulan Ini</p>
            <p class="stat-value">Rp 1.2M</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">⭐</div>
          <div class="stat-info">
            <p class="stat-label">Rating</p>
            <p class="stat-value">4.9/5</p>
          </div>
        </div>
      </div>

      <section class="section-card">
        <h3>📋 Peminjaman Aktif</h3>
        <table class="table">
          <thead>
            <tr>
              <th>Alat</th>
              <th>Peminjam</th>
              <th>Tanggal Kembali</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Laptop</td>
              <td>Ahmad</td>
              <td>02 Apr</td>
              <td><span class="badge active">Aktif</span></td>
            </tr>
          </tbody>
        </table>
      </section>
    </section>

    <!-- MY ITEMS TAB -->
    <section v-if="activeTab === 'my-items'" class="tab-content">
      <div class="section-card">
        <h3>🔧 Alat Saya</h3>
        <div class="items-list">
          <div class="item-row">
            <div class="item-name">💻 Laptop Dell XPS</div>
            <div class="item-status"><span class="badge" style="background: #10b98126; color: #10b981;">Tersedia</span></div>
            <div class="item-actions">
              <button class="btn-small">Edit</button>
              <button class="btn-small">Hapus</button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- BORROWINGS TAB -->
    <section v-if="activeTab === 'borrowings'" class="tab-content">
      <div class="section-card">
        <h3>📋 Riwayat Peminjaman</h3>
        <p>Lihat semua pemberi peminjam dan riwayat transaksi.</p>
      </div>
    </section>

    <!-- REPORTS TAB -->
    <section v-if="activeTab === 'reports'" class="tab-content">
      <div class="section-card">
        <h3>📊 Laporan</h3>
        <p>Analisis peminjaman, pendapatan, dan rating.</p>
      </div>
    </section>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'
import { ref } from 'vue'

defineProps({
  activeTab: String,
  roleColor: String
})
</script>

<style scoped>
.owner-dashboard {
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

.badge.active {
  background: rgba(16, 185, 129, 0.2);
  color: #10b981;
}

.items-list {
  display: space-y;
}

.item-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px;
  background: #f8f9fa;
  border-radius: 8px;
  margin-bottom: 10px;
}

.item-name {
  flex: 1;
  font-weight: 600;
}

.item-status {
  flex: 0.3;
}

.item-actions {
  display: flex;
  gap: 10px;
}

.btn-small {
  background: #0B7285;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.85rem;
}

.btn-small:hover {
  background: #089FB3;
}
</style>