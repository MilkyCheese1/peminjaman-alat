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
            <p class="stat-value">{{ pendingOrders }}</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">✅</div>
          <div class="stat-info">
            <p class="stat-label">Pesanan Proses</p>
            <p class="stat-value">{{ inProcessOrders }}</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">📦</div>
          <div class="stat-info">
            <p class="stat-label">Pengembalian Hari Ini</p>
            <p class="stat-value">{{ todayReturns }}</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">⭐</div>
          <div class="stat-info">
            <p class="stat-label">Rating Support</p>
            <p class="stat-value">{{ supportRating }}/5</p>
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
      <BorrowingApprovalDashboard 
        @approve="handleApprove"
        @reject="handleReject"
      />
      
      <!-- Verify return modal -->
      <div v-if="showReturnVerification && selectedBorrowingDetail" class="modal-overlay" @click.self="showReturnVerification = false">
        <div class="modal-dialog">
          <button class="modal-close" @click="showReturnVerification = false">✕</button>
          <ReturnVerification 
            :borrowing="selectedBorrowingDetail"
            :isStaff="true"
            @verify-staff="handleReturnVerify"
            @cancel="showReturnVerification = false"
          />
        </div>
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
import { defineProps, ref, computed } from 'vue'
import BorrowingApprovalDashboard from '../borrowing/BorrowingApprovalDashboard.vue'
import ReturnVerification from '../borrowing/ReturnVerification.vue'
import { borrowingRecords } from '../../data/borrowingData.js'

defineProps({
  activeTab: String,
  roleColor: String
})

const showReturnVerification = ref(false)
const selectedBorrowingDetail = ref(null)

const pendingOrders = computed(() => {
  return borrowingRecords.filter(b => b.status === 'applied').length
})

const inProcessOrders = computed(() => {
  return borrowingRecords.filter(b => b.status === 'approved' || b.status === 'ready_for_pickup').length
})

const todayReturns = computed(() => {
  const today = new Date().toDateString()
  return borrowingRecords.filter(b => {
    const returnDate = new Date(b.plannedReturnDate).toDateString()
    return returnDate === today && b.status === 'picked_up'
  }).length
})

const supportRating = computed(() => {
  // Default rating (would come from feedback system)
  return (4.7).toFixed(1)
})

const handleApprove = (borrowing) => {
  const idx = borrowingRecords.findIndex(b => b.id === borrowing.id)
  if (idx !== -1) borrowingRecords[idx] = borrowing
}

const handleReject = (borrowing) => {
  const idx = borrowingRecords.findIndex(b => b.id === borrowing.id)
  if (idx !== -1) borrowingRecords[idx] = borrowing
}

const handleReturnVerify = (borrowing) => {
  const idx = borrowingRecords.findIndex(b => b.id === borrowing.id)
  if (idx !== -1) borrowingRecords[idx] = borrowing
  showReturnVerification.value = false
}
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