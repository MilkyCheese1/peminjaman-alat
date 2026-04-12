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
      <BorrowingTable :canApprove="true" :canVerifyReturn="true" />
    </section>

    <!-- APPROVALS TAB -->
    <section v-if="activeTab === 'approvals'" class="tab-content">
      <StaffApprovalsComponent />
    </section>

    <!-- VERIFICATIONS TAB -->
    <section v-if="activeTab === 'verifications'" class="tab-content">
      <StaffVerificationsComponent />
    </section>

    <!-- ORDERS TAB (EQUIPMENT) -->
    <section v-if="activeTab === 'orders'" class="tab-content">
      <EquipmentTable :canEdit="false" />
    </section>

    <!-- REPORTS TAB -->
    <section v-if="activeTab === 'reports'" class="tab-content">
      <div class="section-card">
        <h3>📊 Laporan</h3>
        <p>Laporan peminjaman dan aktivitas.</p>
      </div>
    </section>

    <!-- PROFILE TAB -->
    <section v-if="activeTab === 'profile'" class="tab-content">
      <ProfileCard />
    </section>

    <!-- HELP TAB -->
    <section v-if="activeTab === 'help'" class="tab-content">
      <HelpCenter />
    </section>
  </div>
</template>

<script setup>
import { defineProps, ref, computed, onMounted } from 'vue'
import apiClient from '../../config/api'
import { useToast } from '../../composables/useToast'
import BorrowingTable from '../BorrowingTable.vue'
import EquipmentTable from '../EquipmentTable.vue'
import StaffApprovalsComponent from '../StaffApprovalsComponent.vue'
import StaffVerificationsComponent from '../StaffVerificationsComponent.vue'
import ProfileCard from '../ProfileCard.vue'
import HelpCenter from '../HelpCenter.vue'
import BorrowingApprovalDashboard from '../borrowing/BorrowingApprovalDashboard.vue'
import ReturnVerification from '../borrowing/ReturnVerification.vue'
import { borrowingRecords } from '../../data/borrowingData.js'

defineProps({
  activeTab: String,
  roleColor: String
})

const showReturnVerification = ref(false)
const selectedBorrowingDetail = ref(null)
const borrowings = ref([])
const { error: showError } = useToast()

// Load borrowings from API
onMounted(async () => {
  try {
    const response = await apiClient.get('/borrowings')
    borrowings.value = response.data?.data || []
  } catch (error) {
    // Silently handle staff borrowings loading error
    borrowings.value = []
  }
})

const pendingOrders = computed(() => {
  return (borrowings.value || borrowingRecords).filter(b => b.status === 'applied').length
})

const inProcessOrders = computed(() => {
  return (borrowings.value || borrowingRecords).filter(b => b.status === 'approved' || b.status === 'ready_for_pickup').length
})

const todayReturns = computed(() => {
  const today = new Date().toDateString()
  return (borrowings.value || borrowingRecords).filter(b => {
    const returnDate = new Date(b.tanggal_rencana_kembali || b.planned_return_date || b.plannedReturnDate).toDateString()
    return returnDate === today && b.status === 'picked_up'
  }).length
})

const supportRating = computed(() => {
  // Calculate from overdue percentage
  const overdue = (borrowings.value || borrowingRecords).filter(b => {
    const now = new Date()
    const planned = new Date(b.tanggal_rencana_kembali || b.planned_return_date || b.plannedReturnDate)
    return b.status === 'picked_up' && now > planned
  }).length
  
  const total = Math.max((borrowings.value || borrowingRecords).length, 1)
  const rating = Math.max(3.0, 5.0 - (overdue / total))
  return rating.toFixed(1)
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