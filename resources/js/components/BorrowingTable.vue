<template>
  <div class="borrowing-table-container">
    <!-- Header dengan Search dan Filters -->
    <div class="table-header">
      <div class="search-box">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="🔍 Cari peminjaman..."
          class="search-input"
        />
      </div>
      <div class="header-actions">
        <select v-model="statusFilter" class="status-filter">
          <option value="">Semua Status</option>
          <option value="applied">Menunggu Persetujuan</option>
          <option value="approved">Disetujui</option>
          <option value="ready_for_pickup">Siap Diambil</option>
          <option value="picked_up">Sedang Dipinjam</option>
          <option value="returned">Dikembalikan</option>
          <option value="rejected">Ditolak</option>
        </select>
        <button @click="showAddModal = true" class="btn btn-primary" v-if="canCreate">
          ➕ Buat Peminjaman
        </button>
      </div>
    </div>

    <!-- Info Stats -->
    <div class="stats-row">
      <div class="stat-box">
        <span class="label">Total Peminjaman:</span>
        <span class="value">{{ totalBorrowings }}</span>
      </div>
      <div class="stat-box">
        <span class="label">Menunggu:</span>
        <span class="value pending">{{ pendingCount }}</span>
      </div>
      <div class="stat-box">
        <span class="label">Aktif:</span>
        <span class="value active">{{ activeCount }}</span>
      </div>
      <div class="stat-box">
        <span class="label">Terlambat:</span>
        <span class="value overdue">{{ overdueCount }}</span>
      </div>
    </div>

    <!-- Tabel Peminjaman -->
    <div class="table-wrapper">
      <table class="borrowing-table" v-if="filteredBorrowings.length > 0">
        <thead>
          <tr>
            <th>No.</th>
            <th>ID Peminjaman</th>
            <th>Peminjam</th>
            <th>Alat</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(borrowing, index) in filteredBorrowings" :key="borrowing.id_peminjaman">
            <td>{{ index + 1 }}</td>
            <td>
              <strong>#{{ borrowing.id_peminjaman }}</strong>
            </td>
            <td>{{ borrowing.user?.nama_lengkap || borrowing.nama_peminjam || 'N/A' }}</td>
            <td>{{ borrowing.equipment?.nama_alat || borrowing.nama_alat || 'N/A' }}</td>
            <td>{{ formatDate(borrowing.tanggal_peminjaman || borrowing.borrow_date) }}</td>
            <td>{{ formatDate(borrowing.tanggal_rencana_kembali || borrowing.planned_return_date) }}</td>
            <td>
              <span :class="['status-badge', borrowing.status]">
                {{ getStatusLabel(borrowing.status) }}
              </span>
            </td>
            <td>
              <div class="action-buttons">
                <button
                  @click="openDetail(borrowing)"
                  class="btn-action btn-detail"
                  title="Detail"
                >
                  👁️
                </button>
                <button
                  v-if="borrowing.status === 'applied' && canApprove"
                  @click="approveBorrowing(borrowing)"
                  class="btn-action btn-approve"
                  title="Setujui"
                >
                  ✅
                </button>
                <button
                  v-if="borrowing.status === 'applied' && canApprove"
                  @click="rejectBorrowing(borrowing)"
                  class="btn-action btn-reject"
                  title="Tolak"
                >
                  ❌
                </button>
                <button
                  v-if="borrowing.status === 'ready_for_pickup' && borrowing.user?.id_user === currentUserId"
                  @click="openPickupModal(borrowing)"
                  class="btn-action btn-pickup"
                  title="Ambil Kode"
                >
                  📋
                </button>
                <button
                  v-if="borrowing.status === 'picked_up' && canVerifyReturn"
                  @click="openReturnModal(borrowing)"
                  class="btn-action btn-return"
                  title="Verifikasi Kembali"
                >
                  🔄
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Empty State -->
      <div v-else class="empty-state">
        <p>📭 Tidak ada peminjaman yang ditemukan</p>
      </div>
    </div>

    <!-- Detail Modal -->
    <div v-if="showDetailModal && selectedBorrowing" class="modal-overlay">
      <div class="modal-content modal-large">
        <div class="modal-header">
          <h3>📋 Detail Peminjaman #{{ selectedBorrowing.id_peminjaman }}</h3>
          <button @click="closeModals()" class="btn-close">✕</button>
        </div>

        <div class="modal-body">
          <div class="detail-section">
            <h4>Informasi Peminjam</h4>
            <div class="info-row">
              <span class="label">Nama:</span>
              <span>{{ selectedBorrowing.user?.nama_lengkap }}</span>
            </div>
            <div class="info-row">
              <span class="label">Email:</span>
              <span>{{ selectedBorrowing.user?.email }}</span>
            </div>
            <div class="info-row">
              <span class="label">Telepon:</span>
              <span>{{ selectedBorrowing.user?.phone || '-' }}</span>
            </div>
          </div>

          <div class="detail-section">
            <h4>Informasi Alat</h4>
            <div class="info-row">
              <span class="label">Nama Alat:</span>
              <span>{{ selectedBorrowing.equipment?.nama_alat || selectedBorrowing.nama_alat || 'N/A' }}</span>
            </div>
            <div class="info-row">
              <span class="label">Kategori:</span>
              <span>{{ selectedBorrowing.equipment?.category?.nama_kategori || selectedBorrowing.category_name || '-' }}</span>
            </div>
            <div class="info-row">
              <span class="label">Denda/Hari:</span>
              <span>Rp {{ (selectedBorrowing.equipment?.fine_per_day || 50000).toLocaleString('id-ID') }}</span>
            </div>
          </div>

          <div class="detail-section">
            <h4>Informasi Peminjaman</h4>
            <div class="info-row">
              <span class="label">Tanggal Pinjam:</span>
              <span>{{ formatDate(selectedBorrowing.tanggal_peminjaman || selectedBorrowing.borrow_date) }}</span>
            </div>
            <div class="info-row">
              <span class="label">Tanggal Rencana Kembali:</span>
              <span>{{ formatDate(selectedBorrowing.tanggal_rencana_kembali || selectedBorrowing.planned_return_date) }}</span>
            </div>
            <div class="info-row" v-if="selectedBorrowing.actual_return_date || selectedBorrowing.tanggal_kembali">
              <span class="label">Tanggal Kembali Aktual:</span>
              <span>{{ formatDate(selectedBorrowing.actual_return_date || selectedBorrowing.tanggal_kembali) }}</span>
            </div>
            <div class="info-row">
              <span class="label">Status:</span>
              <span>
                <span :class="['status-badge', selectedBorrowing.status]">
                  {{ getStatusLabel(selectedBorrowing.status) }}
                </span>
              </span>
            </div>
            <div class="info-row" v-if="selectedBorrowing.fine_amount > 0">
              <span class="label">Denda Terhitung:</span>
              <span>Rp {{ selectedBorrowing.fine_amount.toLocaleString('id-ID') }}</span>
            </div>
          </div>

          <div class="detail-section" v-if="selectedBorrowing.pickup_code">
            <h4>Kode Pickup</h4>
            <div class="pickup-code">{{ selectedBorrowing.pickup_code }}</div>
          </div>
        </div>

        <div class="modal-footer">
          <button @click="closeModals()" class="btn btn-secondary">Tutup</button>
        </div>
      </div>
    </div>

    <!-- Pickup Code Modal -->
    <div v-if="showPickupModal && selectedBorrowing" class="modal-overlay">
      <div class="modal-content modal-small">
        <div class="modal-header">
          <h3>📋 Kode Pengambilan</h3>
          <button @click="closeModals()" class="btn-close">✕</button>
        </div>
        <div class="modal-body">
          <p>Kode pengambilan untuk alat <strong>{{ selectedBorrowing.equipment?.nama_alat || selectedBorrowing.nama_alat }}</strong>:</p>
          <div class="pickup-code">{{ selectedBorrowing.pickup_code || 'Belum ada kode' }}</div>
          <p class="note">Berikan kode ini kepada staff untuk pengambilan alat</p>
        </div>
        <div class="modal-footer">
          <button @click="closeModals()" class="btn btn-secondary">Tutup</button>
        </div>
      </div>
    </div>

    <!-- Return Verification Modal -->
    <div v-if="showReturnModal && selectedBorrowing" class="modal-overlay">
      <div class="modal-content">
        <div class="modal-header">
          <h3>🔄 Verifikasi Pengembalian</h3>
          <button @click="closeModals()" class="btn-close">✕</button>
        </div>

        <form @submit.prevent="verifyReturn" class="modal-form">
          <div class="detail-section">
            <h4>Alat yang Dikembalikan</h4>
            <div class="info-row">
              <span class="label">Nama Alat:</span>
              <span>{{ selectedBorrowing.equipment?.nama_alat || selectedBorrowing.nama_alat }}</span>
            </div>
            <div class="info-row">
              <span class="label">Dipinjam Sejak:</span>
              <span>{{ formatDate(selectedBorrowing.tanggal_peminjaman || selectedBorrowing.borrow_date) }}</span>
            </div>
            <div class="info-row">
              <span class="label\">Rencananya Dikembalikan:</span>
              <span>{{ formatDate(selectedBorrowing.tanggal_rencana_kembali || selectedBorrowing.planned_return_date) }}</span>
            </div>
          </div>

          <div class="form-group">
            <label>Kondisi Alat *</label>
            <select v-model="returnData.kondisi" required>
              <option value="">Pilih Kondisi</option>
              <option value="baik">Baik</option>
              <option value="sedang">Sedang Rusak</option>
              <option value="rusak">Rusak Berat</option>
            </select>
          </div>

          <div class="form-group">
            <label>Catatan Kondisi</label>
            <textarea
              v-model="returnData.catatan_kondisi"
              placeholder="Deskripsi detail kondisi alat"
              rows="3"
            ></textarea>
          </div>

          <div class="form-group">
            <label>Tanggal Kembali *</label>
            <input
              v-model="returnData.tanggal_kembali"
              type="date"
              required
            />
          </div>

          <div v-if="formError" class="alert alert-error">
            {{ formError }}
          </div>

          <div class="modal-footer">
            <button type="button" @click="closeModals()" class="btn btn-secondary">
              Batal
            </button>
            <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
              {{ isSubmitting ? '⏳ Memproses...' : 'Verifikasi Kembali' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="loading-overlay">
      <div class="spinner">⏳</div>
      <p>Memuat data...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, defineProps } from 'vue'
import apiClient from '@/config/api'
import { useToast } from '@/composables/useToast'

const props = defineProps({
  canApprove: {
    type: Boolean,
    default: false
  },
  canVerifyReturn: {
    type: Boolean,
    default: false
  },
  canCreate: {
    type: Boolean,
    default: false
  },
  currentUserId: {
    type: [Number, String],
    default: null
  }
})

const borrowings = ref([])
const searchQuery = ref('')
const statusFilter = ref('')
const showDetailModal = ref(false)
const showPickupModal = ref(false)
const showReturnModal = ref(false)
const showAddModal = ref(false)
const isLoading = ref(false)
const isSubmitting = ref(false)
const selectedBorrowing = ref(null)
const formError = ref('')

const returnData = ref({
  kondisi: '',
  catatan_kondisi: '',
  tanggal_kembali: new Date().toISOString().split('T')[0],
})
const { success: showSuccess, error: showError } = useToast()

// Computed Properties
const filteredBorrowings = computed(() => {
  return borrowings.value.filter((borrowing) => {
    const matchSearch =
      (borrowing.user?.nama_lengkap || borrowing.nama_peminjam || '')?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      (borrowing.equipment?.nama_alat || borrowing.nama_alat || '')?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      borrowing.id_peminjaman?.toString().includes(searchQuery.value)

    const matchStatus = !statusFilter.value || borrowing.status === statusFilter.value

    return matchSearch && matchStatus
  })
})

const totalBorrowings = computed(() => borrowings.value.length)

const pendingCount = computed(() => {
  return borrowings.value.filter((b) => b.status === 'applied').length
})

const activeCount = computed(() => {
  return borrowings.value.filter((b) => b.status === 'picked_up').length
})

const overdueCount = computed(() => {
  const now = new Date()
  return borrowings.value.filter((b) => {
    const dueDate = new Date(b.tanggal_rencana_kembali || b.planned_return_date)
    return b.status === 'picked_up' && now > dueDate
  }).length
})

// Methods
const loadBorrowings = async () => {
  try {
    isLoading.value = true
    const response = await apiClient.get('/borrowings')
    if (response.data.success) {
      borrowings.value = response.data.data
    }
  } catch (error) {
    showError('Gagal memuat data peminjaman')
  } finally {
    isLoading.value = false
  }
}

const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const getStatusLabel = (status) => {
  const labels = {
    applied: '📝 Menunggu Persetujuan',
    approved: '✅ Disetujui',
    ready_for_pickup: '📦 Siap Diambil',
    picked_up: '🔄 Sedang Dipinjam',
    returned: '🏁 Dikembalikan',
    rejected: '❌ Ditolak'
  }
  return labels[status] || status
}

const openDetail = (borrowing) => {
  selectedBorrowing.value = borrowing
  showDetailModal.value = true
}

const openPickupModal = (borrowing) => {
  selectedBorrowing.value = borrowing
  showPickupModal.value = true
}

const openReturnModal = (borrowing) => {
  selectedBorrowing.value = borrowing
  returnData.value = {
    kondisi: '',
    catatan_kondisi: '',
    tanggal_kembali: new Date().toISOString().split('T')[0],
  }
  showReturnModal.value = true
  formError.value = ''
}

const closeModals = () => {
  showDetailModal.value = false
  showPickupModal.value = false
  showReturnModal.value = false
  showAddModal.value = false
  selectedBorrowing.value = null
  formError.value = ''
}

const calculateFine = () => {
  if (!selectedBorrowing.value) return 0
  
  const dueDate = new Date(selectedBorrowing.value.tanggal_rencana_kembali || selectedBorrowing.value.planned_return_date)
  const returnDate = new Date(returnData.value.tanggal_kembali)
  
  if (returnDate <= dueDate) return 0
  
  const daysLate = Math.ceil((returnDate - dueDate) / (1000 * 60 * 60 * 24))
  const finePerDay = 50000
  const maxFine = daysLate * finePerDay
  
  return Math.min(maxFine, 30 * finePerDay) // Max 30 days
}

const approveBorrowing = async (borrowing) => {
  try {
    if (!confirm('Setujui peminjaman ini?')) return
    
    const response = await apiClient.post(`/borrowings/${borrowing.id_peminjaman}/approve`)
    if (response.data.success) {
      const index = borrowings.value.findIndex((b) => b.id_peminjaman === borrowing.id_peminjaman)
      if (index > -1) {
        borrowings.value[index] = response.data.data
      }
      showSuccess('Peminjaman berhasil disetujui!')
    }
  } catch (error) {
    showError(error.response?.data?.message || 'Gagal menyetujui peminjaman')
  }
}

const rejectBorrowing = async (borrowing) => {
  try {
    if (!confirm('Tolak peminjaman ini?')) return
    
    const response = await apiClient.post(`/borrowings/${borrowing.id_peminjaman}/reject`)
    if (response.data.success) {
      const index = borrowings.value.findIndex((b) => b.id_peminjaman === borrowing.id_peminjaman)
      if (index > -1) {
        borrowings.value[index] = response.data.data
      }
      showSuccess('Peminjaman berhasil ditolak!')
    }
  } catch (error) {
    showError(error.response?.data?.message || 'Gagal menolak peminjaman')
  }
}

const verifyReturn = async () => {
  try {
    isSubmitting.value = true
    formError.value = ''

    const payload = {
      tanggal_kembali: returnData.value.tanggal_kembali,
      kondisi: returnData.value.kondisi,
      catatan_kondisi: returnData.value.catatan_kondisi,
      denda: calculateFine()
    }

    const response = await apiClient.post(
      `/borrowings/${selectedBorrowing.value.id_peminjaman}/verify-return`,
      payload
    )
    
    if (response.data.success) {
      const index = borrowings.value.findIndex(
        (b) => b.id_peminjaman === selectedBorrowing.value.id_peminjaman
      )
      if (index > -1) {
        borrowings.value[index] = response.data.data
      }
      closeModals()
      showSuccess('Pengembalian berhasil diverifikasi!')
    }
  } catch (error) {
    formError.value = error.response?.data?.message || 'Gagal memverifikasi pengembalian'
  } finally {
    isSubmitting.value = false
  }
}

// Lifecycle
onMounted(() => {
  loadBorrowings()
})
</script>

<style scoped>
.borrowing-table-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* Header Section */
.table-header {
  display: flex;
  gap: 20px;
  align-items: center;
  flex-wrap: wrap;
  background: white;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.search-box {
  flex: 1;
  min-width: 250px;
}

.search-input {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 14px;
  transition: border-color 0.3s ease;
}

.search-input:focus {
  outline: none;
  border-color: #0b7285;
}

.header-actions {
  display: flex;
  gap: 12px;
  align-items: center;
}

.status-filter {
  padding: 10px 14px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 14px;
  cursor: pointer;
  transition: border-color 0.3s ease;
}

.status-filter:focus {
  outline: none;
  border-color: #0b7285;
}

/* Stats Row */
.stats-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 12px;
  margin-bottom: 10px;
}

.stat-box {
  background: white;
  padding: 15px;
  border-radius: 8px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  border-left: 4px solid #0b7285;
}

.stat-box .label {
  font-size: 12px;
  color: #666;
  font-weight: 500;
}

.stat-box .value {
  font-size: 20px;
  font-weight: bold;
  color: #0b7285;
}

.stat-box .value.pending {
  color: #f59f00;
}

.stat-box .value.active {
  color: #2f9e44;
}

.stat-box .value.overdue {
  color: #c92a2a;
}

/* Table Wrapper */
.table-wrapper {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  overflow: hidden;
}

.borrowing-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

.borrowing-table thead {
  background: linear-gradient(135deg, #0b7285 0%, #0c6b7d 100%);
  color: white;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.borrowing-table th {
  padding: 16px 12px;
  text-align: left;
  border-bottom: 2px solid #0b7285;
}

.borrowing-table tbody tr {
  border-bottom: 1px solid #f0f0f0;
  transition: background-color 0.2s ease;
}

.borrowing-table tbody tr:hover {
  background-color: #f9f9f9;
}

.borrowing-table td {
  padding: 14px 12px;
  vertical-align: middle;
}

.borrowing-table td strong {
  color: #1a1a2e;
}

.text-center {
  text-align: center;
}

.text-danger {
  color: #c92a2a;
  font-weight: 500;
}

.text-success {
  color: #2f9e44;
}

/* Status Badges */
.status-badge {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  white-space: nowrap;
}

.status-badge.applied {
  background-color: #fff3e0;
  color: #f59f00;
}

.status-badge.approved {
  background-color: #d3f9d8;
  color: #2f9e44;
}

.status-badge.ready_for_pickup {
  background-color: #e7f5ff;
  color: #0b7285;
}

.status-badge.picked_up {
  background-color: #fce7f3;
  color: #d6336c;
}

.status-badge.returned {
  background-color: #d0bfff;
  color: #5f3dc4;
}

.status-badge.rejected {
  background-color: #ffe3e3;
  color: #c92a2a;
}

/* Action Buttons */
.action-buttons {
  display: flex;
  gap: 6px;
  justify-content: center;
  flex-wrap: wrap;
}

.btn-action {
  padding: 6px 10px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 13px;
  transition: all 0.3s ease;
  background: white;
  border: 1px solid #ddd;
}

.btn-action:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-action.btn-detail:hover {
  background-color: #e7f5ff;
  border-color: #0b7285;
}

.btn-action.btn-approve:hover {
  background-color: #d3f9d8;
  border-color: #2f9e44;
}

.btn-action.btn-reject:hover {
  background-color: #ffe3e3;
  border-color: #c92a2a;
}

.btn-action.btn-pickup:hover {
  background-color: #fff3e0;
  border-color: #f59f00;
}

.btn-action.btn-return:hover {
  background-color: #fce7f3;
  border-color: #d6336c;
}

.btn-action:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Empty State */
.empty-state {
  padding: 60px 20px;
  text-align: center;
  color: #999;
  font-size: 16px;
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-content.modal-small {
  max-width: 400px;
}

.modal-content.modal-large {
  max-width: 700px;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 2px solid #f0f0f0;
}

.modal-header h3 {
  margin: 0;
  color: #1a1a2e;
  font-size: 18px;
}

.btn-close {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #999;
}

.modal-body {
  padding: 20px;
}

.detail-section {
  margin-bottom: 20px;
  padding-bottom: 20px;
  border-bottom: 1px solid #f0f0f0;
}

.detail-section:last-child {
  border-bottom: none;
}

.detail-section h4 {
  margin: 0 0 12px 0;
  color: #1a1a2e;
  font-size: 14px;
  font-weight: 600;
}

.info-row {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
  font-size: 14px;
  border-bottom: 1px solid #f5f5f5;
}

.info-row:last-child {
  border-bottom: none;
}

.info-row .label {
  font-weight: 600;
  color: #666;
  min-width: 150px;
}

.pickup-code {
  background: linear-gradient(135deg, #0b7285 0%, #0c6b7d 100%);
  color: white;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
  font-size: 24px;
  font-weight: bold;
  letter-spacing: 2px;
  font-family: 'Courier New', monospace;
  margin: 16px 0;
}

.note {
  color: #666;
  font-size: 13px;
  text-align: center;
}

.info-box {
  background-color: #fff3e0;
  border: 2px solid #f59f00;
  border-radius: 8px;
  padding: 12px;
  margin-bottom: 16px;
  color: #f59f00;
  font-weight: 600;
}

.info-box p {
  margin: 6px 0 0 0;
  font-size: 16px;
}

.modal-form {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.form-group label {
  font-weight: 600;
  color: #1a1a2e;
  font-size: 14px;
}

.form-group input,
.form-group select,
.form-group textarea {
  padding: 10px 12px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 14px;
  font-family: inherit;
  transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #0b7285;
}

.form-group textarea {
  resize: vertical;
  min-height: 80px;
}

.alert {
  padding: 12px 16px;
  border-radius: 8px;
  font-size: 14px;
  margin-bottom: 16px;
}

.alert-error {
  background-color: #ffe3e3;
  color: #c92a2a;
  border: 1px solid #fcc2c2;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  padding: 20px;
  border-top: 2px solid #f0f0f0;
}

/* Buttons */
.btn {
  padding: 10px 16px;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-primary {
  background-color: #0b7285;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background-color: #0a5f6d;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(11, 114, 133, 0.3);
}

.btn-secondary {
  background-color: #e0e0e0;
  color: #333;
}

.btn-secondary:hover:not(:disabled) {
  background-color: #d0d0d0;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* Loading State */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.3);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  z-index: 999;
}

.spinner {
  font-size: 48px;
  animation: spin 1s linear infinite;
  margin-bottom: 16px;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.loading-overlay p {
  color: white;
  font-size: 16px;
  font-weight: 500;
}

/* Responsive */
@media (max-width: 768px) {
  .table-header {
    flex-direction: column;
  }

  .search-box {
    width: 100%;
  }

  .header-actions {
    width: 100%;
    flex-direction: column;
  }

  .modal-content {
    width: 95%;
    max-width: none;
  }

  .borrowing-table {
    font-size: 12px;
  }

  .borrowing-table th,
  .borrowing-table td {
    padding: 10px 8px;
  }

  .action-buttons {
    flex-direction: column;
  }

  .btn-action {
    width: 100%;
  }

  .info-row {
    flex-direction: column;
  }

  .info-row .label {
    min-width: auto;
    margin-bottom: 4px;
  }
}
</style>
