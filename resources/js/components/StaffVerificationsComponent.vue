<template>
  <div class="verifications-container">
    <div class="verifications-header">
      <h2>✓ Verifikasi Pengembalian</h2>
      <p class="subtitle">Periksa dan verifikasi pengembalian alat dari peminjam</p>
    </div>

    <div class="stats-overview">
      <div class="stat-card pending">
        <div class="stat-number">{{ pendingCount }}</div>
        <div class="stat-label">Menunggu Verifikasi</div>
      </div>
      <div class="stat-card verified">
        <div class="stat-number">{{ verifiedTodayCount }}</div>
        <div class="stat-label">Diverifikasi Hari Ini</div>
      </div>
      <div class="stat-card damaged">
        <div class="stat-number">{{ damagedCount }}</div>
        <div class="stat-label">Rusak</div>
      </div>
    </div>

    <div class="verifications-list">
      <div v-if="borrowings.length === 0" class="empty-state">
        <p>✓ Tidak ada pengembalian yang menunggu verifikasi</p>
      </div>
      
      <div v-else class="borrowing-item" v-for="borrowing in pendingVerifications" :key="borrowing.id_peminjaman">
        <div class="item-header">
          <div class="item-title">
            <h3>{{ borrowing.equipment?.nama_alat || borrowing.nama_alat || borrowing.equipment_name || 'N/A' }}</h3>
            <span class="category">{{ borrowing.category_name }}</span>
          </div>
          <div class="item-date">
            <small>Dikembalikan: {{ formatDate(borrowing.tanggal_kembali_aktual) }}</small>
          </div>
        </div>

        <div class="item-details">
          <div class="detail-row">
            <span class="label">Peminjam:</span>
            <span class="value">{{ borrowing.nama_peminjam }}</span>
          </div>
          <div class="detail-row">
            <span class="label">Durasi Peminjaman:</span>
            <span class="value">{{ borrowing.durasi_peminjaman }} hari</span>
          </div>
          <div class="detail-row">
            <span class="label">Tanggal Seharusnya Kembali:</span>
            <span class="value">{{ formatDate(borrowing.tanggal_rencana_kembali || borrowing.planned_return_date) }}</span>
          </div>
          <div class="detail-row">
            <span class="label">Status Keterlambatan:</span>
            <span class="value" :style="{ color: isOverdue(borrowing) ? '#ff6b6b' : '#51cf66' }">
              {{ isOverdue(borrowing) ? '🔴 Terlambat' : '🟢 Tepat Waktu' }}
            </span>
          </div>
        </div>

        <div class="verification-form">
          <h4>Verifikasi Kondisi Alat</h4>
          <div class="form-group">
            <label>Kondisi Saat Dikembalikan:</label>
            <div class="condition-options">
              <label v-for="condition in conditionOptions" :key="condition" class="radio-label">
                <input 
                  type="radio" 
                  :value="condition"
                  @change="selectedCondition[borrowing.id_peminjaman] = condition"
                  :checked="selectedCondition[borrowing.id_peminjaman] === condition"
                  name="condition"
                />
                <span :class="['condition-badge', condition.toLowerCase()]">
                  {{ getConditionIcon(condition) + ' ' + condition }}
                </span>
              </label>
            </div>
          </div>

          <div class="form-group">
            <label>Catatan Verifikasi (opsional):</label>
            <textarea 
              v-model="verificationNotes[borrowing.id_peminjaman]"
              placeholder="Contoh: Goresan kecil di sudut, lampu masih berfungsi dengan baik"
              class="note-input"
            ></textarea>
          </div>
        </div>

        <div class="item-actions">
          <button @click="verifyReturn(borrowing)" class="btn btn-verify">
            ✓ Verifikasi & Simpan
          </button>
          <button @click="cancelVerification(borrowing)" class="btn btn-cancel">
            ✕ Batalkan
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import apiClient from '@/config/api'

const borrowings = ref([])
const selectedCondition = ref({})
const verificationNotes = ref({})
const conditionOptions = ['Baik', 'Sedang', 'Rusak']

const pendingVerifications = computed(() => {
  return borrowings.value.filter(b => b.status === 'picked_up')
})

const pendingCount = computed(() => pendingVerifications.value.length)

const verifiedTodayCount = computed(() => {
  const today = new Date().toISOString().split('T')[0]
  return borrowings.value.filter(b => {
    const verifyDate = new Date(b.tanggal_verifikasi || b.updated_at).toISOString().split('T')[0]
    return b.status === 'returned' && verifyDate === today
  }).length
})

const damagedCount = computed(() => {
  return borrowings.value.filter(b => b.status === 'returned' && b.kondisi_saat_kembali === 'Rusak').length
})

const loadBorrowings = async () => {
  try {
    const response = await apiClient.get('/borrowings')
    if (response.data.success) {
      borrowings.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading borrowings:', error)
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

const getConditionIcon = (condition) => {
  const icons = {
    'Baik': '✅',
    'Sedang': '⚠️',
    'Rusak': '❌'
  }
  return icons[condition] || ''
}

const isOverdue = (borrowing) => {
  const returnDate = new Date(borrowing.tanggal_kembali_aktual)
  const dueDate = new Date(borrowing.tanggal_rencana_kembali || borrowing.planned_return_date)
  return returnDate > dueDate
}

const verifyReturn = async (borrowing) => {
  const condition = selectedCondition.value[borrowing.id_peminjaman]
  
  if (!condition) {
    alert('Pilih kondisi alat terlebih dahulu')
    return
  }

  try {
    const response = await apiClient.patch(`/borrowings/${borrowing.id_peminjaman}/verify-return`, {
      kondisi_saat_kembali: condition,
      catatan_verifikasi: verificationNotes.value[borrowing.id_peminjaman] || ''
    })
    
    if (response.data.success) {
      borrowings.value = borrowings.value.filter(b => b.id_peminjaman !== borrowing.id_peminjaman)
      alert('Pengembalian berhasil diverifikasi!')
    }
  } catch (error) {
    console.error('Error verifying return:', error)
    alert('Gagal memverifikasi pengembalian: ' + (error.response?.data?.message || error.message))
  }
}

const cancelVerification = (borrowing) => {
  delete selectedCondition.value[borrowing.id_peminjaman]
  delete verificationNotes.value[borrowing.id_peminjaman]
}

onMounted(() => {
  loadBorrowings()
  // Auto-refresh every 30 seconds
  const interval = setInterval(loadBorrowings, 30000)
  return () => clearInterval(interval)
})
</script>

<style scoped>
.verifications-container {
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

.verifications-header {
  margin-bottom: 20px;
}

.verifications-header h2 {
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

.stat-card.verified {
  background: linear-gradient(135deg, #51cf66 0%, #37b24d 100%);
}

.stat-card.damaged {
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

.verifications-list {
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
  background: #fafafa;
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
  background: white;
  padding: 15px;
  border-radius: 6px;
  margin-bottom: 15px;
  border: 1px solid #e0e0e0;
}

.detail-row {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  border-bottom: 1px solid #f0f0f0;
  font-size: 0.95rem;
}

.detail-row:last-child {
  border-bottom: none;
}

.detail-row .label {
  font-weight: 600;
  color: #666;
  min-width: 180px;
}

.detail-row .value {
  color: #1a1a2e;
  text-align: right;
  flex: 1;
}

.verification-form {
  background: white;
  padding: 15px;
  border-radius: 6px;
  margin-bottom: 15px;
  border: 1px solid #e0e0e0;
}

.verification-form h4 {
  margin: 0 0 15px 0;
  color: #1a1a2e;
  font-size: 0.95rem;
}

.form-group {
  margin-bottom: 15px;
}

.form-group:last-child {
  margin-bottom: 0;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #666;
  font-size: 0.9rem;
}

.condition-options {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
  gap: 10px;
}

.radio-label {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  padding: 10px;
  border: 2px solid #e0e0e0;
  border-radius: 6px;
  transition: all 0.2s ease;
}

.radio-label input[type="radio"] {
  margin: 0;
  cursor: pointer;
}

.radio-label:hover {
  border-color: #0b7285;
}

.condition-badge {
  font-weight: 600;
  font-size: 0.9rem;
  white-space: nowrap;
}

.condition-badge.baik {
  color: #51cf66;
}

.condition-badge.sedang {
  color: #ff9800;
}

.condition-badge.rusak {
  color: #ff6b6b;
}

.note-input {
  width: 100%;
  min-height: 80px;
  padding: 12px;
  border: 1px solid #e0e0e0;
  border-radius: 6px;
  font-family: inherit;
  font-size: 0.95rem;
  resize: vertical;
}

.note-input:focus {
  outline: none;
  border-color: #0b7285;
  box-shadow: 0 0 0 3px rgba(11, 114, 133, 0.1);
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

.btn-verify {
  background-color: #51cf66;
  color: white;
}

.btn-verify:hover {
  background-color: #37b24d;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(81, 207, 102, 0.3);
}

.btn-cancel {
  background-color: #e0e0e0;
  color: #333;
}

.btn-cancel:hover {
  background-color: #d0d0d0;
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

  .condition-options {
    grid-template-columns: 1fr;
  }

  .item-actions {
    flex-direction: column;
  }
}
</style>
