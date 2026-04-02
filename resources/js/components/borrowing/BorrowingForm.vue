<template>
  <div class="borrowing-form-wrapper">
    <h2>📦 Pinjam Alat</h2>
    
    <div v-if="!showForm" class="no-form-state">
      <button @click="showForm = true" class="btn-primary">
        + Buat Permintaan Peminjaman
      </button>
    </div>

    <form v-else @submit.prevent="submitForm" class="borrowing-form">
      <!-- Equipment Selection -->
      <div class="form-group">
        <label for="equipment">Pilih Alat</label>
        <select v-model="form.equipmentId" id="equipment" required>
          <option value="">-- Pilih Alat --</option>
          <option v-for="eq in availableEquipment" :key="eq.id" :value="eq.id">
            {{ eq.image }} {{ eq.name }} ({{ eq.stock }} tersedia)
          </option>
        </select>
        <small v-if="selectedEquipment">{{ selectedEquipment.description }}</small>
      </div>

      <!-- Quantity -->
      <div class="form-group">
        <label for="quantity">Jumlah</label>
        <input 
          v-model.number="form.quantity" 
          type="number" 
          id="quantity" 
          min="1" 
          :max="selectedEquipment?.stock || 1"
          required
        >
      </div>

      <!-- Return Date -->
      <div class="form-group">
        <label for="returnDate">Tanggal Kembali</label>
        <input 
          v-model="form.returnDate" 
          type="date" 
          id="returnDate"
          :min="minReturnDate"
          required
        >
        <small>Minimal hari esok (7 hari default)</small>
      </div>

      <!-- Duration Display -->
      <div v-if="duration > 0" class="info-box">
        <strong>Durasi Peminjaman:</strong> {{ duration }} hari
      </div>

      <!-- Reason -->
      <div class="form-group">
        <label for="reason">Alasan Peminjaman (Opsional)</label>
        <textarea 
          v-model="form.reason" 
          id="reason" 
          rows="3"
          placeholder="Jelaskan tujuan penggunaan alat..."
        ></textarea>
      </div>

      <!-- Validation Messages -->
      <div v-if="validationError" class="error-message">
        ⚠️ {{ validationError }}
      </div>

      <!-- Actions -->
      <div class="form-actions">
        <button type="submit" class="btn-primary" :disabled="isSubmitting">
          {{ isSubmitting ? 'Mengirim...' : '✅ Kirim Permintaan' }}
        </button>
        <button type="button" class="btn-secondary" @click="resetForm">
          Batal
        </button>
      </div>
    </form>

    <!-- Success Message -->
    <div v-if="successMessage" class="success-message">
      ✅ {{ successMessage }}
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { equipmentList, createBorrowingRequest } from '../../data/borrowingData.js'

const props = defineProps({
  customer: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['submit'])

const showForm = ref(false)
const isSubmitting = ref(false)
const validationError = ref('')
const successMessage = ref('')

const form = ref({
  equipmentId: '',
  quantity: 1,
  returnDate: '',
  reason: ''
})

// Set default return date (7 days from now)
const getDefaultReturnDate = () => {
  const tomorrow = new Date()
  tomorrow.setDate(tomorrow.getDate() + 7)
  return tomorrow.toISOString().split('T')[0]
}

const minReturnDate = computed(() => {
  const tomorrow = new Date()
  tomorrow.setDate(tomorrow.getDate() + 1)
  return tomorrow.toISOString().split('T')[0]
})

const availableEquipment = computed(() => {
  return equipmentList.filter(eq => eq.stock > 0)
})

const selectedEquipment = computed(() => {
  return equipmentList.find(eq => eq.id === form.value.equipmentId)
})

const duration = computed(() => {
  if (!form.value.returnDate) return 0
  const today = new Date()
  const returnDate = new Date(form.value.returnDate)
  const timeDiff = returnDate - today
  const dayDiff = Math.ceil(timeDiff / (1000 * 60 * 60 * 24))
  return Math.max(0, dayDiff)
})

const validateForm = () => {
  validationError.value = ''

  if (!form.value.equipmentId) {
    validationError.value = 'Pilih alat terlebih dahulu'
    return false
  }

  if (form.value.quantity < 1) {
    validationError.value = 'Jumlah minimal 1'
    return false
  }

  if (form.value.quantity > selectedEquipment.value.stock) {
    validationError.value = `Stok hanya tersedia ${selectedEquipment.value.stock} unit`
    return false
  }

  if (!form.value.returnDate) {
    validationError.value = 'Tanggal kembali harus diisi'
    return false
  }

  const returnDate = new Date(form.value.returnDate)
  const tomorrow = new Date()
  tomorrow.setDate(tomorrow.getDate() + 1)
  tomorrow.setHours(0, 0, 0, 0)

  if (returnDate < tomorrow) {
    validationError.value = 'Tanggal kembali minimal hari esok'
    return false
  }

  return true
}

const submitForm = async () => {
  if (!validateForm()) return

  isSubmitting.value = true

  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 500))

    const borrowingRequest = createBorrowingRequest(
      props.customer,
      selectedEquipment.value,
      form.value.quantity,
      form.value.returnDate,
      form.value.reason
    )

    emit('submit', borrowingRequest)

    successMessage.value = `Permintaan peminjaman telah dikirim! ID: ${borrowingRequest.id}`
    resetForm()
    showForm.value = false

    // Clear success message after 3 seconds
    setTimeout(() => {
      successMessage.value = ''
    }, 3000)
  } catch (error) {
    validationError.value = 'Gagal mengirim permintaan. Coba lagi.'
  } finally {
    isSubmitting.value = false
  }
}

const resetForm = () => {
  form.value = {
    equipmentId: '',
    quantity: 1,
    returnDate: getDefaultReturnDate(),
    reason: ''
  }
  validationError.value = ''
}

// Initialize form
if (showForm.value) {
  form.value.returnDate = getDefaultReturnDate()
}
</script>

<style scoped>
.borrowing-form-wrapper {
  background: white;
  border-radius: 10px;
  padding: 20px;
  margin-bottom: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.borrowing-form-wrapper h2 {
  margin-top: 0;
  color: #333;
  font-size: 1.5rem;
  margin-bottom: 20px;
}

.no-form-state {
  text-align: center;
  padding: 20px;
}

.borrowing-form {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  grid-column: 1;
}

.form-group:nth-child(1),
.form-group:nth-child(4) {
  grid-column: 1 / -1;
}

.form-group label {
  font-weight: 600;
  margin-bottom: 8px;
  color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 1rem;
  font-family: inherit;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.form-group small {
  color: #666;
  font-size: 0.85rem;
  margin-top: 5px;
}

.info-box {
  grid-column: 1 / -1;
  background: #e7f3ff;
  border-left: 4px solid #2196F3;
  padding: 12px;
  border-radius: 4px;
  color: #1976D2;
  font-size: 0.95rem;
}

.error-message {
  grid-column: 1 / -1;
  background: #ffe7e7;
  border-left: 4px solid #dc3545;
  padding: 12px;
  border-radius: 4px;
  color: #c82333;
  font-size: 0.95rem;
}

.success-message {
  background: #e8f5e9;
  border-left: 4px solid #4caf50;
  padding: 12px;
  border-radius: 4px;
  color: #2e7d32;
  margin-top: 10px;
}

.form-actions {
  grid-column: 1 / -1;
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
  min-width: 150px;
}

.btn-primary:hover:not(:disabled) {
  background: #0056b3;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-secondary {
  background: #6c757d;
  color: white;
}

.btn-secondary:hover {
  background: #545b62;
}

@media (max-width: 768px) {
  .borrowing-form {
    grid-template-columns: 1fr;
  }

  .form-group {
    grid-column: 1 !important;
  }

  .form-group:nth-child(1),
  .form-group:nth-child(4) {
    grid-column: 1 !important;
  }
}
</style>
