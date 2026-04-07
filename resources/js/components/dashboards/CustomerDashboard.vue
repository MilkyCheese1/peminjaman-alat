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
            <p class="stat-value">{{ borrowingsCount }}</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">✅</div>
          <div class="stat-info">
            <p class="stat-label">Tersedia</p>
            <p class="stat-value">{{ availableItemsCount }}</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">⏰</div>
          <div class="stat-info">
            <p class="stat-label">Pengembalian Tertunda</p>
            <p class="stat-value">{{ overdueCount }}</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">📊</div>
          <div class="stat-info">
            <p class="stat-label">Riwayat</p>
            <p class="stat-value">{{ historyCount }}</p>
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

    </section>

    <!-- EXPLORE TAB -->
    <section v-if="activeTab === 'explore'" class="tab-content">
      <EquipmentBrowseComponent />
    </section>

    <!-- MY BORROWINGS TAB -->
    <section v-if="activeTab === 'my-borrowings'" class="tab-content">
      <BorrowingTable :canApprove="false" :canVerifyReturn="false" :canCreate="true" :currentUserId="currentUser?.id" />
    </section>

    <!-- PROFILE TAB -->
    <section v-if="activeTab === 'profile'" class="tab-content">
      <ProfileCard />
    </section>

    <!-- HELP TAB -->
    <section v-if="activeTab === 'help'" class="tab-content">
      <HelpCenter />
    </section>

    <!-- Detail Modal -->
    <div v-if="showDetailModal && selectedBorrowingDetail" class="modal-overlay" @click.self="handleCloseDetailModal">
      <div class="modal-dialog">
        <button class="modal-close" @click="handleCloseDetailModal">✕</button>
        <PickupVerification 
          v-if="selectedBorrowingDetail.status === 'ready_for_pickup'"
          :borrowing="selectedBorrowingDetail"
          @verify="handlePickupVerify"
          @cancel="handleCloseDetailModal"
        />
        <ReturnVerification 
          v-else-if="selectedBorrowingDetail.status === 'picked_up'"
          :borrowing="selectedBorrowingDetail"
          :isStaff="false"
          @verify-customer="handleReturnCustomerVerify"
          @cancel="handleCloseDetailModal"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, ref, computed, onMounted } from 'vue'
import BorrowingTable from '../BorrowingTable.vue'
import EquipmentTable from '../EquipmentTable.vue'
import EquipmentBrowseComponent from '../EquipmentBrowseComponent.vue'

import ProfileCard from '../ProfileCard.vue'
import HelpCenter from '../HelpCenter.vue'
import BorrowingForm from '../borrowing/BorrowingForm.vue'
import PickupVerification from '../borrowing/PickupVerification.vue'
import ReturnVerification from '../borrowing/ReturnVerification.vue'
import { borrowingRecords, getCustomerBorrowings } from '../../data/borrowingData.js'
import { STATUS_INFO, formatDate } from '../../data/borrowingStatuses.js'

const props = defineProps({
  activeTab: String,
  roleColor: String
})

const userName = ref('Ahmad Rizki')
const currentUser = ref(null)
const selectedBorrowingDetail = ref(null)
const showDetailModal = ref(false)

onMounted(() => {
  const userStr = localStorage.getItem('user')
  if (userStr) {
    currentUser.value = JSON.parse(userStr)
  }
})

const myBorrowings = computed(() => {
  if (!currentUser.value) return []
  return getCustomerBorrowings(currentUser.value.id, borrowingRecords)
})

const borrowingsCount = computed(() => {
  return myBorrowings.value.filter(b => b.status === 'picked_up').length
})

const overdueCount = computed(() => {
  return myBorrowings.value.filter(b => b.status === 'overdue').length
})

const historyCount = computed(() => {
  return myBorrowings.value.filter(b => b.status === 'returned' || b.status === 'cancelled').length
})

const availableItemsCount = computed(() => {
  return allItems.value.reduce((total, item) => total + item.stock, 0)
})

const getStatusLabel = (status) => {
  return STATUS_INFO[status]?.label || status
}

const getStatusColor = (status) => {
  return STATUS_INFO[status]?.color || '#999'
}

const handleNewBorrowRequest = (borrowing) => {
  borrowingRecords.push(borrowing)
}

const selectBorrowingDetail = (borrowing) => {
  selectedBorrowingDetail.value = borrowing
  showDetailModal.value = true
}

const allItems = ref([
  { id: 1, icon: '💻', name: 'Laptop', desc: 'Berbagai model', stock: 10 },
  { id: 2, icon: '📷', name: 'Kamera', desc: 'DSLR Professional', stock: 5 },
  { id: 3, icon: '🎬', name: 'Proyektor', desc: 'HD 4K', stock: 8 },
  { id: 4, icon: '🎙️', name: 'Microphone', desc: 'Studio Set', stock: 12 }
])

// Modal for borrowing details
const handleCloseDetailModal = () => {
  showDetailModal.value = false
  selectedBorrowingDetail.value = null
}
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

/* Borrowing Item Card */
.borrowing-item-card {
  background: #f9f9f9;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 12px;
  transition: all 0.3s ease;
}

.borrowing-item-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.item-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
  padding-bottom: 12px;
  border-bottom: 1px solid #e0e0e0;
}

.item-id {
  font-weight: bold;
  color: #333;
  font-family: 'Courier New', monospace;
}

.item-status {
  padding: 4px 8px;
  border-radius: 4px;
  color: white;
  font-size: 0.85rem;
  font-weight: 600;
}

.item-details {
  margin-bottom: 12px;
}

.item-details p {
  margin: 6px 0;
  font-size: 0.9rem;
  color: #555;
}

.item-actions {
  display: flex;
  gap: 8px;
  justify-content: flex-end;
}

.btn-action {
  padding: 6px 12px;
  background: #0B7285;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.85rem;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-action:hover {
  background: #089FB3;
  transform: translateY(-1px);
}

/* Modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-dialog {
  background: white;
  border-radius: 10px;
  padding: 24px;
  max-width: 600px;
  width: 90%;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
  position: relative;
}

.modal-close {
  position: absolute;
  top: 16px;
  right: 16px;
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #666;
}

.modal-close:hover {
  color: #333;
}
</style>
