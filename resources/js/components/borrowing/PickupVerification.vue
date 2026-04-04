<template>
  <div class="pickup-verification-wrapper">
    <div v-if="!borrowing">
      <p>Tidak ada data peminjaman</p>
    </div>

    <div v-else class="verification-container">
      <!-- Header -->
      <div class="header">
        <h3>🚚 Verifikasi Pengambilan Alat</h3>
        <p class="status-badge" :style="{ backgroundColor: statusInfo.color }">
          {{ statusInfo.icon }} {{ statusInfo.label }}
        </p>
      </div>

      <!-- Borrowing Details -->
      <div class="details-card">
        <h4>Detail Peminjaman</h4>
        <div class="detail-row">
          <span class="label">ID Peminjaman:</span>
          <span class="value">{{ borrowing.id }}</span>
        </div>
        <div class="detail-row">
          <span class="label">Alat:</span>
          <span class="value">{{ borrowing.equipmentName }}</span>
        </div>
        <div class="detail-row">
          <span class="label">Jumlah:</span>
          <span class="value">{{ borrowing.equipmentQuantity }} unit</span>
        </div>
        <div class="detail-row">
          <span class="label">Pickup Code:</span>
          <span class="value code">{{ borrowing.pickupCode }}</span>
        </div>
        <div class="detail-row">
          <span class="label">Kode Berlaku Hingga:</span>
          <span class="value">{{ formatDate(pickupCodeExpiryDate) }}</span>
        </div>
      </div>

      <!-- Instructions -->
      <div class="instructions-card">
        <h4>📋 Instruksi Verifikasi</h4>
        <ol>
          <li>Datang ke gudang dengan identitas</li>
          <li>Serahkan pickup code kepada staff: <strong>{{ borrowing.pickupCode }}</strong></li>
          <li>Periksa kondisi alat</li>
          <li>Ambil foto/selfie kondisi alat sebelum dibawa</li>
          <li>Input pickup code di form di bawah</li>
          <li>Upload foto kondisi alat</li>
          <li>Klik "Konfirmasi Pengambilan"</li>
        </ol>
      </div>

      <!-- Verification Form -->
      <div class="verification-form">
        <h4>✅ Form Verifikasi</h4>

        <!-- Pickup Code Input -->
        <div class="form-group">
          <label for="code">Masukkan Pickup Code</label>
          <input 
            v-model="input.pickupCode" 
            type="text"
            id="code"
            placeholder="Contoh: 123-456"
            @input="validatePickupCode"
          >
          <small v-if="codeValidation.message" :class="codeValidation.class">
            {{ codeValidation.message }}
          </small>
        </div>

        <!-- Photo Upload -->
        <div class="form-group">
          <label for="photo">📸 Foto Kondisi Alat (Opsional)</label>
          <div class="photo-upload">
            <input 
              ref="photoInput"
              type="file" 
              id="photo"
              accept="image/*"
              @change="handlePhotoUpload"
              style="display: none"
            >
            <button type="button" @click="$refs.photoInput.click()" class="btn-upload">
              Pilih Foto
            </button>
            <span v-if="input.photoName" class="photo-name">
              ✓ {{ input.photoName }}
            </span>
          </div>
          <small>Upload foto untuk dokumentasi kondisi alat</small>
        </div>

        <!-- Confirmation Checkbox -->
        <div class="form-group checkbox">
          <input 
            v-model="input.confirmed" 
            type="checkbox"
            id="confirm"
          >
          <label for="confirm">
            Saya menyatakan bahwa alat telah saya periksa dan kondisinya sesuai dengan kondisi saat diambil
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
            @click="submitVerification" 
            class="btn-primary"
            :disabled="!canSubmit || isSubmitting"
          >
            {{ isSubmitting ? 'Memproses...' : '✅ Konfirmasi Pengambilan' }}
          </button>
          <button @click="$emit('cancel')" class="btn-secondary">
            Batal
          </button>
        </div>
      </div>

      <!-- Important Notes -->
      <div class="notes-card">
        <h4>⚠️ Penting</h4>
        <ul>
          <li>Pickup code hanya berlaku {{ PICKUP_CODE_VALIDITY }} hari</li>
          <li>Pastikan alat dalam kondisi baik sebelum diambil</li>
          <li>Dokumentasikan kondisi awal alat dengan foto</li>
          <li>Simpan bukti pickup code (screenshot/foto) untuk referensi</li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { verifyPickup } from '../../data/borrowingData.js'
import { formatDate, isValidPickupCode, STATUS_INFO } from '../../data/borrowingStatuses.js'

const props = defineProps({
  borrowing: {
    type: Object,
    required: true
  },
  staffId: {
    type: String,
    default: 'staff-1'
  }
})

const emit = defineEmits(['verify', 'cancel'])

const PICKUP_CODE_VALIDITY = 7

const photoInput = ref(null)
const isSubmitting = ref(false)
const error = ref('')
const successMessage = ref('')

const input = ref({
  pickupCode: '',
  photoFile: null,
  photoName: '',
  confirmed: false
})

const codeValidation = ref({
  message: '',
  class: ''
})

const pickupCodeExpiryDate = computed(() => {
  if (!props.borrowing.pickupCodeGeneratedAt) return new Date()
  const expiryDate = new Date(props.borrowing.pickupCodeGeneratedAt)
  expiryDate.setDate(expiryDate.getDate() + PICKUP_CODE_VALIDITY)
  return expiryDate
})

const statusInfo = computed(() => {
  return STATUS_INFO[props.borrowing.status] || STATUS_INFO.ready_for_pickup
})

const canSubmit = computed(() => {
  return input.value.pickupCode && input.value.confirmed && !error.value
})

const validatePickupCode = () => {
  const code = input.value.pickupCode.trim()

  if (!code) {
    codeValidation.value = { message: '', class: '' }
    error.value = ''
    return
  }

  if (!isValidPickupCode(code)) {
    codeValidation.value = {
      message: '❌ Format tidak sesuai (harus XXX-XXX)',
      class: 'error'
    }
    error.value = 'Format pickup code tidak valid'
    return
  }

  if (code !== props.borrowing.pickupCode) {
    codeValidation.value = {
      message: '❌ Kode tidak sesuai',
      class: 'error'
    }
    error.value = 'Pickup code tidak sesuai dengan yang terdaftar'
    return
  }

  codeValidation.value = {
    message: '✅ Kode valid',
    class: 'success'
  }
  error.value = ''
}

const handlePhotoUpload = (event) => {
  const file = event.target.files?.[0]
  if (file) {
    input.value.photoFile = file
    input.value.photoName = file.name
  }
}

const submitVerification = async () => {
  if (!canSubmit.value) return

  isSubmitting.value = true
  error.value = ''

  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 500))

    const verificationResult = verifyPickup(
      props.borrowing,
      input.value.pickupCode,
      props.staffId,
      input.value.photoName
    )

    if (!verificationResult.success) {
      error.value = verificationResult.message
      return
    }

    successMessage.value = 'Pengambilan alat telah diverifikasi!'
    emit('verify', verificationResult.borrowing)

    // Reset form
    setTimeout(() => {
      input.value = {
        pickupCode: '',
        photoFile: null,
        photoName: '',
        confirmed: false
      }
      successMessage.value = ''
    }, 2000)
  } catch (err) {
    error.value = 'Gagal memverifikasi pengambilan. Coba lagi.'
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
.pickup-verification-wrapper {
  background: white;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.verification-container {
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

.details-card,
.instructions-card,
.verification-form,
.notes-card {
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 16px;
  background: #f9f9f9;
}

.details-card h4,
.instructions-card h4,
.verification-form h4,
.notes-card h4 {
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

.detail-row .code {
  font-family: 'Courier New', monospace;
  font-size: 1.1rem;
  font-weight: bold;
  background: #e3f2fd;
  padding: 4px 8px;
  border-radius: 4px;
  color: #1976d2;
}

.instructions-card ol {
  padding-left: 20px;
  margin: 0;
  color: #555;
}

.instructions-card li {
  margin-bottom: 8px;
  line-height: 1.5;
}

.instructions-card strong {
  color: #333;
  background: #fff3cd;
  padding: 2px 6px;
  border-radius: 3px;
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

.form-group input[type="text"] {
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 1rem;
}

.form-group input[type="text"]:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.form-group small {
  font-size: 0.85rem;
  margin-top: 5px;
}

.form-group small.error {
  color: #dc3545;
}

.form-group small.success {
  color: #28a745;
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

.notes-card ul {
  padding-left: 20px;
  margin: 0;
  color: #555;
}

.notes-card li {
  margin-bottom: 8px;
  line-height: 1.5;
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
}
</style>
