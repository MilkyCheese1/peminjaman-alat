<template>
  <div class="recommendations-container">
    <div class="recommendations-header">
      <h2>⭐ Rekomendasi untuk Anda</h2>
      <p class="subtitle">Alat-alat yang mungkin ingin Anda pinjam</p>
    </div>

    <div class="recommendation-sections">
      <!-- Based on Borrowing History -->
      <div class="rec-section">
        <h3>📚 Berdasarkan Riwayat Peminjaman</h3>
        <div v-if="historyBasedRecs.length === 0" class="empty-section">
          <p>Mulai pinjam alat untuk mendapat rekomendasi yang dipersonalisasi</p>
        </div>
        <div v-else class="rec-grid">
          <div v-for="item in historyBasedRecs" :key="item.id_equipment" class="rec-card">
            <div class="rec-image">
              <img v-if="item.gambar" :src="item.gambar" :alt="item.nama_alat" />
            </div>
            <div class="rec-info">
              <h4>{{ item.nama_alat }}</h4>
              <p class="category">{{ item.category_name }}</p>

              <button 
                @click="borrowEquipment(item)"
                :disabled="item.available_quantity === 0"
                class="btn btn-small"
              >
                📋 Pinjam
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Popular Items -->
      <div class="rec-section">
        <h3>🔥 Alat Populer</h3>
        <div class="rec-grid">
          <div v-for="item in popularItems" :key="item.id_equipment" class="rec-card">
            <div class="rec-image">
              <img v-if="item.gambar" :src="item.gambar" :alt="item.nama_alat" />
              <div class="popularity-badge">{{ item.borrow_count }} dipinjam</div>
            </div>
            <div class="rec-info">
              <h4>{{ item.nama_alat }}</h4>
              <p class="category">{{ item.category_name }}</p>
              <button 
                @click="borrowEquipment(item)"
                :disabled="item.available_quantity === 0"
                class="btn btn-small"
              >
                📋 Pinjam
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- New Items -->
      <div class="rec-section">
        <h3>✨ Alat Terbaru</h3>
        <div class="rec-grid">
          <div v-for="item in newItems" :key="item.id_equipment" class="rec-card">
            <div class="rec-image">
              <img v-if="item.gambar" :src="item.gambar" :alt="item.nama_alat" />
              <div class="new-badge">BARU</div>
            </div>
            <div class="rec-info">
              <h4>{{ item.nama_alat }}</h4>
              <p class="category">{{ item.category_name }}</p>
              <button 
                @click="borrowEquipment(item)"
                :disabled="item.available_quantity === 0"
                class="btn btn-small"
              >
                📋 Pinjam
              </button>
            </div>
          </div>
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
import { ref, computed, onMounted } from 'vue'
import apiClient from '@/config/api'
import BorrowingFormComponent from './BorrowingFormComponent.vue'
import { useToast } from '@/composables/useToast'

const equipment = ref([])
const userBorrowingHistory = ref([])
const showBorrowingModal = ref(false)
const selectedEquipment = ref(null)
const { error: showError } = useToast()

const today = computed(() => {
  const d = new Date()
  return d.toISOString().split('T')[0]
})

const historyBasedRecs = computed(() => {
  if (userBorrowingHistory.value.length === 0) return []

  // Get categories from borrowing history (via equipment relationship)
  const historyCategories = new Set(
    userBorrowingHistory.value
      .map(b => b.equipment?.id_category)
      .filter(Boolean)
  )

  // Recommend similar items (same category, not yet borrowed)
  const borrowedIds = new Set(userBorrowingHistory.value.map(b => b.id_equipment))
  return equipment.value
    .filter(item => historyCategories.has(item.id_category) && !borrowedIds.has(item.id_equipment))
    .slice(0, 6)
})

const popularItems = computed(() => {
  return equipment.value
    .sort((a, b) => (b.borrow_count || 0) - (a.borrow_count || 0))
    .slice(0, 6)
})

const newItems = computed(() => {
  return equipment.value
    .sort((a, b) => new Date(b.created_at || 0) - new Date(a.created_at || 0))
    .slice(0, 6)
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

const loadUserBorrowingHistory = async () => {
  try {
    const userStr = localStorage.getItem('user')
    if (!userStr) return

    const user = JSON.parse(userStr)
    const response = await apiClient.get(`/borrowings?user_id=${user.id_user}`)
    if (response.data.success) {
      userBorrowingHistory.value = response.data.data
    }
  } catch (error) {
    // Silently handle borrowing history loading error
  }
}

const borrowEquipment = (item) => {
  if (item.available_quantity === 0) {
    showError('Alat ini tidak tersedia saat ini')
    return
  }
  selectedEquipment.value = item
  showBorrowingModal.value = true
}

const closeBorrowingModal = () => {
  showBorrowingModal.value = false
}

const onBorrowingSuccess = () => {
  closeBorrowingModal()
  loadUserBorrowingHistory()
}

onMounted(() => {
  loadEquipment()
  loadUserBorrowingHistory()
})
</script>

<style scoped>
.recommendations-container {
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

.recommendations-header {
  margin-bottom: 25px;
}

.recommendations-header h2 {
  margin: 0 0 5px 0;
  color: #1a1a2e;
}

.subtitle {
  margin: 0;
  color: #666;
  font-size: 0.95rem;
}

.recommendation-sections {
  display: grid;
  gap: 30px;
}

.rec-section {
  background: white;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.rec-section h3 {
  margin: 0 0 20px 0;
  color: #1a1a2e;
  font-size: 1.2rem;
  border-bottom: 2px solid #f0f0f0;
  padding-bottom: 10px;
}

.empty-section {
  text-align: center;
  padding: 40px 20px;
  color: #999;
}

.rec-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
  gap: 15px;
}

.rec-card {
  background: #f9f9f9;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  overflow: hidden;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
}

.rec-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  border-color: #0b7285;
  transform: translateY(-2px);
}

.rec-image {
  position: relative;
  width: 100%;
  height: 120px;
  background: #f0f0f0;
  overflow: hidden;
}

.rec-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.popularity-badge,
.new-badge {
  position: absolute;
  top: 8px;
  right: 8px;
  font-size: 0.7rem;
  font-weight: 600;
  padding: 4px 8px;
  border-radius: 4px;
  color: white;
}

.popularity-badge {
  background: rgba(255, 107, 107, 0.9);
}

.new-badge {
  background: rgba(81, 207, 102, 0.9);
}

.rec-info {
  padding: 12px;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.rec-info h4 {
  margin: 0 0 4px 0;
  color: #1a1a2e;
  font-size: 0.95rem;
  line-height: 1.3;
}

.category {
  margin: 0 0 6px 0;
  color: #666;
  font-size: 0.8rem;
  background: #e0e0e0;
  padding: 2px 6px;
  border-radius: 3px;
  display: inline-block;
  width: fit-content;
}

.price {
  margin: 0 0 8px 0;
  color: #0b7285;
  font-weight: 600;
  font-size: 0.9rem;
}

.btn-small {
  width: 100%;
  padding: 8px 12px;
  background-color: #0b7285;
  color: white;
  border: none;
  border-radius: 4px;
  font-weight: 600;
  font-size: 0.85rem;
  cursor: pointer;
  transition: all 0.2s ease;
  margin-top: auto;
}

.btn-small:hover:not(:disabled) {
  background-color: #0a5f6d;
  transform: translateY(-1px);
}

.btn-small:disabled {
  opacity: 0.5;
  cursor: not-allowed;
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
  padding: 12px;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-primary {
  background-color: #0b7285;
  color: white;
}

.btn-primary:hover {
  background-color: #0a5f6d;
}

.btn-secondary {
  background-color: #e0e0e0;
  color: #333;
}

.btn-secondary:hover {
  background-color: #d0d0d0;
}

@media (max-width: 768px) {
  .rec-grid {
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
  }

  .modal {
    max-width: 95%;
  }

  .modal-large {
    max-width: 95%;
  }
}
</style>
