<template>
  <div class="customer-dashboard">
    <!-- OVERVIEW TAB -->
    <section v-if="activeTab === 'overview'" class="tab-content">
      <div class="welcome-card" :style="{ borderLeftColor: roleColor }">
        <h2>Selamat datang, {{ userName }}! 👋</h2>
        <p>Jelajahi dan pinjam alat yang Anda butuhkan</p>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon">📦</div>
          <div class="stat-info">
            <p class="stat-label">Alat Sedang Dipinjam</p>
            <p class="stat-value">3</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">✅</div>
          <div class="stat-info">
            <p class="stat-label">Tersedia</p>
            <p class="stat-value">152</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">⏰</div>
          <div class="stat-info">
            <p class="stat-label">Pengembalian Tertunda</p>
            <p class="stat-value">1</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">📊</div>
          <div class="stat-info">
            <p class="stat-label">Riwayat</p>
            <p class="stat-value">24</p>
          </div>
        </div>
      </div>

      <section class="section-card">
        <div class="section-header">
          <h3>📋 Peminjaman Aktif</h3>
          <a href="#" class="view-all">Lihat Semua</a>
        </div>
        <div class="borrowing-list">
          <div class="borrow-item">
            <span class="icon">💻</span>
            <div class="info">
              <h4>Laptop Dell XPS 15</h4>
              <p>📅 29 Mar - 02 Apr 2026</p>
            </div>
            <button class="btn-small">Detail</button>
          </div>
          <div class="borrow-item">
            <span class="icon">📷</span>
            <div class="info">
              <h4>Kamera DSLR Canon 6D</h4>
              <p>📅 25 Mar - 31 Mar 2026 (OVERDUC)</p>
            </div>
            <button class="btn-small warning">Perpanjang</button>
          </div>
        </div>
      </section>

      <section class="section-card">
        <h3>⚡ Rekomendasi untuk Anda</h3>
        <div class="items-grid">
          <div v-for="item in recommendedItems" :key="item.id" class="item-card">
            <div class="item-icon">{{ item.icon }}</div>
            <h4>{{ item.name }}</h4>
            <p>{{ item.desc }}</p>
            <div class="item-footer">
              <span class="price">Rp {{ item.price }}/hari</span>
              <button class="btn-borrow">Pinjam</button>
            </div>
          </div>
        </div>
      </section>
    </section>

    <!-- BROWSE TAB -->
    <section v-if="activeTab === 'browse'" class="tab-content">
      <div class="section-card">
        <h3>🛍️ Jelajahi Alat</h3>
        <div class="filters">
          <input type="text" placeholder="Cari alat...">
          <select>
            <option>Semua Kategori</option>
            <option>Elektronik</option>
            <option>Peralatan</option>
          </select>
        </div>
        <div class="items-grid">
          <div v-for="item in allItems" :key="item.id" class="item-card">
            <div class="item-icon">{{ item.icon }}</div>
            <h4>{{ item.name }}</h4>
            <p>{{ item.desc }}</p>
            <div class="item-stock">Stok: {{ item.stock }}</div>
            <div class="item-footer">
              <span class="price">Rp {{ item.price }}/hari</span>
              <button class="btn-borrow">Pinjam</button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- MY BORROWINGS TAB -->
    <section v-if="activeTab === 'my-borrowings'" class="tab-content">
      <div class="section-card">
        <h3>📦 Riwayat Peminjaman</h3>
        <table class="table">
          <thead>
            <tr>
              <th>Alat</th>
              <th>Peminjam</th>
              <th>Tanggal</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>💻 Laptop Dell</td>
              <td>Ahmad</td>
              <td>29 Mar - 02 Apr</td>
              <td><span class="badge active">Aktif</span></td>
            </tr>
            <tr>
              <td>📷 Kamera DSLR</td>
              <td>Ahmad</td>
              <td>25 Mar - 31 Mar</td>
              <td><span class="badge overdue">Overduc</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- PROFILE TAB -->
    <section v-if="activeTab === 'profile'" class="tab-content">
      <div class="profile-card">
        <h3>👤 Profil Saya</h3>
        <div class="info-group">
          <p><strong>Nama:</strong> Ahmad Rizki</p>
          <p><strong>Email:</strong> ahmad@school.id</p>
          <p><strong>Sekolah:</strong> SMA Negeri 1</p>
          <p><strong>Rating:</strong> ⭐ 4.8/5</p>
        </div>
      </div>
    </section>

    <!-- HELP TAB -->
    <section v-if="activeTab === 'help'" class="tab-content">
      <div class="section-card">
        <h3>❓ Bantuan</h3>
        <div class="faq-list">
          <details>
            <summary>Bagaimana cara meminjam alat?</summary>
            <p>Klik tombol "Pinjam" pada alat yang ingin dipinjam, kemudian lengkapi formulir peminjaman.</p>
          </details>
          <details>
            <summary>Berapa lama durasi peminjaman?</summary>
            <p>Durasi standar 3-7 hari tergantung jenis alat, dan dapat diperpanjang jika tersedia.</p>
          </details>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { defineProps, ref } from 'vue'

defineProps({
  activeTab: String,
  roleColor: String
})

const userName = ref('Ahmad Rizki')

const recommendedItems = ref([
  { id: 1, icon: '💻', name: 'Laptop Gaming', desc: 'Gaming terbaru', price: '50000', stock: 5 },
  { id: 2, icon: '📷', name: 'Kamera 4K', desc: 'Kamera profesional', price: '75000', stock: 3 },
  { id: 3, icon: '🎙️', name: 'Mic Studio', desc: 'Mikrofon berkualitas', price: '35000', stock: 8 }
])

const allItems = ref([
  { id: 1, icon: '💻', name: 'Laptop', desc: 'Berbagai model', price: '50000', stock: 10 },
  { id: 2, icon: '📷', name: 'Kamera', desc: 'DSLR Professional', price: '75000', stock: 5 },
  { id: 3, icon: '🎬', name: 'Proyektor', desc: 'HD 4K', price: '60000', stock: 8 },
  { id: 4, icon: '🎙️', name: 'Microphone', desc: 'Studio Set', price: '35000', stock: 12 }
])
</script>

<style scoped>
.customer-dashboard {
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

.welcome-card p {
  color: #666;
  font-size: 0.95rem;
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

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 2px solid #e0e0e0;
}

.section-card h3 {
  margin: 0 0 20px 0;
  color: #1a1a2e;
}

.view-all {
  color: #0B7285;
  text-decoration: none;
  font-weight: 600;
}

.borrowing-list {
  display: space-y;
}

.borrow-item {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 15px;
  background: #f8f9fa;
  border-radius: 8px;
  margin-bottom: 10px;
  transition: all 0.3s ease;
}

.borrow-item:hover {
  background: #f0f2f5;
}

.borrow-item .icon {
  font-size: 2rem;
}

.borrow-item .info {
  flex: 1;
}

.borrow-item h4 {
  margin: 0 0 5px 0;
  color: #1a1a2e;
}

.borrow-item p {
  margin: 0;
  font-size: 0.85rem;
  color: #666;
}

.items-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 20px;
}

.item-card {
  background: #f8f9fa;
  border-radius: 8px;
  padding: 15px;
  text-align: center;
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.item-card:hover {
  border-color: #0B7285;
  box-shadow: 0 4px 12px rgba(11, 114, 133, 0.2);
}

.item-icon {
  font-size: 2.5rem;
  margin-bottom: 10px;
}

.item-card h4 {
  margin: 0 0 5px 0;
  color: #1a1a2e;
}

.item-card p {
  margin: 0 0 10px 0;
  font-size: 0.85rem;
  color: #666;
}

.item-stock {
  font-size: 0.8rem;
  color: #666;
  margin-bottom: 10px;
}

.item-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 10px;
  border-top: 1px solid #e0e0e0;
}

.price {
  font-weight: 600;
  color: #0B7285;
}

.btn-borrow {
  background: #0B7285;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.85rem;
  transition: all 0.3s ease;
}

.btn-borrow:hover {
  background: #089FB3;
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

.btn-small.warning {
  background: #FF9F1C;
}

.filters {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}

.filters input,
.filters select {
  padding: 8px 12px;
  border: 1px solid #e0e0e0;
  border-radius: 6px;
  font-size: 0.9rem;
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

.badge.overdue {
  background: rgba(239, 68, 68, 0.2);
  color: #ef4444;
}

.profile-card {
  background: white;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.info-group p {
  margin: 15px 0;
  color: #666;
}

.info-group strong {
  color: #1a1a2e;
}

.faq-list details {
  margin-bottom: 15px;
  background: #f8f9fa;
  padding: 15px;
  border-radius: 8px;
  cursor: pointer;
}

.faq-list summary {
  font-weight: 600;
  color: #1a1a2e;
}

.faq-list details p {
  margin: 10px 0 0 0;
  color: #666;
  font-size: 0.9rem;
}
</style>
