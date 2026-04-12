<template>
  <div class="borrowing-form-modal" v-if="showModal">
    <div class="modal-overlay" @click.self="closeModal">
      <div class="modal-content modal-large">
        <!-- Header -->
        <div class="modal-header">
          <h3>📋 Form Peminjaman - {{ selectedEquipment?.nama_alat }}</h3>
          <button @click="closeModal" class="close-btn">✕</button>
        </div>

        <!-- Main Form -->
        <div class="modal-body" v-if="!showVerificationCode">
          <form @submit.prevent="submitBorrowingRequest">
            <!-- Step indicator -->
            <div class="step-indicator">
              <div class="step" :class="{ active: currentStep >= 1 }">1. Tanggal</div>
              <div class="step" :class="{ active: currentStep >= 2 }">2. Keperluan</div>
              <div class="step" :class="{ active: currentStep >= 3 }">3. Konfirmasi</div>
            </div>

            <!-- Step 1: Dates -->
            <div v-if="currentStep === 1" class="form-step">
              <h4>Pilih Tanggal Peminjaman & Pengembalian</h4>
              
              <div class="form-group">
                <label>Tanggal Pengambilan Alat (Hari ini) *</label>
                <input 
                  type="text"
                  v-model="borrowingForm.tanggal_mulai" 
                  readonly
                  class="input"
                />
                <small class="text-muted">Alat diambil hari ini di lokasi</small>
              </div>

              <div class="form-group">
                <label>Tanggal Pengembalian Alat *</label>
                <input 
                  type="date"
                  v-model="borrowingForm.tanggal_kembali" 
                  :min="minReturnDate"
                  :max="maxReturnDate"
                  @change="validateReturnDate"
                  class="input"
                  required
                />
              <div v-if="!borrowingForm.tanggal_kembali" class="form-help-box">
                📅 Pilih tanggal pengembalian untuk melanjutkan
              </div>
              <small v-if="borrowingForm.tanggal_kembali" class="text-info">
                  📅 Durasi: {{ calculateDuration() }} hari ({{ calculateHours() }} jam)
                </small>
                <small v-if="dateError" class="text-danger">⚠️ {{ dateError }}</small>
              </div>

              <div class="form-group info-box">
                <p>⏰ <strong>Ketentuan Durasi:</strong></p>
                <p>• Minimum: 1 jam</p>
                <p>• Maksimum: 14 hari (2 minggu)</p>
                <p>• Tidak boleh pada hari libur (Sabtu/Minggu)</p>
              </div>

              <div class="button-group">
                <button type="button" @click="closeModal" class="btn btn-secondary">Batal</button>
                <button 
                  type="button" 
                  @click="goToStep(2)"
                  class="btn btn-primary"
                  :disabled="!borrowingForm.tanggal_kembali || !!dateError"
                >
                  Lanjut →
                </button>
              </div>
            </div>

            <!-- Step 2: Purpose -->
            <div v-if="currentStep === 2" class="form-step">
              <h4>Keperluan Peminjaman</h4>
              
              <div class="form-group">
                <label>Keperluan/Tujuan Peminjaman *</label>
                <textarea 
                  v-model="borrowingForm.keperluan" 
                  placeholder="Contoh: Untuk presentasi di ruang meeting, project fotografi, dll..."
                  class="textarea"
                  rows="4"
                  required
                ></textarea>
                <small class="text-muted">{{ borrowingForm.keperluan.length }}/500 karakter</small>
              </div>

              <div class="form-group">
                <label>Catatan Tambahan (Opsional)</label>
                <textarea 
                  v-model="borrowingForm.catatan" 
                  placeholder="Informasi tambahan yang ingin Anda berikan kepada admin..."
                  class="textarea"
                  rows="3"
                ></textarea>
                <small class="text-muted">{{ borrowingForm.catatan.length }}/500 karakter</small>
              </div>

              <div class="button-group">
                <button type="button" @click="goToStep(1)" class="btn btn-secondary">← Kembali</button>
                <button 
                  type="button" 
                  @click="goToStep(3)"
                  class="btn btn-primary"
                  :disabled="!borrowingForm.keperluan"
                >
                  Lanjut →
                </button>
              </div>
            </div>

            <!-- Step 3: Confirmation -->
            <div v-if="currentStep === 3" class="form-step">
              <h4>Konfirmasi Peminjaman</h4>
              
              <div class="summary-box">
                <div class="summary-item">
                  <span class="label">Alat:</span>
                  <span class="value">{{ selectedEquipment?.nama_alat }}</span>
                </div>
                <div class="summary-item">
                  <span class="label">Kategori:</span>
                  <span class="value">{{ selectedEquipment?.category?.nama_kategori }}</span>
                </div>
                <div class="summary-item">
                  <span class="label">Denda/Hari:</span>
                  <span class="value">Rp {{ (selectedEquipment?.fine_per_day || 0).toLocaleString('id-ID') }}</span>
                </div>
                <hr>
                <div class="summary-item">
                  <span class="label">Tanggal Pengambilan:</span>
                  <span class="value">{{ formatDate(borrowingForm.tanggal_mulai) }}</span>
                </div>
                <div class="summary-item">
                  <span class="label">Tanggal Pengembalian:</span>
                  <span class="value">{{ formatDate(borrowingForm.tanggal_kembali) }}</span>
                </div>
                <div class="summary-item">
                  <span class="label">Durasi:</span>
                  <span class="value">{{ calculateDuration() }} hari</span>
                </div>
                <hr>
                <div class="summary-item">
                  <span class="label">Keperluan:</span>
                  <span class="value pre-wrap">{{ borrowingForm.keperluan }}</span>
                </div>
                <div v-if="borrowingForm.catatan" class="summary-item">
                  <span class="label">Catatan:</span>
                  <span class="value pre-wrap">{{ borrowingForm.catatan }}</span>
                </div>
              </div>

              <div class="button-group">
                <button type="button" @click="goToStep(2)" class="btn btn-secondary">← Kembali</button>
                <button 
                  type="submit"
                  @click="submitBorrowingRequest"
                  class="btn btn-primary btn-lg"
                  :disabled="isSubmitting"
                >
                  {{ isSubmitting ? '⏳ Memproses...' : '✓ Kirim Permohonan' }}
                </button>
              </div>
            </div>

            <!-- Error message -->
            <div v-if="formError" class="alert alert-error">
              ❌ {{ formError }}
            </div>
          </form>
        </div>

        <!-- Verification Code Display -->
        <div v-if="showVerificationCode" class="modal-body verification-code-section">
          <div class="success-box">
            <h3>✅ Permohonan Peminjaman Berhasil!</h3>
            <p>Silakan simpan kode verifikasi di bawah ini untuk dikonfirmasi kepada staff</p>
          </div>

          <div class="verification-code-display">
            <div class="code-container">
              <div class="code-label">Kode Verifikasi Peminjaman</div>
              <div class="code-box">
                <span class="code-text">{{ verificationCode }}</span>
              </div>
              <div class="code-info">
                <p>📌 <strong>Tunjukkan kode ini kepada staff untuk konfirmasi peminjaman</strong></p>
                <p>⏰ Kode berlaku selama 24 jam</p>
              </div>
            </div>

            <div class="code-actions">
              <button @click="copyToClipboard" class="btn btn-info">
                📋 Copy Kode
              </button>
              <button @click="downloadAsImage" class="btn btn-primary">
                🖼️ Simpan sebagai Gambar
              </button>
              <button @click="printCode" class="btn btn-secondary">
                🖨️ Cetak
              </button>
            </div>

            <div class="info-box">
              <h4>📝 Informasi Peminjaman Anda</h4>
              <table class="summary-table">
                <tr>
                  <td>Alat:</td>
                  <td><strong>{{ selectedEquipment?.nama_alat }}</strong></td>
                </tr>
                <tr>
                  <td>Pengambilan:</td>
                  <td>{{ formatDate(borrowingForm.tanggal_mulai) }}</td>
                </tr>
                <tr>
                  <td>Pengembalian:</td>
                  <td>{{ formatDate(borrowingForm.tanggal_kembali) }}</td>
                </tr>
                <tr>
                  <td>Durasi:</td>
                  <td>{{ calculateDuration() }} hari</td>
                </tr>
                <tr>
                  <td>Status:</td>
                  <td><span class="badge pending">⏳ Menunggu Verifikasi Staff</span></td>
                </tr>
              </table>
            </div>
          </div>

          <div class="button-group">
            <button @click="closeModal" class="btn btn-primary btn-lg">Selesai</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import apiClient from '@/config/api'
import html2canvas from 'html2canvas'
import { useToast } from '@/composables/useToast'

const props = defineProps({
  showModal: Boolean,
  selectedEquipment: Object,
})

const emit = defineEmits(['close', 'success'])

// Form state
const currentStep = ref(1)
const borrowingForm = ref({
  tanggal_mulai: new Date().toISOString().split('T')[0],
  tanggal_kembali: '',
  keperluan: '',
  catatan: '',
})

const isSubmitting = ref(false)
const formError = ref('')
const dateError = ref('')
const showVerificationCode = ref(false)
const verificationCode = ref('')
const isDataTouched = ref(false)
const { success: showSuccess, error: showError } = useToast()

const minReturnDate = computed(() => {
  const tomorrow = new Date()
  tomorrow.setDate(tomorrow.getDate() + 1)
  return tomorrow.toISOString().split('T')[0]
})

const maxReturnDate = computed(() => {
  const maxDate = new Date()
  maxDate.setDate(maxDate.getDate() + 14)
  return maxDate.toISOString().split('T')[0]
})

const calculateDuration = () => {
  if (!borrowingForm.value.tanggal_kembali) return 0
  const start = new Date(borrowingForm.value.tanggal_mulai)
  const end = new Date(borrowingForm.value.tanggal_kembali)
  const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24))
  return days
}

const calculateHours = () => {
  if (!borrowingForm.value.tanggal_kembali) return 0
  const duration = calculateDuration()
  return duration * 24
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const validateReturnDate = () => {
  if (!borrowingForm.value.tanggal_kembali) {
    dateError.value = ''
    return false
  }
  
  dateError.value = ''
  const duration = calculateDuration()
  
  if (duration < 1) {
    dateError.value = 'Durasi minimal 1 hari'
    return false
  }
  
  if (duration > 14) {
    dateError.value = 'Durasi maksimal 14 hari'
    return false
  }

  // Check if return date falls on holidays (weekends)
  const end = new Date(borrowingForm.value.tanggal_kembali)

  if (end.getDay() === 0 || end.getDay() === 6) {
    dateError.value = 'Tanggal pengembalian tidak boleh pada hari libur (Sabtu/Minggu)'
    return false
  }

  return true
}

const goToStep = (step) => {
  if (step === 1) {
    currentStep.value = step
  } else if (step === 2) {
    if (validateReturnDate()) {
      currentStep.value = step
    }
  } else if (step === 3) {
    currentStep.value = step
  }
}

const submitBorrowingRequest = async () => {
  try {
    isSubmitting.value = true
    formError.value = ''

    const userStr = localStorage.getItem('user')
    if (!userStr) {
      formError.value = 'Silakan login terlebih dahulu'
      return
    }

    const user = JSON.parse(userStr)

    // Handle both id_user and id (for backward compatibility)
    const userId = user.id_user || user.id

    if (!userId) {
      formError.value = 'Error: User ID tidak ditemukan. Silakan login ulang.'
      return
    }

    // Get equipment ID - try both id_equipment and id_alat for compatibility
    const equipmentId = props.selectedEquipment?.id_equipment || props.selectedEquipment?.id_alat

    if (!equipmentId) {
      formError.value = 'Error: Equipment ID tidak ditemukan'
      return
    }

    const payload = {
      id_user: userId,
      id_equipment: equipmentId,
      quantity: 1,
      tanggal_mulai_peminjaman: borrowingForm.value.tanggal_mulai,
      tanggal_rencana_kembali: borrowingForm.value.tanggal_kembali,
      keperluan: borrowingForm.value.keperluan,
      catatan: borrowingForm.value.catatan,
    }

    const response = await apiClient.post('/borrowings', payload)

    if (response.data.success) {
      verificationCode.value = response.data.kode_verifikasi
      showVerificationCode.value = true
      emit('success', response.data.data)
    }
  } catch (error) {
    formError.value = 'Gagal mengirim permohonan: ' + (error.response?.data?.message || error.message)
  } finally {
    isSubmitting.value = false
  }
}

const copyToClipboard = async () => {
  try {
    await navigator.clipboard.writeText(verificationCode.value)
    showSuccess('✅ Kode telah disalin ke clipboard')
  } catch (err) {
    showError('❌ Gagal menyalin kode')
  }
}

const downloadAsImage = async () => {
  try {
    const element = document.querySelector('.code-container')
    if (!element) return

    const canvas = await html2canvas(element, {
      backgroundColor: '#ffffff'
    })
    
    const link = document.createElement('a')
    link.href = canvas.toDataURL('image/png')
    link.download = `kode-verifikasi-${verificationCode.value}.png`
    link.click()
  } catch (error) {
    showError('❌ Gagal mengunduh gambar: ' + error.message)
  }
}

const printCode = () => {
  const printContent = `
    <html>
      <head>
        <title>Kode Verifikasi Peminjaman</title>
        <style>
          body { font-family: Arial, sans-serif; padding: 20px; }
          .container { text-align: center; max-width: 400px; margin: 0 auto; }
          .code-box { 
            border: 3px solid #333; 
            padding: 30px; 
            margin: 30px 0;
            font-size: 48px;
            font-weight: bold;
            letter-spacing: 5px;
            font-family: monospace;
          }
          .info { color: #666; margin-top: 20px; }
        </style>
      </head>
      <body>
        <div class="container">
          <h1>Kode Verifikasi Peminjaman</h1>
          <div class="code-box">${verificationCode.value}</div>
          <p class="info">Tunjukkan kode ini kepada staff untuk konfirmasi</p>
          <p class="info">Alat: ${props.selectedEquipment?.nama_alat}</p>
          <p class="info">Tanggal: ${new Date().toLocaleDateString('id-ID')}</p>
        </div>
      </body>
    </html>
  `
  
  const printWindow = window.open('', '', 'height=400,width=600')
  printWindow.document.write(printContent)
  printWindow.document.close()
  printWindow.print()
}

const closeModal = () => {
  currentStep.value = 1
  borrowingForm.value = {
    tanggal_mulai: new Date().toISOString().split('T')[0],
    tanggal_kembali: '',
    keperluan: '',
    catatan: '',
  }
  formError.value = ''
  dateError.value = ''
  showVerificationCode.value = false
  verificationCode.value = ''
  emit('close')
}
</script>

<style scoped>
.borrowing-form-modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
  max-height: 90vh;
  overflow-y: auto;
  width: 90%;
  max-width: 600px;
}

.modal-header {
  padding: 20px;
  border-bottom: 1px solid #e0e0e0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h3 {
  margin: 0;
  font-size: 1.3rem;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #999;
}

.modal-body {
  padding: 30px;
}

.step-indicator {
  display: flex;
  justify-content: space-around;
  margin-bottom: 30px;
}

.step {
  padding: 10px 15px;
  border-radius: 8px;
  background: #f0f0f0;
  color: #999;
  font-weight: bold;
  text-align: center;
}

.step.active {
  background: #0b7285;
  color: white;
}

.form-step {
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-weight: bold;
  margin-bottom: 8px;
  color: #333;
}

.input,
.textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-family: inherit;
  font-size: 1rem;
}

.input:focus,
.textarea:focus {
  outline: none;
  border-color: #0b7285;
  box-shadow: 0 0 0 3px rgba(11, 114, 133, 0.1);
}

.textarea {
  resize: vertical;
  min-height: 100px;
}

.text-muted {
  color: #999;
  font-size: 0.9rem;
}

.text-info {
  color: #0b7285;
  font-size: 0.9rem;
}

.text-danger {
  color: #e74c3c;
  font-size: 0.9rem;
}

.info-box {
  background: #e8f4f8;
  border-left: 4px solid #0b7285;
  padding: 15px;
  border-radius: 6px;
  margin-bottom: 20px;
}

.info-box p {
  margin: 5px 0;
  color: #333;
}

.form-help-box {
  background: #fff3cd;
  border-left: 4px solid #ffc107;
  padding: 12px;
  border-radius: 4px;
  margin: 10px 0;
  color: #856404;
  font-size: 0.95rem;
}

.summary-box {
  background: #f9f9f9;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  border-bottom: 1px solid #eee;
}

.summary-item:last-of-type {
  border-bottom: none;
}

.summary-item .label {
  font-weight: bold;
  color: #666;
}

.summary-item .value {
  color: #333;
  text-align: right;
  flex: 1;
  margin-left: 20px;
}

.summary-item .value.pre-wrap {
  white-space: pre-wrap;
  word-break: break-word;
}

.summary-table {
  width: 100%;
  border-collapse: collapse;
}

.summary-table tr {
  border-bottom: 1px solid #eee;
}

.summary-table td {
  padding: 10px;
  text-align: left;
}

.summary-table td:first-child {
  width: 120px;
  font-weight: bold;
  color: #666;
}

.button-group {
  display: flex;
  gap: 10px;
  margin-top: 30px;
  justify-content: flex-end;
}

.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 6px;
  font-size: 1rem;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.3s ease;
}

.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-primary {
  background: #0b7285;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background: #0a5f73;
}

.btn-secondary {
  background: #e0e0e0;
  color: #333;
}

.btn-secondary:hover:not(:disabled) {
  background: #d0d0d0;
}

.btn-info {
  background: #3498db;
  color: white;
}

.btn-lg {
  padding: 15px 30px;
  font-size: 1.1rem;
}

.alert-error {
  background: #fee;
  border-left: 4px solid #e74c3c;
  color: #c00;
  padding: 15px;
  border-radius: 6px;
  margin-bottom: 20px;
}

/* Verification Code Styles */
.success-box {
  background: #e8f5e9;
  border-left: 4px solid #4caf50;
  padding: 20px;
  border-radius: 6px;
  margin-bottom: 30px;
  text-align: center;
}

.success-box h3 {
  margin: 0 0 10px 0;
  color: #2e7d32;
}

.success-box p {
  color: #558b2f;
  margin: 0;
}

.verification-code-section {
  text-align: center;
}

.code-container {
  background: white;
  border: 2px solid #f0f0f0;
  padding: 40px;
  border-radius: 12px;
  margin: 30px 0;
}

.code-label {
  font-size: 0.9rem;
  color: #999;
  margin-bottom: 15px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.code-box {
  background: linear-gradient(135deg, #0b7285 0%, #087c7c 100%);
  color: white;
  padding: 40px;
  border-radius: 12px;
  margin: 20px 0;
  box-shadow: 0 4px 15px rgba(11, 114, 133, 0.3);
}

.code-text {
  font-size: 3rem;
  font-weight: bold;
  letter-spacing: 8px;
  font-family: 'Courier New', monospace;
}

.code-info {
  margin-top: 20px;
  color: #666;
}

.code-info p {
  margin: 8px 0;
  font-size: 0.95rem;
}

.code-actions {
  display: flex;
  gap: 10px;
  justify-content: center;
  margin: 30px 0;
  flex-wrap: wrap;
}

.code-actions .btn {
  flex: 1;
  min-width: 150px;
}

.badge {
  display: inline-block;
  padding: 5px 12px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: bold;
}

.badge.pending {
  background: #fff3cd;
  color: #856404;
}

.info-box {
  background: #f0f8ff;
  border: 1px solid #0b7285;
  border-radius: 8px;
  padding: 20px;
  margin-top: 30px;
  text-align: left;
}

.info-box h4 {
  margin-top: 0;
  color: #0b7285;
}
</style>
