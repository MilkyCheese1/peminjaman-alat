<template>
  <div class="borrowing-approval-wrapper">
    <h2>🎯 Manajemen Peminjaman</h2>

    <!-- Tabs -->
    <div class="tabs">
      <button 
        v-for="tab in tabs"
        :key="tab.id"
        @click="activeTab = tab.id"
        :class="['tab-btn', { active: activeTab === tab.id }]"
      >
        {{ tab.icon }} {{ tab.label }}
        <span v-if="tab.count > 0" class="badge">{{ tab.count }}</span>
      </button>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
      <!-- Pending Approvals -->
      <div v-if="activeTab === 'pending'" class="tab-pane">
        <h3>⏳ Menunggu Persetujuan</h3>
        <div v-if="pendingApprovals.length === 0" class="empty-state">
          <p>Tidak ada permintaan peminjaman yang menunggu persetujuan</p>
        </div>
        <div v-else class="borrowing-list">
          <div v-for="bor in pendingApprovals" :key="bor.id" class="borrowing-card">
            <div class="card-header">
              <div class="borrowing-id">{{ bor.id }}</div>
              <div class="card-actions">
                <button @click="selectBorrowing(bor)" class="btn-view">👀 Lihat Detail</button>
              </div>
            </div>
            <div class="card-body">
              <div class="info-row">
                <span class="label">Peminjam:</span>
                <span>{{ bor.customerName }}</span>
              </div>
              <div class="info-row">
                <span class="label">Email:</span>
                <span>{{ bor.customerEmail }}</span>
              </div>
              <div class="info-row">
                <span class="label">Alat:</span>
                <span>{{ bor.equipmentName }}</span>
              </div>
              <div class="info-row">
                <span class="label">Alasan:</span>
                <span>{{ bor.borrowReason || '(Tidak ada)' }}</span>
              </div>
            </div>
            <div class="card-actions approval">
              <button @click="approveBorrowing(bor)" class="btn-approve">✅ Setujui</button>
              <button @click="showRejectForm(bor)" class="btn-reject">❌ Tolak</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Ready for Pickup -->
      <div v-if="activeTab === 'pickup'" class="tab-pane">
        <h3>📦 Siap Diambil</h3>
        <div v-if="readyForPickup.length === 0" class="empty-state">
          <p>Tidak ada alat yang siap diambil</p>
        </div>
        <div v-else class="borrowing-list">
          <div v-for="bor in readyForPickup" :key="bor.id" class="borrowing-card">
            <div class="card-header">
              <div class="borrowing-id">{{ bor.id }}</div>
              <div class="pickup-code">Kode: <strong>{{ bor.pickupCode }}</strong></div>
            </div>
            <div class="card-body">
              <div class="info-row">
                <span class="label">Peminjam:</span>
                <span>{{ bor.customerName }}</span>
              </div>
              <div class="info-row">
                <span class="label">Alat:</span>
                <span>{{ bor.equipmentName }}</span>
              </div>
              <div class="info-row">
                <span class="label">Kode Berlaku:</span>
                <span>{{ formatDate(pickupCodeExpiry(bor)) }}</span>
              </div>
            </div>
            <div class="card-actions pickup">
              <p class="status-info">⏳ Menunggu customer mengambil alat dengan kode di atas</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Pending Returns -->
      <div v-if="activeTab === 'returns'" class="tab-pane">
        <h3>🚚 Menunggu Pengembalian Alat</h3>
        <div v-if="pendingReturns.length === 0" class="empty-state">
          <p>Tidak ada alat yang menunggu pengembalian</p>
        </div>
        <div v-else class="borrowing-list">
          <div v-for="bor in pendingReturns" :key="bor.id" class="borrowing-card" :class="{ overdue: isOverdue(bor.plannedReturnDate) }">
            <div class="card-header">
              <div class="borrowing-id">{{ bor.id }}</div>
              <div v-if="isOverdue(bor.plannedReturnDate)" class="overdue-badge">🚨 TERLAMBAT</div>
            </div>
            <div class="card-body">
              <div class="info-row">
                <span class="label">Peminjam:</span>
                <span>{{ bor.customerName }}</span>
              </div>
              <div class="info-row">
                <span class="label">Alat:</span>
                <span>{{ bor.equipmentName }}</span>
              </div>
              <div class="info-row">
                <span class="label">Target Kembali:</span>
                <span>{{ formatDate(bor.plannedReturnDate) }}</span>
              </div>
              <div class="info-row">
                <span class="label">Status:</span>
                <span>{{ bor.returnVerifiedByCustomer ? '✅ Customer siap return' : '⏳ Menunggu customer' }}</span>
              </div>
            </div>
            <div class="card-actions return">
              <button @click="selectBorrowing(bor)" class="btn-verify">✅ Verifikasi Pengembalian</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Completed -->
      <div v-if="activeTab === 'completed'" class="tab-pane">
        <h3>✔️ Selesai</h3>
        <div v-if="completedBorrowings.length === 0" class="empty-state">
          <p>Tidak ada peminjaman yang selesai</p>
        </div>
        <div v-else class="borrowing-list">
          <div v-for="bor in completedBorrowings" :key="bor.id" class="borrowing-card completed">
            <div class="card-header">
              <div class="borrowing-id">{{ bor.id }}</div>
              <div class="completion-date">{{ formatDate(bor.actualReturnDate) }}</div>
            </div>
            <div class="card-body">
              <div class="info-row">
                <span class="label">Peminjam:</span>
                <span>{{ bor.customerName }}</span>
              </div>
              <div class="info-row">
                <span class="label">Alat:</span>
                <span>{{ bor.equipmentName }}</span>
              </div>
              <div class="info-row">
                <span class="label">Denda:</span>
                <span>Rp {{ bor.fineAmount.toLocaleString('id-ID') }}</span>
              </div>
              <div class="info-row">
                <span class="label">Status Denda:</span>
                <span>{{ bor.finePaid ? '✅ Lunas' : '⏳ Belum Bayar' }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Rejection Modal -->
    <div v-if="showRejectModal" class="modal-overlay" @click.self="showRejectModal = false">
      <div class="modal-content">
        <h3>❌ Tolak Permintaan Peminjaman</h3>
        <div class="modal-body">
          <p>ID: <strong>{{ selectedBorrowingForReject?.id }}</strong></p>
          <textarea 
            v-model="rejectReason"
            placeholder="Jelaskan alasan penolakan..."
            rows="4"
          ></textarea>
        </div>
        <div class="modal-actions">
          <button @click="submitReject" class="btn-primary">❌ Tolak</button>
          <button @click="showRejectModal = false" class="btn-secondary">Batal</button>
        </div>
      </div>
    </div>

    <!-- Detail Modal -->
    <div v-if="showDetailModal && selectedBorrowingDetail" class="modal-overlay" @click.self="showDetailModal = false">
      <div class="modal-content large">
        <div class="modal-header">
          <h3>📋 Detail Peminjaman</h3>
          <button @click="showDetailModal = false" class="close-btn">✕</button>
        </div>
        <div class="modal-body">
          <div class="detail-grid">
            <div class="detail-section">
              <h4>👤 Data Peminjam</h4>
              <p><strong>Nama:</strong> {{ selectedBorrowingDetail.customerName }}</p>
              <p><strong>Email:</strong> {{ selectedBorrowingDetail.customerEmail }}</p>
            </div>
            <div class="detail-section">
              <h4>📦 Data Alat</h4>
              <p><strong>Alat:</strong> {{ selectedBorrowingDetail.equipmentName }}</p>
              <p><strong>Jumlah:</strong> {{ selectedBorrowingDetail.equipmentQuantity }} unit</p>
            </div>
            <div class="detail-section">
              <h4>📅 Timeline</h4>
              <p><strong>Tgl Pinjam:</strong> {{ formatDate(selectedBorrowingDetail.borrowDate) }}</p>
              <p><strong>Tgl Target Kembali:</strong> {{ formatDate(selectedBorrowingDetail.plannedReturnDate) }}</p>
            </div>
            <div class="detail-section">
              <h4>📝 Alasan</h4>
              <p>{{ selectedBorrowingDetail.borrowReason || '(Tidak ada)' }}</p>
            </div>
          </div>
        </div>
        <div class="modal-actions">
          <button @click="showDetailModal = false" class="btn-secondary">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import {
  getPendingApprovals,
  getReadyForPickup,
  getPendingReturns,
  getCompletedBorrowings,
  approveBorrowingRequest,
  rejectBorrowingRequest,
  generatePickupCodeForBorrowing,
  borrowingRecords
} from '../../data/borrowingData.js'
import { formatDate, isOverdue } from '../../data/borrowingStatuses.js'

const emit = defineEmits(['approve', 'reject', 'pickup-ready'])

const activeTab = ref('pending')
const showRejectModal = ref(false)
const showDetailModal = ref(false)
const selectedBorrowingForReject = ref(null)
const selectedBorrowingDetail = ref(null)
const rejectReason = ref('')

const tabs = computed(() => [
  {
    id: 'pending',
    label: 'Persetujuan',
    icon: '⏳',
    count: pendingApprovals.value.length
  },
  {
    id: 'pickup',
    label: 'Siap Diambil',
    icon: '📦',
    count: readyForPickup.value.length
  },
  {
    id: 'returns',
    label: 'Pengembalian',
    icon: '🚚',
    count: pendingReturns.value.length
  },
  {
    id: 'completed',
    label: 'Selesai',
    icon: '✔️',
    count: completedBorrowings.value.length
  }
])

const pendingApprovals = computed(() => getPendingApprovals(borrowingRecords))
const readyForPickup = computed(() => getReadyForPickup(borrowingRecords))
const pendingReturns = computed(() => getPendingReturns(borrowingRecords))
const completedBorrowings = computed(() => getCompletedBorrowings(borrowingRecords))

const pickupCodeExpiry = (borrowing) => {
  if (!borrowing.pickupCodeGeneratedAt) return new Date()
  const expiryDate = new Date(borrowing.pickupCodeGeneratedAt)
  expiryDate.setDate(expiryDate.getDate() + 7)
  return expiryDate
}

const approveBorrowing = (borrowing) => {
  const approved = approveBorrowingRequest(borrowing, 'staff-1', 'Disetujui')
  const ready = generatePickupCodeForBorrowing(approved)
  
  const index = borrowingRecords.findIndex(b => b.id === borrowing.id)
  if (index !== -1) {
    borrowingRecords[index] = ready
  }

  emit('approve', ready)
}

const showRejectForm = (borrowing) => {
  selectedBorrowingForReject.value = borrowing
  rejectReason.value = ''
  showRejectModal.value = true
}

const submitReject = () => {
  const rejected = rejectBorrowingRequest(selectedBorrowingForReject.value, rejectReason.value)
  
  const index = borrowingRecords.findIndex(b => b.id === rejected.id)
  if (index !== -1) {
    borrowingRecords[index] = rejected
  }

  emit('reject', rejected)
  showRejectModal.value = false
}

const selectBorrowing = (borrowing) => {
  selectedBorrowingDetail.value = borrowing
  showDetailModal.value = true
}
</script>

<style scoped>
.borrowing-approval-wrapper {
  background: white;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.borrowing-approval-wrapper h2 {
  margin-top: 0;
  color: #333;
  font-size: 1.5rem;
  margin-bottom: 20px;
}

.tabs {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
  border-bottom: 2px solid #e0e0e0;
  overflow-x: auto;
}

.tab-btn {
  padding: 12px 16px;
  border: none;
  background: transparent;
  cursor: pointer;
  font-size: 0.95rem;
  font-weight: 600;
  color: #666;
  border-bottom: 3px solid transparent;
  transition: all 0.3s ease;
  white-space: nowrap;
  position: relative;
}

.tab-btn:hover {
  color: #333;
}

.tab-btn.active {
  color: #007bff;
  border-bottom-color: #007bff;
}

.badge {
  display: inline-block;
  background: #dc3545;
  color: white;
  border-radius: 12px;
  padding: 2px 6px;
  font-size: 0.8rem;
  margin-left: 6px;
  font-weight: bold;
}

.tab-content {
  min-height: 300px;
}

.tab-pane {
  display: none;
}

.tab-pane.active {
  display: block;
}

.tab-pane h3 {
  margin-top: 0;
  color: #333;
  margin-bottom: 20px;
}

.empty-state {
  text-align: center;
  padding: 40px 20px;
  color: #999;
  background: #f9f9f9;
  border-radius: 8px;
  border: 1px dashed #ddd;
}

.borrowing-list {
  display: grid;
  gap: 16px;
}

.borrowing-card {
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  overflow: hidden;
  transition: all 0.3s ease;
}

.borrowing-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.borrowing-card.overdue {
  border-left: 4px solid #dc3545;
  background: #fff8f8;
}

.borrowing-card.completed {
  background: #f0f8f0;
  border-left: 4px solid #28a745;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 16px;
  background: #f9f9f9;
  border-bottom: 1px solid #e0e0e0;
}

.borrowing-id {
  font-weight: bold;
  color: #333;
  font-family: 'Courier New', monospace;
}

.pickup-code {
  color: #666;
  font-size: 0.9rem;
}

.pickup-code strong {
  background: #e3f2fd;
  padding: 2px 6px;
  border-radius: 3px;
  color: #1976d2;
  font-family: 'Courier New', monospace;
}

.completion-date {
  color: #666;
  font-size: 0.9rem;
}

.overdue-badge {
  background: #dc3545;
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.85rem;
  font-weight: 600;
}

.card-body {
  padding: 12px 16px;
}

.info-row {
  display: flex;
  justify-content: space-between;
  padding: 6px 0;
  border-bottom: 1px solid #f0f0f0;
  font-size: 0.95rem;
}

.info-row:last-child {
  border-bottom: none;
}

.info-row .label {
  font-weight: 600;
  color: #666;
}

.card-actions {
  padding: 12px 16px;
  border-top: 1px solid #e0e0e0;
  display: flex;
  gap: 8px;
  justify-content: flex-end;
  flex-wrap: wrap;
}

.card-actions button {
  padding: 6px 12px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.9rem;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-view {
  background: #17a2b8;
  color: white;
}

.btn-view:hover {
  background: #138496;
}

.btn-approve {
  background: #28a745;
  color: white;
}

.btn-approve:hover {
  background: #218838;
}

.btn-reject {
  background: #dc3545;
  color: white;
}

.btn-reject:hover {
  background: #c82333;
}

.btn-verify {
  background: #007bff;
  color: white;
}

.btn-verify:hover {
  background: #0056b3;
}

.card-actions.pickup {
  justify-content: flex-start;
}

.status-info {
  margin: 0;
  color: #666;
  font-size: 0.95rem;
  font-style: italic;
}

/* Modal Styles */
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

.modal-content {
  background: white;
  border-radius: 10px;
  padding: 24px;
  max-width: 500px;
  width: 90%;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.modal-content.large {
  max-width: 700px;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.modal-header h3 {
  margin: 0;
  color: #333;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #666;
  padding: 0;
  transition: color 0.3s ease;
}

.close-btn:hover {
  color: #333;
}

.modal-content h3 {
  margin-top: 0;
  color: #333;
  margin-bottom: 16px;
}

.modal-body {
  margin-bottom: 20px;
}

.modal-body p {
  margin: 8px 0;
  color: #555;
}

.modal-body textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-family: inherit;
  font-size: 1rem;
  resize: vertical;
}

.modal-body textarea:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.detail-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

.detail-section h4 {
  margin-top: 0;
  color: #333;
  margin-bottom: 12px;
}

.detail-section p {
  margin: 8px 0;
  color: #555;
  font-size: 0.95rem;
}

.modal-actions {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
}

.btn-primary,
.btn-secondary {
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-primary {
  background: #007bff;
  color: white;
}

.btn-primary:hover {
  background: #0056b3;
}

.btn-secondary {
  background: #6c757d;
  color: white;
}

.btn-secondary:hover {
  background: #545b62;
}

@media (max-width: 768px) {
  .tabs {
    gap: 5px;
  }

  .tab-btn {
    padding: 10px 12px;
    font-size: 0.85rem;
  }

  .badge {
    font-size: 0.7rem;
    padding: 1px 4px;
  }

  .detail-grid {
    grid-template-columns: 1fr;
  }

  .modal-content {
    padding: 16px;
  }

  .card-actions {
    flex-direction: column;
  }

  .card-actions button {
    width: 100%;
  }
}
</style>
