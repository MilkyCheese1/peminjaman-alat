<template>
  <div class="browse-container">
    <div class="browse-header">
      <h2>🔍 Jelajahi Alat</h2>
      <p class="subtitle">Temukan alat yang ingin Anda pinjam</p>
    </div>

    <div class="filters-section">
      <div class="filter-group">
        <input 
          v-model="searchQuery"
          type="text" 
          placeholder="Cari nama alat..." 
          class="search-input"
        />
      </div>

      <div class="filter-group">
        <select v-model="selectedCategory" class="select-input">
          <option value="">Semua Kategori</option>
          <option v-for="cat in categories" :key="cat.id_kategori" :value="cat.id_kategori">
            {{ cat.nama_kategori }}
          </option>
        </select>
      </div>

      <div class="filter-group">
        <select v-model="sortBy" class="select-input">
          <option value="nama">Urutkan: Nama (A-Z)</option>

          <option value="stok">Urutkan: Stok Tersedia</option>
        </select>
      </div>
    </div>

    <div class="stats-bar">
      <span>Total Ditemukan: <strong>{{ filteredEquipment.length }}</strong></span>
      <span>Tersedia: <strong>{{ availableCount }}</strong></span>
    </div>

    <div v-if="filteredEquipment.length === 0" class="empty-state">
      <p>😢 Tidak ada alat yang cocok dengan pencarian Anda</p>
    </div>

    <div v-else class="equipment-grid">
      <div v-for="item in filteredEquipment" :key="item.id_equipment" class="equipment-card">
        <div class="card-image">
          <img v-if="item.gambar" :src="item.gambar" :alt="item.nama_alat" />
          <div class="stock-badge" :class="{ unavailable: item.available_quantity === 0 }">
            {{ item.available_quantity > 0 ? `${item.available_quantity} tersedia` : 'Tidak tersedia' }}
          </div>
        </div>

        <div class="card-content">
          <h3>{{ item.nama_alat }}</h3>
          <p class="category">{{ item.category_name || item.kategori }}</p>
          
          <div class="details">
            <div class="detail-item">
              <span class="label">Denda/Hari:</span>
              <span class="value">Rp {{ (item.fine_per_day || 50000).toLocaleString('id-ID') }}</span>
            </div>
            <div class="detail-item">
              <span class="label">Stok Total:</span>
              <span class="value">{{ item.total_stok }}</span>
            </div>
            <div class="detail-item">
              <span class="label">Kondisi:</span>
              <span class="value" :style="{ color: getConditionColor(item.kondisi) }">
                {{ getConditionBadge(item.kondisi) }}
              </span>
            </div>
          </div>

          <div class="card-description">
            {{ item.deskripsi || 'Tidak ada deskripsi' }}
          </div>

          <div class="card-actions">
            <button 
              @click="selectEquipmentForBorrowing(item)"
              :disabled="item.available_quantity === 0"
              class="btn btn-primary"
            >
              📋 Pinjam
            </button>
            <button 
              @click="showDetails(item)"
              class="btn btn-secondary"
            >
              ℹ️ Detail
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Equipment Detail Modal -->
    <div v-if="showDetailModal" class="modal-overlay" @click.self="closeDetailModal">
      <div class="modal">
        <div class="modal-header">
          <h3>{{ selectedEquipment?.nama_alat }}</h3>
          <button @click="closeDetailModal" class="close-btn">✕</button>
        </div>
        <div class="modal-body">
          <div class="modal-image">
            <img v-if="selectedEquipment?.gambar" :src="selectedEquipment?.gambar" :alt="selectedEquipment?.nama_alat" />
          </div>
          <div class="modal-info">
            <div class="info-row">
              <span class="label">Kategori:</span>
              <span class="value">{{ selectedEquipment?.category_name }}</span>
            </div>
            <div class="info-row">
              <span class="label">Deskripsi:</span>
              <span class="value">{{ selectedEquipment?.deskripsi }}</span>
            </div>
            <div class="info-row">
              <span class="label">Kondisi:</span>
              <span class="value" :style="{ color: getConditionColor(selectedEquipment?.kondisi) }">
                {{ getConditionBadge(selectedEquipment?.kondisi) }}
              </span>
            </div>
            <div class="info-row">
              <span class="label">Denda/Hari:</span>
              <span class="value">Rp {{ (selectedEquipment?.fine_per_day || 50000).toLocaleString('id-ID') }}</span>
            </div>
            <div class="info-row">
              <span class="label">Stok Total:</span>
              <span class="value">{{ selectedEquipment?.total_stok }}</span>
            </div>
            <div class="info-row">
              <span class="label">Stok Tersedia:</span>
              <span class="value">{{ selectedEquipment?.available_quantity }}</span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button @click="closeDetailModal" class="btn btn-secondary">Tutup</button>
          <button 
            @click="selectEquipmentForBorrowing(selectedEquipment)"
            :disabled="selectedEquipment?.available_quantity === 0"
            class="btn btn-primary"
          >
            📋 Pinjam
          </button>
        </div>
      </div>
    </div>

    <!-- Borrowing Form Modal (New) -->
    <BorrowingFormComponent 
      :showModal="showBorrowingModal"
      :selectedEquipment="selectedEquipment"
      @close="closeBorrowingModal"
      @success="onBorrowingSuccess"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import apiClient from '@/config/api'
import { useSessionRestoration } from '@/composables/useSessionRestoration.js'
import { useToast } from '@/composables/useToast'
import BorrowingFormComponent from './BorrowingFormComponent.vue'

const { saveState, getState } = useSessionRestoration()

const equipment = ref([])
const categories = ref([])
const searchQuery = ref('')
const selectedCategory = ref('')
const sortBy = ref('nama')
const showDetailModal = ref(false)
const showBorrowingModal = ref(false)
const selectedEquipment = ref(null)
const { error: showError } = useToast()

const today = computed(() => {
  const d = new Date()
  return d.toISOString().split('T')[0]
})

const filteredEquipment = computed(() => {
  let result = equipment.value

  // Filter by search query
  if (searchQuery.value) {
    result = result.filter(item =>
      item.nama_alat.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      (item.deskripsi && item.deskripsi.toLowerCase().includes(searchQuery.value.toLowerCase()))
    )
  }

  // Filter by category
  if (selectedCategory.value) {
    result = result.filter(item => item.id_category == selectedCategory.value)
  }

  // Sort
  switch (sortBy.value) {
    case 'nama':
      result.sort((a, b) => a.nama_alat.localeCompare(b.nama_alat))
      break

    case 'stok':
      result.sort((a, b) => b.available_quantity - a.available_quantity)
      break
  }

  return result
})

const availableCount = computed(() => {
  return filteredEquipment.value.filter(item => item.available_quantity > 0).length
})



const loadEquipment = async () => {
  try {
    const response = await apiClient.get('/equipment')
    if (response.data.success) {
      equipment.value = response.data.data
    }
  } catch (error) {
    // Silently handle equipment loading error
  }
}

const loadCategories = async () => {
  try {
    const response = await apiClient.get('/categories')
    if (response.data.success) {
      categories.value = response.data.data
    }
  } catch (error) {
    // Silently handle category loading error
  }
}

const getConditionBadge = (condition) => {
  const badges = {
    'Baik': '✅ Baik',
    'Sedang': '⚠️ Sedang',
    'Rusak': '❌ Rusak',
    'good': '✅ Baik',
    'fair': '⚠️ Sedang',
    'poor': '❌ Rusak'
  }
  return badges[condition] || condition
}

const getConditionColor = (condition) => {
  const colors = {
    'Baik': '#51cf66',
    'Sedang': '#ff9800',
    'Rusak': '#ff6b6b',
    'good': '#51cf66',
    'fair': '#ff9800',
    'poor': '#ff6b6b'
  }
  return colors[condition] || '#666'
}

const showDetails = (item) => {
  selectedEquipment.value = item
  showDetailModal.value = true
}

const closeDetailModal = () => {
  showDetailModal.value = false
}

const selectEquipmentForBorrowing = (item) => {
  if (item.available_quantity === 0) {
    showError('Alat ini tidak tersedia saat ini')
    return
  }
  selectedEquipment.value = item
  showDetailModal.value = false
  showBorrowingModal.value = true
}

const closeBorrowingModal = () => {
  showBorrowingModal.value = false
}

const onBorrowingSuccess = () => {
  closeBorrowingModal()
  loadEquipment()
}

onMounted(() => {
  // Restore filter state from session
  const savedSearch = getState('browse_searchQuery', '')
  const savedCategory = getState('browse_selectedCategory', '')
  const savedSort = getState('browse_sortBy', 'nama')
  
  searchQuery.value = savedSearch
  selectedCategory.value = savedCategory
  sortBy.value = savedSort
  
  loadEquipment()
  loadCategories()
})

// Save filter/search state whenever it changes
watch(searchQuery, (newValue) => {
  saveState('browse_searchQuery', newValue)
})

watch(selectedCategory, (newValue) => {
  saveState('browse_selectedCategory', newValue)
})

watch(sortBy, (newValue) => {
  saveState('browse_sortBy', newValue)
})
</script>

<style scoped>
.browse-container {
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

.browse-header {
  margin-bottom: 25px;
}

.browse-header h2 {
  margin: 0 0 5px 0;
  color: #1a1a2e;
}

.subtitle {
  margin: 0;
  color: #666;
  font-size: 0.95rem;
}

.filters-section {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
  margin-bottom: 20px;
}

.filter-group {
  display: flex;
  flex-direction: column;
}

.search-input,
.select-input {
  padding: 12px;
  border: 1px solid #e0e0e0;
  border-radius: 6px;
  font-size: 0.95rem;
  font-family: inherit;
}

.search-input:focus,
.select-input:focus {
  outline: none;
  border-color: #0b7285;
  box-shadow: 0 0 0 3px rgba(11, 114, 133, 0.1);
}

.stats-bar {
  display: flex;
  gap: 20px;
  padding: 15px;
  background: #f5f5f5;
  border-radius: 6px;
  margin-bottom: 20px;
  font-size: 0.95rem;
}

.stats-bar strong {
  color: #0b7285;
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: #666;
  font-size: 1.1rem;
}

.equipment-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 20px;
}

.equipment-card {
  background: white;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  overflow: hidden;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
}

.equipment-card:hover {
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
  transform: translateY(-4px);
  border-color: #0b7285;
}

.card-image {
  position: relative;
  width: 100%;
  height: 200px;
  background: #f5f5f5;
  overflow: hidden;
}

.card-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.stock-badge {
  position: absolute;
  top: 10px;
  right: 10px;
  background: rgba(81, 207, 102, 0.95);
  color: white;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
}

.stock-badge.unavailable {
  background: rgba(255, 107, 107, 0.95);
}

.card-content {
  padding: 15px;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.card-content h3 {
  margin: 0 0 5px 0;
  color: #1a1a2e;
  font-size: 1.1rem;
}

.category {
  margin: 0 0 12px 0;
  color: #666;
  font-size: 0.9rem;
  background: #f0f0f0;
  padding: 4px 8px;
  border-radius: 4px;
  display: inline-block;
}

.details {
  background: #f9f9f9;
  padding: 12px;
  border-radius: 6px;
  margin-bottom: 12px;
  flex: 1;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  padding: 6px 0;
  font-size: 0.9rem;
  border-bottom: 1px solid #e0e0e0;
}

.detail-item:last-child {
  border-bottom: none;
}

.detail-item .label {
  color: #666;
  font-weight: 600;
}

.detail-item .value {
  color: #1a1a2e;
}

.card-description {
  font-size: 0.85rem;
  color: #666;
  margin-bottom: 12px;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.card-actions {
  display: flex;
  gap: 8px;
  margin-top: auto;
}

.btn {
  flex: 1;
  padding: 10px 12px;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.85rem;
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
}

.btn-primary:disabled {
  opacity: 0.5;
  cursor: not-allowed;
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
  padding: 20px;
}

.modal {
  background: white;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
  width: 100%;
  max-width: 500px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  max-height: 90vh;
}

.modal-large {
  max-width: 600px;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 1px solid #e0e0e0;
  background: linear-gradient(135deg, #0b7285 0%, #0c6b7d 100%);
  color: white;
}

.modal-header h3 {
  margin: 0;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: white;
}

.modal-body {
  padding: 20px;
  overflow-y: auto;
  flex: 1;
}

.modal-image {
  width: 100%;
  height: 200px;
  background: #f5f5f5;
  border-radius: 6px;
  overflow: hidden;
  margin-bottom: 20px;
}

.modal-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.modal-info {
  display: grid;
  gap: 12px;
}

.info-row {
  display: grid;
  grid-template-columns: 120px 1fr;
  gap: 15px;
  padding: 10px 0;
  border-bottom: 1px solid #f0f0f0;
}

.info-row:last-child {
  border-bottom: none;
}

.info-row .label {
  font-weight: 600;
  color: #666;
}

.info-row .value {
  color: #1a1a2e;
}

.borrowing-form {
  display: grid;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  margin-bottom: 8px;
  font-weight: 600;
  color: #1a1a2e;
  font-size: 0.95rem;
}

.form-group small {
  margin-top: 4px;
  color: #999;
  font-size: 0.85rem;
}

.input,
.textarea {
  padding: 12px;
  border: 1px solid #e0e0e0;
  border-radius: 6px;
  font-family: inherit;
  font-size: 0.95rem;
}

.input:focus,
.textarea:focus {
  outline: none;
  border-color: #0b7285;
  box-shadow: 0 0 0 3px rgba(11, 114, 133, 0.1);
}

.textarea {
  min-height: 80px;
  resize: vertical;
}

.cost-summary {
  background: #f9f9f9;
  padding: 15px;
  border-radius: 6px;
  border: 1px solid #e0e0e0;
}

.cost-summary h4 {
  margin: 0 0 12px 0;
  color: #1a1a2e;
}

.cost-row {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
  border-bottom: 1px solid #e0e0e0;
  font-size: 0.95rem;
}

.cost-row:last-child {
  border-bottom: none;
}

.cost-row.total {
  font-weight: 600;
  color: #0b7285;
  font-size: 1.1rem;
}

.modal-footer {
  display: flex;
  gap: 10px;
  padding: 20px;
  border-top: 1px solid #e0e0e0;
  background: #f5f5f5;
}

.modal-footer .btn {
  flex: 1;
}

@media (max-width: 768px) {
  .equipment-grid {
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 15px;
  }

  .modal {
    max-width: 95%;
  }

  .modal-large {
    max-width: 95%;
  }

  .info-row {
    grid-template-columns: 1fr;
  }

  .info-row .label {
    margin-bottom: 4px;
  }
}
</style>
