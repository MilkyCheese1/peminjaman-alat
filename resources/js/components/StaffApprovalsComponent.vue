<template>
  <div class="approvals-container">
    <div class="approvals-header">
      <h2>📋 Persetujuan Peminjaman</h2>
      <p class="subtitle">Daftar peminjaman yang menunggu persetujuan Anda</p>
    </div>

    <div class="stats-overview">
      <div class="stat-card pending">
        <div class="stat-number">{{ pendingCount }}</div>
        <div class="stat-label">Menunggu Persetujuan</div>
      </div>
      <div class="stat-card approved">
        <div class="stat-number">{{ approvedTodayCount }}</div>
        <div class="stat-label">Disetujui Hari Ini</div>
      </div>
      <div class="stat-card rejected">
        <div class="stat-number">{{ rejectedCount }}</div>
        <div class="stat-label">Ditolak</div>
      </div>
    </div>

    <div class="approvals-list">
      <div v-if="borrowings.length === 0" class="empty-state">
        <p>✓ Tidak ada peminjaman yang menunggu persetujuan</p>
      </div>
      
      <div v-else class="borrowing-item" v-for="borrowing in pendingBorrowings" :key="borrowing.id_peminjaman">
        <div class="item-header">
          <div class="item-title">
            <h3>{{ borrowing.equipment?.nama_alat || borrowing.nama_alat || borrowing.equipment_name || 'N/A' }}</h3>
            <span class="category">{{ borrowing.category_name }}</span>
          </div>
          <div class="item-date">
            <small>Diminta: {{ formatDate(borrowing.tanggal_permohonan) }}</small>
          </div>
        </div>

        <div class="item-details">
          <div class="detail-row">
            <span class="label">Peminjam:</span>
            <span class="value">{{ borrowing.nama_peminjam }}</span>
          </div>
          <div class="detail-row">
            <span class="label">Durasi:</span>
            <span class="value">{{ borrowing.durasi_peminjaman }} hari</span>
          </div>
          <div class="detail-row">
            <span class="label">Tgl. Rencana Kembali:</span>
            <span class="value">{{ formatDate(borrowing.tanggal_rencana_kembali || borrowing.planned_return_date) }}</span>
          </div>
          <div class="detail-row" v-if="borrowing.catatan">
            <span class="label">Catatan:</span>
            <span class="value">{{ borrowing.catatan }}</span>
          </div>
        </div>

        <div class="item-actions">
          <button @click="approveBorrowing(borrowing)" class="btn btn-approve">
            ✓ Setujui
          </button>
          <button @click="rejectBorrowing(borrowing)" class="btn btn-reject">
            ✗ Tolak
          </button>
        </div>
      </div>
    </div>

    <!-- Rejection Modal -->
    <div v-if="showRejectModal" class="modal-overlay" @click.self="closeRejectModal">
      <div class="modal">
        <div class="modal-header">
          <h3>Alasan Penolakan</h3>
          <button @click="closeRejectModal" class="close-btn">✕</button>
        </div>
        <div class="modal-body">
          <label>Pilih atau ketik alasan penolakan:</label>
          <textarea 
            v-model="rejectReason" 
            placeholder="Contoh: Alat sedang tidak tersedia"
            class="reason-input"
          ></textarea>
        </div>
        <div class="modal-footer">
          <button @click="closeRejectModal" class="btn btn-secondary">Batal</button>
          <button @click="confirmReject" class="btn btn-reject">Tolak</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import apiClient from '@/config/api'
import { useToast } from '@/composables/useToast'

const borrowings = ref([])
const showRejectModal = ref(false)
const rejectReason = ref('')
const selectedBorrowing = ref(null)
const { success: showSuccess, error: showError } = useToast()

const pendingBorrowings = computed(() => {
  return borrowings.value.filter(b => b.status === 'applied')
})

const pendingCount = computed(() => pendingBorrowings.value.length)

const approvedTodayCount = computed(() => {
  const today = new Date().toISOString().split('T')[0]
  return borrowings.value.filter(b => {
    const approvedDate = new Date(b.tanggal_persetujuan || b.updated_at).toISOString().split('T')[0]
    return b.status === 'approved' && approvedDate === today
  }).length
})

const rejectedCount = computed(() => {
  return borrowings.value.filter(b => b.status === 'rejected').length
})

const loadBorrowings = async () => {
  try {
    const response = await apiClient.get('/borrowings?status=applied')
    if (response.data.success) {
      borrowings.value = response.data.data
    }
  } catch (error) {
    // Error handling
  }
}

const formatDate = (dateString) => {
  if (!dateString) return '-'
  return new Date(dateString).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const approveBorrowing = async (borrowing) => {
  try {
    const response = await apiClient.post(`/borrowings/${borrowing.id_peminjaman}/approve`, {})
    if (response.data.success) {
      // Remove from list
      borrowings.value = borrowings.value.filter(b => b.id_peminjaman !== borrowing.id_peminjaman)
      showSuccess('Peminjaman berhasil disetujui!')
    }
  } catch (error) {
    showError('Gagal menyetujui peminjaman: ' + (error.response?.data?.message || error.message))
  }
}

const rejectBorrowing = (borrowing) => {
  selectedBorrowing.value = borrowing
  rejectReason.value = ''
  showRejectModal.value = true
}

const closeRejectModal = () => {
  showRejectModal.value = false
  selectedBorrowing.value = null
  rejectReason.value = ''
}

const confirmReject = async () => {
  if (!rejectReason.value.trim()) {
    showError('Silakan masukkan alasan penolakan')
    return
  }

  try {
    const response = await apiClient.post(`/borrowings/${selectedBorrowing.value.id_peminjaman}/reject`, {
      alasan_penolakan: rejectReason.value
    })
    if (response.data.success) {
      borrowings.value = borrowings.value.filter(b => b.id_peminjaman !== selectedBorrowing.value.id_peminjaman)
      closeRejectModal()
      showSuccess('Peminjaman berhasil ditolak!')
    }
  } catch (error) {
    showError('Gagal menolak peminjaman: ' + (error.response?.data?.message || error.message))
  }
}

onMounted(() => {
  loadBorrowings()
  // Auto-refresh every 30 seconds
  const interval = setInterval(loadBorrowings, 30000)
  return () => clearInterval(interval)
})
</script>

<style scoped>
.approvals-container {
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

.approvals-header {
  margin-bottom: 20px;
}

.approvals-header h2 {
  margin: 0 0 5px 0;
  color: #1a1a2e;
}

.subtitle {
  margin: 0;
  color: #666;
  font-size: 0.95rem;
}

.stats-overview {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 15px;
  margin-bottom: 25px;
}

.stat-card {
  padding: 20px;
  border-radius: 8px;
  text-align: center;
  color: white;
}

.stat-card.pending {
  background: linear-gradient(135deg, #ff6b6b 0%, #ff5252 100%);
}

.stat-card.approved {
  background: linear-gradient(135deg, #51cf66 0%, #37b24d 100%);
}

.stat-card.rejected {
  background: linear-gradient(135deg, #ffb84d 0%, #ff9800 100%);
}

.stat-number {
  font-size: 2rem;
  font-weight: bold;
  margin-bottom: 5px;
}

.stat-label {
  font-size: 0.85rem;
  opacity: 0.9;
}

.approvals-list {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.empty-state {
  text-align: center;
  padding: 40px 20px;
  color: #666;
}

.borrowing-item {
  padding: 20px;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  margin-bottom: 15px;
  transition: all 0.2s ease;
}

.borrowing-item:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  border-color: #0b7285;
}

.item-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 15px;
  gap: 15px;
}

.item-title h3 {
  margin: 0 0 5px 0;
  color: #1a1a2e;
}

.category {
  display: inline-block;
  background: #f0f0f0;
  color: #666;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.8rem;
  margin-top: 5px;
}

.item-date small {
  color: #999;
}

.item-details {
  background: #f9f9f9;
  padding: 15px;
  border-radius: 6px;
  margin-bottom: 15px;
}

.detail-row {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
  border-bottom: 1px solid #e0e0e0;
  font-size: 0.95rem;
}

.detail-row:last-child {
  border-bottom: none;
}

.detail-row .label {
  font-weight: 600;
  color: #666;
  min-width: 150px;
}

.detail-row .value {
  color: #1a1a2e;
  text-align: right;
  flex: 1;
}

.item-actions {
  display: flex;
  gap: 10px;
}

.btn {
  flex: 1;
  padding: 10px 16px;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-approve {
  background-color: #51cf66;
  color: white;
}

.btn-approve:hover {
  background-color: #37b24d;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(81, 207, 102, 0.3);
}

.btn-reject {
  background-color: #ff6b6b;
  color: white;
}

.btn-reject:hover {
  background-color: #ff5252;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(255, 107, 107, 0.3);
}

.btn-secondary {
  background-color: #e0e0e0;
  color: #333;
}

.btn-secondary:hover {
  background-color: #d0d0d0;
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

.modal {
  background: white;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
  width: 90%;
  max-width: 500px;
  overflow: hidden;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 1px solid #e0e0e0;
}

.modal-header h3 {
  margin: 0;
  color: #1a1a2e;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #999;
}

.modal-body {
  padding: 20px;
}

.modal-body label {
  display: block;
  margin-bottom: 10px;
  font-weight: 600;
  color: #1a1a2e;
}

.reason-input {
  width: 100%;
  min-height: 120px;
  padding: 12px;
  border: 1px solid #e0e0e0;
  border-radius: 6px;
  font-family: inherit;
  font-size: 0.95rem;
  resize: vertical;
}

.reason-input:focus {
  outline: none;
  border-color: #0b7285;
  box-shadow: 0 0 0 3px rgba(11, 114, 133, 0.1);
}

.modal-footer {
  display: flex;
  gap: 10px;
  padding: 20px;
  border-top: 1px solid #e0e0e0;
}

.modal-footer .btn {
  flex: 1;
}

@media (max-width: 768px) {
  .item-header {
    flex-direction: column;
  }

  .detail-row {
    flex-direction: column;
  }

  .detail-row .label {
    min-width: auto;
    margin-bottom: 5px;
  }

  .detail-row .value {
    text-align: left;
  }

  .item-actions {
    flex-direction: column;
  }
}
</style>
