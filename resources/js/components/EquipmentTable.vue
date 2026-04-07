<template>
  <div class="equipment-table-container">
    <!-- Header dengan Search dan Actions -->
    <div class="table-header">
      <div class="search-box">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="🔍 Cari alat..."
          class="search-input"
        />
      </div>
      <div class="header-actions">
        <select v-model="categoryFilter" class="category-filter">
          <option value="">Semua Kategori</option>
          <option value="elektronik">Elektronik</option>
          <option value="peralatan">Peralatan</option>
          <option value="furnitur">Furnitur</option>
          <option value="lainnya">Lainnya</option>
        </select>
        <button @click="showAddModal = true" class="btn btn-primary" v-if="canEdit">
          ➕ Tambah Alat
        </button>
      </div>
    </div>

    <!-- Info Stats -->
    <div class="stats-row">
      <div class="stat-box">
        <span class="label">Total Alat:</span>
        <span class="value">{{ totalEquipment }}</span>
      </div>
      <div class="stat-box">
        <span class="label">Tersedia:</span>
        <span class="value available">{{ availableCount }}</span>
      </div>
      <div class="stat-box">
        <span class="label">Dipinjam:</span>
        <span class="value borrowed">{{ borrowedCount }}</span>
      </div>
      <div class="stat-box">
        <span class="label">Rusak/Hilang:</span>
        <span class="value damaged">{{ damagedCount }}</span>
      </div>
    </div>

    <!-- Tabel Alat -->
    <div class="table-wrapper">
      <table class="equipment-table" v-if="filteredEquipment.length > 0">
        <thead>
          <tr>
            <th>No.</th>
            <th>Nama Alat</th>
            <th>Kategori</th>
            <th>Total Stok</th>
            <th>Tersedia</th>
            <th>Dipinjam</th>
            <th>Denda/Hari</th>
            <th>Kondisi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(equipment, index) in filteredEquipment" :key="equipment.id_equipment">
            <td>{{ index + 1 }}</td>
            <td>
              <strong>{{ equipment.nama_alat }}</strong>
            </td>
            <td>
              <span class="category-badge">
                {{ equipment.category?.nama_kategori || 'N/A' }}
              </span>
            </td>
            <td class="text-center">
              <span class="stock-total">{{ equipment.total_stok || 0 }}</span>
            </td>
            <td class="text-center">
              <span class="stock-available">{{ equipment.available_quantity || 0 }}</span>
            </td>
            <td class="text-center">
              <span class="stock-borrowed">{{ (equipment.total_stok || 0) - (equipment.available_quantity || 0) }}</span>
            </td>
            <td>
              <strong>Rp {{ (equipment.fine_per_day || 50000).toLocaleString('id-ID') }}</strong>
            </td>
            <td>
              <span :class="['condition-badge', getConditionClass(equipment.kondisi)]">
                {{ getConditionLabel(equipment.kondisi) }}
              </span>
            </td>
            <td>
              <div class="action-buttons">
                <button
                  @click="openEditModal(equipment)"
                  class="btn-action btn-edit"
                  title="Edit"
                  v-if="canEdit"
                >
                  ✏️
                </button>
                <button
                  @click="openDeleteConfirm(equipment)"
                  class="btn-action btn-delete"
                  title="Hapus"
                  v-if="canEdit"
                >
                  🗑️
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Empty State -->
      <div v-else class="empty-state">
        <p>📭 Tidak ada alat yang ditemukan</p>
      </div>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showAddModal || showEditModal" class="modal-overlay">
      <div class="modal-content">
        <div class="modal-header">
          <h3>{{ showEditModal ? '✏️ Edit Alat' : '➕ Tambah Alat Baru' }}</h3>
          <button @click="closeModals()" class="btn-close">✕</button>
        </div>

        <form @submit.prevent="saveEquipment" class="modal-form">
          <div class="form-group">
            <label>Nama Alat *</label>
            <input
              v-model="formData.nama_alat"
              type="text"
              placeholder="Nama alat"
              required
            />
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Kategori *</label>
              <select v-model="formData.id_kategori" required>
                <option value="">Pilih Kategori</option>
                <option v-for="cat in categories" :key="cat.id_kategori" :value="cat.id_kategori">
                  {{ cat.nama_kategori }}
                </option>
              </select>
            </div>

            <div class="form-group">
              <label>Kondisi *</label>
              <select v-model="formData.kondisi" required>
                <option value="baik">Baik</option>
                <option value="sedang">Sedang</option>
                <option value="rusak">Rusak</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Total Stok *</label>
              <input
                v-model.number="formData.total_stok"
                type="number"
                min="0"
                required
              />
            </div>

            <div class="form-group">
              <label>Denda/Hari (Rp) *</label>
              <input 
                v-model.number="formData.fine_per_day" 
                type="number" 
                min="0"
                step="1000"
                placeholder="Contoh: 50000"
                required
              />
            </div>
          </div>

          <div class="form-group">
            <label>Deskripsi</label>
            <textarea
              v-model="formData.deskripsi"
              placeholder="Deskripsi alat"
              rows="2"
            ></textarea>
          </div>

          <div class="form-group">
            <label>Foto Alat * <span class="required-badge">(Wajib)</span></label>
            <div class="photo-upload-section">
              <div v-if="photoPreview" class="photo-preview">
                <img :src="photoPreview" alt="Preview" />
                <button type="button" @click="removePhoto" class="btn-remove-photo">✕ Hapus</button>
              </div>
              <div v-else class="photo-placeholder">
                <div class="upload-icon">📷</div>
                <p>Tidak ada foto dipilih</p>
              </div>
              <input
                ref="fileInput"
                type="file"
                accept="image/jpeg,image/png,image/webp"
                @change="handlePhotoSelect"
                class="file-input"
                :required="!showEditModal"
              />
              <button type="button" @click="$refs.fileInput.click()" class="btn btn-secondary">
                📁 Pilih Foto...
              </button>
            </div>
            <small class="file-info">Format: JPG, PNG, WebP. Ukuran maksimal: 5MB</small>
          </div>

          <div v-if="formError" class="alert alert-error">
            {{ formError }}
          </div>

          <div class="modal-footer">
            <button type="button" @click="closeModals()" class="btn btn-secondary">
              Batal
            </button>
            <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
              {{ isSubmitting ? '⏳ Menyimpan...' : 'Simpan' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteConfirm" class="modal-overlay">
      <div class="modal-content modal-small">
        <div class="modal-header">
          <h3>⚠️ Konfirmasi Penghapusan</h3>
        </div>
        <div class="modal-body">
          <p>
            Apakah Anda yakin ingin menghapus alat
            <strong>{{ deleteEquipment?.nama_alat }}</strong
            >?
          </p>
          <p class="text-danger">Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <div class="modal-footer">
          <button @click="showDeleteConfirm = false" class="btn btn-secondary">
            Batal
          </button>
          <button @click="deleteEquipmentConfirm()" class="btn btn-danger">
            Hapus Alat
          </button>
        </div>
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

const props = defineProps({
  canEdit: {
    type: Boolean,
    default: false
  }
})

const equipment = ref([])
const categories = ref([])
const searchQuery = ref('')
const categoryFilter = ref('')
const showAddModal = ref(false)
const showEditModal = ref(false)
const showDeleteConfirm = ref(false)
const isLoading = ref(false)
const isSubmitting = ref(false)
const formError = ref('')
const deleteEquipment = ref(null)

const formData = ref({
  nama_alat: '',
  id_kategori: '',
  kondisi: 'baik',
  total_stok: 0,
  fine_per_day: 0,
  deskripsi: '',
  photo: null,
})

const photoPreview = ref(null)
const fileInput = ref(null)

// Computed Properties
const filteredEquipment = computed(() => {
  return equipment.value.filter((item) => {
    const matchSearch =
      item.nama_alat.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.deskripsi?.toLowerCase().includes(searchQuery.value.toLowerCase())

    const matchCategory = !categoryFilter.value || 
      (item.category?.nama_kategori?.toLowerCase() === categoryFilter.value.toLowerCase())

    return matchSearch && matchCategory
  })
})

const totalEquipment = computed(() => equipment.value.length)

const availableCount = computed(() => {
  return equipment.value.reduce((sum, item) => sum + (item.available_quantity || 0), 0)
})

const borrowedCount = computed(() => {
  return equipment.value.reduce((sum, item) => {
    const total = item.total_stok || 0
    const available = item.available_quantity || 0
    return sum + Math.max(0, total - available)
  }, 0)
})

const damagedCount = computed(() => {
  return equipment.value.filter((item) => item.kondisi === 'rusak').length
})

// Methods
const loadEquipment = async () => {
  try {
    isLoading.value = true
    const response = await apiClient.get('/equipment')
    if (response.data.success) {
      equipment.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading equipment:', error)
    alert('Gagal memuat data alat')
  } finally {
    isLoading.value = false
  }
}

const loadCategories = async () => {
  try {
    const response = await apiClient.get('/categories')
    if (response.data.success) {
      categories.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading categories:', error)
  }
}

const getConditionLabel = (kondisi) => {
  const labels = {
    baik: '✅ Baik',
    sedang: '⚠️ Sedang',
    rusak: '❌ Rusak'
  }
  return labels[kondisi] || kondisi
}

const getConditionClass = (kondisi) => {
  return kondisi
}

const openEditModal = (item) => {
  formData.value = { ...item, id_kategori: item.id_kategori }
  photoPreview.value = item.gambar ? `/storage/${item.gambar}` : null
  showEditModal.value = true
  formError.value = ''
}

const closeModals = () => {
  showAddModal.value = false
  showEditModal.value = false
  formData.value = {
    nama_alat: '',
    id_kategori: '',
    kondisi: 'baik',
    total_stok: 0,
    fine_per_day: 0,
    deskripsi: '',
    photo: null,
  }
  photoPreview.value = null
  formError.value = ''
}

const saveEquipment = async () => {
  try {
    isSubmitting.value = true
    formError.value = ''

    // Validate photo field (mandatory)
    if (!showEditModal.value && !formData.value.photo) {
      formError.value = 'Gambar alat wajib diunggah!'
      isSubmitting.value = false
      return
    }

    // Create FormData for file upload
    const submission = new FormData()
    submission.append('nama_alat', formData.value.nama_alat)
    submission.append('id_kategori', formData.value.id_kategori || '')
    submission.append('kondisi', formData.value.kondisi)
    submission.append('total_stok', formData.value.total_stok)
    submission.append('fine_per_day', formData.value.fine_per_day)
    submission.append('deskripsi', formData.value.deskripsi || '')
    
    // Only append photo if a new file is selected
    if (formData.value.photo) {
      submission.append('photo', formData.value.photo)
    }

    if (showEditModal.value) {
      const response = await apiClient.post(`/equipment/${formData.value.id_equipment}?_method=PUT`, submission, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
      if (response.data.success) {
        const index = equipment.value.findIndex(
          (e) => e.id_equipment === formData.value.id_equipment
        )
        if (index > -1) {
          equipment.value[index] = response.data.data
        }
        closeModals()
        alert('Alat berhasil diperbarui!')
      }
    } else {
      const response = await apiClient.post('/equipment', submission, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
      if (response.data.success) {
        equipment.value.push(response.data.data)
        closeModals()
        alert('Alat berhasil ditambahkan!')
      }
    }
  } catch (error) {
    if (error.response?.data?.errors) {
      formError.value = Object.values(error.response.data.errors)
        .flat()
        .join(', ')
    } else {
      formError.value = error.response?.data?.message || 'Terjadi kesalahan'
    }
  } finally {
    isSubmitting.value = false
  }
}

const handlePhotoSelect = (event) => {
  const file = event.target.files[0]
  if (file) {
    // Validate file type
    const validTypes = ['image/jpeg', 'image/png', 'image/webp']
    if (!validTypes.includes(file.type)) {
      formError.value = 'Format foto hanya JPG, PNG, atau WebP'
      return
    }
    
    // Validate file size (max 5MB)
    const maxSize = 5 * 1024 * 1024
    if (file.size > maxSize) {
      formError.value = 'Ukuran foto tidak boleh lebih dari 5MB'
      return
    }
    
    formData.value.photo = file
    formError.value = ''
    
    // Create preview
    const reader = new FileReader()
    reader.onload = (e) => {
      photoPreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const removePhoto = () => {
  formData.value.photo = null
  photoPreview.value = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

const openDeleteConfirm = (item) => {
  deleteEquipment.value = item
  showDeleteConfirm.value = true
}

const deleteEquipmentConfirm = async () => {
  try {
    isSubmitting.value = true
    const response = await apiClient.delete(`/equipment/${deleteEquipment.value.id_equipment}`)
    if (response.data.success) {
      equipment.value = equipment.value.filter(
        (e) => e.id_equipment !== deleteEquipment.value.id_equipment
      )
      showDeleteConfirm.value = false
      alert('Alat berhasil dihapus!')
    }
  } catch (error) {
    alert(error.response?.data?.message || 'Gagal menghapus alat')
  } finally {
    isSubmitting.value = false
  }
}

// Lifecycle
onMounted(() => {
  loadEquipment()
  loadCategories()
})
</script>

<style scoped>
.equipment-table-container {
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

.category-filter {
  padding: 10px 14px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 14px;
  cursor: pointer;
  transition: border-color 0.3s ease;
}

.category-filter:focus {
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

.stat-box .value.available {
  color: #2f9e44;
}

.stat-box .value.borrowed {
  color: #f59f00;
}

.stat-box .value.damaged {
  color: #c92a2a;
}

/* Table Wrapper */
.table-wrapper {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  overflow: hidden;
}

.equipment-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

.equipment-table thead {
  background: linear-gradient(135deg, #0b7285 0%, #0c6b7d 100%);
  color: white;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.equipment-table th {
  padding: 16px 12px;
  text-align: left;
  border-bottom: 2px solid #0b7285;
}

.equipment-table tbody tr {
  border-bottom: 1px solid #f0f0f0;
  transition: background-color 0.2s ease;
}

.equipment-table tbody tr:hover {
  background-color: #f9f9f9;
}

.equipment-table td {
  padding: 14px 12px;
  vertical-align: middle;
}

.equipment-table td strong {
  color: #1a1a2e;
}

.text-center {
  text-align: center;
}

/* Badges and Labels */
.category-badge {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  background-color: #e7f5ff;
  color: #0b7285;
}

.stock-total {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  background-color: #f0f0f0;
  border-radius: 50%;
  font-weight: 600;
}

.stock-available {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  background-color: #d3f9d8;
  color: #2f9e44;
  border-radius: 50%;
  font-weight: 600;
}

.stock-borrowed {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  background-color: #fff3e0;
  color: #f59f00;
  border-radius: 50%;
  font-weight: 600;
}

.condition-badge {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
}

.condition-badge.baik {
  background-color: #d3f9d8;
  color: #2f9e44;
}

.condition-badge.sedang {
  background-color: #fff3e0;
  color: #f59f00;
}

.condition-badge.rusak {
  background-color: #ffe3e3;
  color: #c92a2a;
}

/* Action Buttons */
.action-buttons {
  display: flex;
  gap: 8px;
  justify-content: center;
}

.btn-action {
  padding: 8px 12px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  transition: all 0.3s ease;
  background: white;
  border: 1px solid #ddd;
}

.btn-action:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-action.btn-edit:hover {
  background-color: #e7f5ff;
  border-color: #0b7285;
}

.btn-action.btn-delete:hover:not(:disabled) {
  background-color: #ffe3e3;
  border-color: #c92a2a;
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

.modal-body p {
  margin: 8px 0;
  color: #666;
}

.modal-body .text-danger {
  color: #c92a2a;
  font-weight: 500;
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

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
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

.btn-danger {
  background-color: #c92a2a;
  color: white;
}

.btn-danger:hover:not(:disabled) {
  background-color: #b02d2d;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(201, 42, 42, 0.3);
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

  .form-row {
    grid-template-columns: 1fr;
  }

  .equipment-table {
    font-size: 12px;
  }

  .equipment-table th,
  .equipment-table td {
    padding: 10px 8px;
  }

  .action-buttons {
    flex-direction: column;
  }

  .btn-action {
    width: 100%;
  }
}

/* Photo Upload Styles */
.photo-upload-section {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.photo-preview {
  position: relative;
  display: inline-block;
  max-width: 100%;
}

.photo-preview img {
  max-width: 200px;
  max-height: 200px;
  border-radius: 8px;
  border: 2px solid #e0e0e0;
  object-fit: cover;
}

.photo-placeholder {
  min-height: 150px;
  border: 2px dashed #0b7285;
  border-radius: 8px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background-color: #f0f9ff;
  gap: 10px;
}

.upload-icon {
  font-size: 48px;
}

.photo-placeholder p {
  margin: 0;
  color: #666;
  font-size: 14px;
}

.file-input {
  display: none;
}

.btn-remove-photo {
  position: absolute;
  top: 8px;
  right: 8px;
  background-color: rgba(255, 255, 255, 0.9);
  border: none;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  cursor: pointer;
  font-size: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.btn-remove-photo:hover {
  background-color: #c92a2a;
  color: white;
}

.file-info {
  color: #999;
  font-size: 12px;
  margin-top: 4px;
}

.required-badge {
  color: #c92a2a;
  font-weight: bold;
  font-size: 12px;
}
</style>
