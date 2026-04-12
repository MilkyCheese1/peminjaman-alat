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
            <p class="stat-label">Alat Tersedia</p>
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
          <a href="#" class="view-all" @click="activeTab = 'my-borrowings'">Lihat Semua</a>
        </div>
        
        <!-- Loading State -->
        <div v-if="loadingActiveBorrowings" class="loading-state">
          <p>📦 Memuat data peminjaman aktif...</p>
        </div>
        
        <!-- No Active Borrowings -->
        <div v-else-if="activeBorrowings.length === 0" class="empty-state">
          <p>✓ Tidak ada peminjaman aktif saat ini</p>
        </div>
        
        <!-- Active Borrowings List -->
        <div v-else class="borrowing-list">
          <div v-for="borrowing in activeBorrowings.slice(0, 3)" :key="borrowing.id_peminjaman" class="borrow-item">
            <span class="icon">{{ getEquipmentIcon(borrowing.equipment?.category?.nama_kategori) }}</span>
            <div class="info">
              <h4>{{ borrowing.equipment?.nama_alat || borrowing.nama_alat }}</h4>
              <p>📅 {{ formatDate(borrowing.tanggal_peminjaman || borrowing.borrow_date) }} - {{ formatDate(borrowing.tanggal_rencana_kembali || borrowing.planned_return_date) }}</p>
              <p v-if="borrowing.kode_verifikasi" class="code-detail">🔑 Kode: {{ borrowing.kode_verifikasi }}</p>
              <p v-if="borrowing.pickup_code" class="code-detail">📍 Pickup: {{ borrowing.pickup_code }}</p>
              <p v-if="borrowing.fine_amount > 0" class="fine-detail">⚠️ Denda: Rp {{ formatCurrency(borrowing.fine_amount) }}</p>
            </div>
            <div class="actions">
              <span :class="['status-badge', isOverdue(borrowing) ? 'overdue' : 'active']">
                {{ isOverdue(borrowing) ? '🔴 OVERDUE' : '🟢 Active' }}
              </span>
              <button v-if="isOverdue(borrowing)" class="btn-small warning" @click="handleExtendBorrowing(borrowing)">
                Perpanjang
              </button>
              <button v-else class="btn-small" @click="selectBorrowingDetail(borrowing)">
                Detail
              </button>
            </div>
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
import apiClient from '../../config/api'
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
const activeBorrowings = ref([])
const loadingActiveBorrowings = ref(false)
const availableEquipmentCount = ref(0)

onMounted(() => {
  const userStr = localStorage.getItem('user')
  if (userStr) {
    currentUser.value = JSON.parse(userStr)
    userName.value = currentUser.value.fullname || currentUser.value.username || 'User'
    loadActiveBorrowings()
    loadAvailableEquipment()
  }
})

// Fetch active borrowings from API
const loadActiveBorrowings = async () => {
  if (!currentUser.value?.id_user && !currentUser.value?.id) return
  
  loadingActiveBorrowings.value = true
  try {
    const userId = currentUser.value.id_user || currentUser.value.id
    const response = await apiClient.get(`/borrowings/user/${userId}`)
    
    if (response.data && response.data.data) {
      // Filter to only ACTIVE borrowings (status = picked_up)
      activeBorrowings.value = response.data.data.filter(b => b.status === 'picked_up')
    }
  } catch (error) {
    activeBorrowings.value = []
  } finally {
    loadingActiveBorrowings.value = false
  }
}

const myBorrowings = computed(() => {
  if (!currentUser.value) return []
  return getCustomerBorrowings(currentUser.value.id, borrowingRecords)
})

const borrowingsCount = computed(() => {
  return activeBorrowings.value.length
})

const overdueCount = computed(() => {
  return activeBorrowings.value.filter(b => isOverdue(b)).length
})

const historyCount = computed(() => {
  return myBorrowings.value.filter(b => b.status === 'returned' || b.status === 'cancelled').length
})

const availableItemsCount = computed(() => {
  return availableEquipmentCount.value
})

// Load available equipment from API
const loadAvailableEquipment = async () => {
  try {
    const response = await fetch('http://localhost:8000/api/statistics/dashboard')
    if (response.ok) {
      const data = await response.json()
      if (data.success && data.data && data.data.total_equipment !== undefined) {
        availableEquipmentCount.value = data.data.total_equipment
      }
    }
  } catch (error) {
    // Silently fail with default value
  }
}

// Check if borrowing is overdue
const isOverdue = (borrowing) => {
  if (!borrowing) return false
  const plannedReturnDate = new Date(borrowing.tanggal_rencana_kembali || borrowing.planned_return_date)
  const now = new Date()
  return now > plannedReturnDate
}

// Format date for display
const formatDateDisplay = (dateStr) => {
  if (!dateStr) return '-'
  const date = new Date(dateStr)
  const options = { day: 'numeric', month: 'short', year: 'numeric' }
  return date.toLocaleDateString('id-ID', options)
}

// Format currency
const formatCurrency = (value) => {
  if (!value) return '0'
  return value.toLocaleString('id-ID')
}

// Get equipment icon based on category
const getEquipmentIcon = (category) => {
  const categoryIcons = {
    'Elektronik': '💻',
    'Kamera': '📷',
    'Audio': '🎙️',
    'Proyektor': '🎬',
    'Alat Tulis': '📝',
    'Furniture': '🪑',
    'Lainnya': '📦'
  }
  return categoryIcons[category] || '📦'
}

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

// Handle extend borrowing request (future feature)
const handleExtendBorrowing = (borrowing) => {
  // TODO: Implement borrowing extension API call
}

// Dummy data (kept for reference but not used for available count)
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
  min-width: 40px;
}

.borrow-item .info {
  flex: 1;
}

.borrow-item h4 {
  margin: 0 0 5px 0;
  color: #1a1a2e;
  font-weight: 600;
}

.borrow-item p {
  margin: 0;
  font-size: 0.85rem;
  color: #666;
}

.code-detail {
  font-family: 'Courier New', monospace;
  background: #e8f5f9;
  padding: 2px 6px;
  border-radius: 3px;
  color: #0B7285;
  font-weight: 500;
  margin-top: 3px;
}

.fine-detail {
  color: #FF9F1C;
  font-weight: 600;
  margin-top: 3px;
}

.borrow-item .actions {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
  justify-content: flex-end;
  min-width: 160px;
}

.status-badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  white-space: nowrap;
}

.status-badge.active {
  background: #d4edda;
  color: #155724;
}

.status-badge.overdue {
  background: #f8d7da;
  color: #721c24;
}

.loading-state {
  text-align: center;
  padding: 40px 20px;
  color: #666;
}

.empty-state {
  text-align: center;
  padding: 40px 20px;
  color: #999;
  background: #f8f9fa;
  border-radius: 8px;
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
