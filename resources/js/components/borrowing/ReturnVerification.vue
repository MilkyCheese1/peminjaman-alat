<template>
  <div class="return-verification-wrapper">
    <div v-if="!borrowing">
      <p>Tidak ada data peminjaman</p>
    </div>

    <div v-else class="return-container">
      <!-- Header -->
      <div class="header">
        <h3>📋 Verifikasi Pengembalian Alat</h3>
        <p class="status-badge" :style="{ backgroundColor: statusInfo.color }">
          {{ statusInfo.icon }} {{ statusInfo.label }}
        </p>
      </div>

      <!-- Overdue Warning -->
      <div v-if="isOverdue" class="warning-box">
        <strong>🚨 PERHATIAN: TERLAMBAT!</strong>
        <p>Alat harus dikembalikan pada {{ formatDate(borrowing.plannedReturnDate) }}</p>
        <p>Hari ini: {{ formatDate(new Date()) }} ({{ lateDays }} hari terlambat)</p>
        <p class="fine-warning" v-if="estimatedFine > 0">
          Estimasi denda: <strong>Rp {{ estimatedFine.toLocaleString('id-ID') }}</strong>
        </p>
      </div>

      <!-- Borrowing Details -->
      <div class="details-card">
        <h4>📦 Detail Peminjaman</h4>
        <div class="detail-row">
          <span class="label">ID Peminjaman:</span>
          <span class="value">{{ borrowing.id }}</span>
        </div>
        <div class="detail-row">
          <span class="label">Alat:</span>
          <span class="value">{{ borrowing.equipmentName }}</span>
        </div>
        <div class="detail-row">
          <span class="label">Tanggal Peminjaman:</span>
          <span class="value">{{ formatDate(borrowing.borrowDate) }}</span>
        </div>
        <div class="detail-row">
          <span class="label">Tanggal Kembali Target:</span>
          <span class="value">{{ formatDate(borrowing.plannedReturnDate) }}</span>
        </div>
        <div class="detail-row">
          <span class="label">Durasi Peminjaman:</span>
          <span class="value">{{ duration }} hari</span>
        </div>
      </div>

      <!-- Process Steps -->
      <div class="steps-card">
        <h4>📝 Proses Pengembalian</h4>
        <div class="step" :class="{ completed: step1Done }">
          <div class="step-number">1</div>
          <div class="step-content">
            <strong>Verifikasi Customer</strong>
            <p>Periksa kondisi alat dan upload foto</p>
          </div>
          <div v-if="step1Done" class="step-badge">✅ Selesai</div>
        </div>
        <div class="step" :class="{ completed: step2Done }">
          <div class="step-number">2</div>
          <div class="step-content">
            <strong>Verifikasi Staff</strong>
            <p>Staff menerima alat dan verifikasi kondisi</p>
          </div>
          <div v-if="step2Done" class="step-badge">✅ Selesai</div>
        </div>
      </div>

      <!-- Step 1: Customer Verification (if not done) -->
      <div v-if="!step1Done" class="verification-form">
        <h4>👤 Langkah 1: Verifikasi Customer</h4>

        <!-- Photo Before Return -->
        <div class="form-group">
          <label for="photo-before">📸 Foto Kondisi Alat Saat Dikembalikan</label>
          <div class="photo-upload">
            <input 
              ref="photoBefore"
              type="file" 
              id="photo-before"
              accept="image/*"
              @change="handlePhotoBefore"
              style="display: none"
            >
            <button type="button" @click="$refs.photoBefore.click()" class="btn-upload">
              Pilih Foto
            </button>
            <span v-if="form.photoNameBefore" class="photo-name">
              ✓ {{ form.photoNameBefore }}
            </span>
          </div>
          <small>Ambil foto untuk dokumentasi kondisi saat dikembalikan</small>
        </div>

        <!-- Condition Description -->
        <div class="form-group">
          <label for="condition-desc">Kondisi Alat (1-5 bintang)</label>
          <div class="rating-select">
            <button 
              v-for="n in 5" 
              :key="n"
              type="button"
              @click="form.conditionRating = n"
              class="rating-btn"
              :class="{ active: form.conditionRating === n }"
            >
              ⭐ {{ n }}
            </button>
          </div>
        </div>

        <!-- Damage Notes -->
        <div class="form-group">
          <label for="damage-notes">Ada Kerusakan? (Opsional)</label>
          <textarea 
            v-model="form.damageNotes"
            id="damage-notes"
            rows="3"
            placeholder="Jelaskan jika ada kerusakan atau masalah dengan alat..."
          ></textarea>
        </div>

        <!-- Confirmation Checkbox -->
        <div class="form-group checkbox">
          <input 
            v-model="form.customerConfirmed" 
            type="checkbox"
            id="customer-confirm"
          >
          <label for="customer-confirm">
            Saya siap mengembalikan alat ini dan foto/kondisi di atas adalah akurat
          </label>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="error-message">
          ❌ {{ error }}
        </div>

        <!-- Actions -->
        <div class="form-actions">
          <button 
            @click="submitCustomerVerification" 
            class="btn-primary"
            :disabled="!form.customerConfirmed || !form.photoNameBefore || isSubmitting"
          >
            {{ isSubmitting ? 'Memproses...' : '✅ Kirim Verifikasi' }}
          </button>
          <button @click="$emit('cancel')" class="btn-secondary">
            Batal
          </button>
        </div>
      </div>

      <!-- Step 2: Staff Verification (if step 1 done) -->
      <div v-if="step1Done && !step2Done && isStaff" class="verification-form">
        <h4>👨‍💼 Langkah 2: Verifikasi Staff</h4>

        <!-- Review Customer Photo -->
        <div class="form-group">
          <label>📸 Foto dari Customer</label>
          <div class="photo-preview">
            <span>Foto kondisi alat dari customer telah diterima</span>
          </div>
        </div>

        <!-- Photo After Inspection -->
        <div class="form-group">
          <label for="photo-after">📸 Foto Hasil Inspeksi Staff</label>
          <div class="photo-upload">
            <input 
              ref="photoAfter"
              type="file" 
              id="photo-after"
              accept="image/*"
              @change="handlePhotoAfter"
              style="display: none"
            >
            <button type="button" @click="$refs.photoAfter.click()" class="btn-upload">
              Pilih Foto
            </button>
            <span v-if="form.photoNameAfter" class="photo-name">
              ✓ {{ form.photoNameAfter }}
            </span>
          </div>
        </div>

        <!-- Final Damage Assessment -->
        <div class="form-group">
          <label for="staff-damage">Catatan Kerusakan/Kondisi</label>
          <textarea 
            v-model="form.staffDamageNotes"
            id="staff-damage"
            rows="3"
            placeholder="Hasil inspeksi staff: kerusakan, cacat, atau catatan kondisi..."
          ></textarea>
        </div>

        <!-- Fine Summary -->
        <div v-if="isOverdue" class="fine-summary">
          <h5>💰 Perhitungan Denda</h5>
          <div class="summary-row">
            <span>Tanggal Target Kembali:</span>
            <strong>{{ formatDate(borrowing.plannedReturnDate) }}</strong>
          </div>
          <div class="summary-row">
            <span>Tanggal Pengembalian Aktual:</span>
            <strong>{{ formatDate(new Date()) }}</strong>
          </div>
          <div class="summary-row">
            <span>Hari Terlambat:</span>
            <strong>{{ lateDays }} hari</strong>
          </div>
          <div class="summary-row">
            <span>Tarif Per Hari:</span>
            <strong>Rp 50.000</strong>
          </div>
          <div class="summary-row total">
            <span>Total Denda:</span>
            <strong>Rp {{ estimatedFine.toLocaleString('id-ID') }}</strong>
          </div>
          <small>* Denda maksimal untuk 30 hari adalah Rp 1.500.000</small>
        </div>

        <!-- Confirmation Checkbox -->
        <div class="form-group checkbox">
          <input 
            v-model="form.staffConfirmed" 
            type="checkbox"
            id="staff-confirm"
          >
          <label for="staff-confirm">
            Saya telah menerima alat, verifikasi kondisi sesuai dengan catatan di atas, dan denda (jika ada) telah dicatat
          </label>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="error-message">
          ❌ {{ error }}
        </div>

        <!-- Success Message -->
        <div v-if="successMessage" class="success-message">
          ✅ {{ successMessage }}
        </div>

        <!-- Actions -->
        <div class="form-actions">
          <button 
            @click="submitStaffVerification" 
            class="btn-primary"
            :disabled="!form.staffConfirmed || isSubmitting"
          >
            {{ isSubmitting ? 'Memproses...' : '✅ Verifikasi & Selesaikan Pengembalian' }}
          </button>
          <button @click="$emit('cancel')" class="btn-secondary">
            Batal
          </button>
        </div>
      </div>

      <!-- Completion Message -->
      <div v-if="step2Done" class="completion-card">
        <h4>✅ Pengembalian Selesai</h4>
        <p>Terima kasih telah mengembalikan alat tepat waktu!</p>
        <div v-if="fineAmount > 0" class="fine-notice">
          <strong>Denda yang harus dibayar: Rp {{ fineAmount.toLocaleString('id-ID') }}</strong>
          <p>Silakan hubungi staff untuk pembayaran denda</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { verifyReturnCustomer, verifyReturnStaff } from '../../data/borrowingData.js'
import { calculateFine, formatDate, isOverdue, getDurationDays, STATUS_INFO } from '../../data/borrowingStatuses.js'

const props = defineProps({
  borrowing: {
    type: Object,
    required: true
  },
  isStaff: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['verify-customer', 'verify-staff', 'cancel'])

const photoBefore = ref(null)
const photoAfter = ref(null)
const isSubmitting = ref(false)
const error = ref('')
const successMessage = ref('')

const form = ref({
  photoNameBefore: '',
  photoFileBefore: null,
  conditionRating: 5,
  damageNotes: '',
  customerConfirmed: false,
  
  photoNameAfter: '',
  photoFileAfter: null,
  staffDamageNotes: '',
  staffConfirmed: false
})

const step1Done = computed(() => props.borrowing.returnVerifiedByCustomer)
const step2Done = computed(() => props.borrowing.returnVerifiedByStaff)

const statusInfo = computed(() => {
  return STATUS_INFO[props.borrowing.status] || STATUS_INFO.picked_up
})

const isLateBorrow = computed(() => {
  return isOverdue(props.borrowing.plannedReturnDate)
})

const lateDays = computed(() => {
  if (!isLateBorrow.value) return 0
  const plannedDate = new Date(props.borrowing.plannedReturnDate)
  const nowDate = new Date()
  const msPerDay = 24 * 60 * 60 * 1000
  return Math.ceil((nowDate - plannedDate) / msPerDay)
})

const duration = computed(() => {
  return getDurationDays(props.borrowing.borrowDate, props.borrowing.plannedReturnDate)
})

const estimatedFine = computed(() => {
  const fineCalc = calculateFine(
    props.borrowing.plannedReturnDate,
    new Date()
  )
  return fineCalc.fine
})

const fineAmount = computed(() => {
  return props.borrowing.fineAmount || 0
})

const handlePhotoBefore = (event) => {
  const file = event.target.files?.[0]
  if (file) {
    form.value.photoFileBefore = file
    form.value.photoNameBefore = file.name
  }
}

const handlePhotoAfter = (event) => {
  const file = event.target.files?.[0]
  if (file) {
    form.value.photoFileAfter = file
    form.value.photoNameAfter = file.name
  }
}

const submitCustomerVerification = async () => {
  if (!form.value.customerConfirmed) {
    error.value = 'Centang konfirmasi terlebih dahulu'
    return
  }

  isSubmitting.value = true
  error.value = ''

  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 500))

    const updatedBorrowing = verifyReturnCustomer(
      props.borrowing,
      form.value.photoNameBefore
    )

    emit('verify-customer', updatedBorrowing)
    successMessage.value = 'Verifikasi customer diterima. Tunggu verifikasi staff.'
  } catch (err) {
    error.value = 'Gagal mengirim verifikasi. Coba lagi.'
  } finally {
    isSubmitting.value = false
  }
}

const submitStaffVerification = async () => {
  if (!form.value.staffConfirmed) {
    error.value = 'Centang konfirmasi terlebih dahulu'
    return
  }

  isSubmitting.value = true
  error.value = ''

  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 500))

    const updatedBorrowing = verifyReturnStaff(
      props.borrowing,
      form.value.photoNameAfter,
      form.value.staffDamageNotes
    )

    emit('verify-staff', updatedBorrowing)
    successMessage.value = 'Pengembalian alat telah diverifikasi dan selesai!'
  } catch (err) {
    error.value = 'Gagal menyelesaikan pengembalian. Coba lagi.'
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
.return-verification-wrapper {
  background: white;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.return-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.header h3 {
  margin: 0;
  color: #333;
}

.status-badge {
  padding: 6px 12px;
  border-radius: 20px;
  color: white;
  font-size: 0.9rem;
  font-weight: 600;
}

.warning-box {
  background: #fff3cd;
  border-left: 4px solid #ffc107;
  padding: 16px;
  border-radius: 5px;
  color: #856404;
}

.warning-box strong {
  display: block;
  margin-bottom: 8px;
  font-size: 1.05rem;
}

.warning-box p {
  margin: 4px 0;
  font-size: 0.95rem;
}

.fine-warning {
  background: #ffe0e0;
  padding: 8px;
  border-radius: 4px;
  margin-top: 8px;
  font-weight: 600;
  color: #c82333;
}

.details-card,
.steps-card,
.verification-form,
.completion-card {
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 16px;
  background: #f9f9f9;
}

.details-card h4,
.steps-card h4,
.verification-form h4,
.completion-card h4 {
  margin-top: 0;
  color: #333;
  margin-bottom: 12px;
}

.detail-row {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
  border-bottom: 1px solid #e0e0e0;
}

.detail-row:last-child {
  border-bottom: none;
}

.detail-row .label {
  font-weight: 600;
  color: #666;
}

.detail-row .value {
  color: #333;
}

.step {
  display: flex;
  align-items: flex-start;
  gap: 15px;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  margin-bottom: 12px;
}

.step.completed {
  background: #d4edda;
  border-color: #28a745;
}

.step-number {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  background: #007bff;
  color: white;
  border-radius: 50%;
  font-weight: bold;
  flex-shrink: 0;
}

.step.completed .step-number {
  background: #28a745;
}

.step-content strong {
  display: block;
  color: #333;
  margin-bottom: 4px;
}

.step-content p {
  margin: 0;
  font-size: 0.9rem;
  color: #666;
}

.step-badge {
  margin-left: auto;
  background: #28a745;
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.85rem;
  font-weight: 600;
}

.form-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 16px;
}

.form-group label {
  font-weight: 600;
  margin-bottom: 8px;
  color: #333;
}

.form-group input[type="text"],
.form-group textarea {
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 1rem;
  font-family: inherit;
}

.form-group input[type="text"]:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.form-group small {
  font-size: 0.85rem;
  margin-top: 5px;
  color: #666;
}

.photo-upload {
  display: flex;
  align-items: center;
  gap: 10px;
}

.btn-upload {
  padding: 8px 16px;
  background: #17a2b8;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-upload:hover {
  background: #138496;
}

.photo-name {
  color: #28a745;
  font-weight: 500;
}

.photo-preview {
  padding: 12px;
  background: #e7f3ff;
  border-radius: 5px;
  color: #1976d2;
  border-left: 4px solid #2196f3;
}

.rating-select {
  display: flex;
  gap: 8px;
}

.rating-btn {
  padding: 8px 12px;
  border: 2px solid #ddd;
  background: white;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
}

.rating-btn:hover {
  border-color: #ffc107;
  background: #fffbf0;
}

.rating-btn.active {
  background: #ffc107;
  color: white;
  border-color: #ffc107;
}

.form-group.checkbox {
  flex-direction: row;
  align-items: flex-start;
  margin-bottom: 16px;
}

.form-group.checkbox input[type="checkbox"] {
  width: 18px;
  height: 18px;
  margin-right: 8px;
  margin-top: 2px;
  cursor: pointer;
}

.form-group.checkbox label {
  margin: 0;
  font-weight: 400;
  color: #555;
  line-height: 1.5;
}

.fine-summary {
  background: #fff3cd;
  border: 1px solid #ffc107;
  border-radius: 6px;
  padding: 12px;
  margin-bottom: 16px;
}

.fine-summary h5 {
  margin: 0 0 12px 0;
  color: #856404;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  padding: 6px 0;
  color: #856404;
}

.summary-row.total {
  border-top: 2px solid #ffc107;
  padding-top: 8px;
  margin-top: 8px;
  font-weight: bold;
  font-size: 1.1rem;
}

.error-message,
.success-message {
  padding: 12px;
  border-radius: 5px;
  margin-bottom: 16px;
}

.error-message {
  background: #f8d7da;
  color: #721c24;
  border-left: 4px solid #dc3545;
}

.success-message {
  background: #d4edda;
  color: #155724;
  border-left: 4px solid #28a745;
}

.completion-card {
  background: #d4edda;
  border: 2px solid #28a745;
  text-align: center;
}

.completion-card h4 {
  color: #155724;
}

.completion-card p {
  color: #155724;
  margin: 8px 0;
}

.fine-notice {
  background: #fff3cd;
  padding: 12px;
  border-radius: 5px;
  margin-top: 12px;
  color: #856404;
}

.fine-notice strong {
  display: block;
  margin-bottom: 6px;
}

.form-actions {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
  margin-top: 20px;
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

.btn-primary:hover:not(:disabled) {
  background: #0056b3;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

.btn-primary:disabled {
  background: #ccc;
  cursor: not-allowed;
  opacity: 0.6;
}

.btn-secondary {
  background: #6c757d;
  color: white;
}

.btn-secondary:hover {
  background: #545b62;
}

@media (max-width: 768px) {
  .header {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }

  .form-actions {
    flex-direction: column;
  }

  .btn-primary,
  .btn-secondary {
    width: 100%;
  }

  .rating-select {
    flex-wrap: wrap;
  }

  .rating-btn {
    flex: 1;
    min-width: 80px;
  }
}
</style>
