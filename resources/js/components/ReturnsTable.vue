<template>
  <div class="returns-table-container">
    <!-- Header -->
    <div class="table-header">
      <div class="search-box">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="🔍 Cari pengembalian..."
          class="search-input"
        />
      </div>
      <div class="header-actions">
        <select v-model="statusFilter" class="status-filter">
          <option value="">Semua Status</option>
          <option value="on_time">Tepat Waktu</option>
          <option value="late">Terlambat</option>
          <option value="damaged">Rusak</option>
        </select>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
      <div class="stat-box">
        <span class="label">Total Pengembalian:</span>
        <span class="value">{{ totalReturns }}</span>
      </div>
      <div class="stat-box">
        <span class="label">Tepat Waktu:</span>
        <span class="value on-time">{{ onTimeCount }}</span>
      </div>
      <div class="stat-box">
        <span class="label">Terlambat:</span>
        <span class="value late">{{ lateCount }}</span>
      </div>
      <div class="stat-box">
        <span class="label">Rusak:</span>
        <span class="value damaged">{{ damagedCount }}</span>
      </div>
    </div>

    <!-- Tabel -->
    <div class="table-wrapper">
      <table class="returns-table" v-if="filteredReturns.length > 0">
        <thead>
          <tr>
            <th>No.</th>
            <th>ID Peminjaman</th>
            <th>Peminjam</th>
            <th>Alat</th>
            <th>Tanggal Kembali Rencana</th>
            <th>Tanggal Kembali Aktual</th>
            <th>Kondisi</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in filteredReturns" :key="item.id_peminjaman">
            <td>{{ index + 1 }}</td>
            <td><strong>#{{ item.id_peminjaman }}</strong></td>
            <td>{{ item.user?.nama_lengkap || item.nama_peminjam || 'N/A' }}</td>
            <td>{{ item.equipment?.nama_alat || item.nama_alat || 'N/A' }}</td>
            <td>{{ formatDate(item.tanggal_rencana_kembali || item.planned_return_date) }}</td>
            <td>{{ formatDate(item.actual_return_date || item.tanggal_kembali) || '-' }}</td>
            <td>
              <span :class="['condition-badge', getConditionClass(item.returnDetails?.kondisi)]">
                {{ getConditionLabel(item.returnDetails?.kondisi) }}
              </span>
            </td>
            <td>
              <span :class="['status-badge', getStatusClass(item.actual_return_date || item.tanggal_kembali, item.tanggal_rencana_kembali || item.planned_return_date, item.returnDetails?.kondisi)]">
                {{ getReturnStatus(item.actual_return_date || item.tanggal_kembali, item.tanggal_rencana_kembali || item.planned_return_date, item.returnDetails?.kondisi) }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-else class="empty-state">
        <p>📭 Tidak ada data pengembalian</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import apiClient from '@/config/api'
import { useToast } from '@/composables/useToast'

const borrowings = ref([])
const searchQuery = ref('')
const statusFilter = ref('')
const isLoading = ref(false)
const { error: showError } = useToast()

const filteredReturns = computed(() => {
  return borrowings.value
    .filter(b => b.status === 'returned' || b.actual_return_date || b.tanggal_kembali)
    .filter(b => {
      const matchSearch =
        (b.user?.nama_lengkap || b.nama_peminjam || '')?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        (b.equipment?.nama_alat || b.nama_alat || '')?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        b.id_peminjaman?.toString().includes(searchQuery.value)

      let matchStatus = true
      if (statusFilter.value) {
        const actualReturnDate = b.actual_return_date || b.tanggal_kembali
        const plannedReturnDate = b.tanggal_rencana_kembali || b.planned_return_date
        const returnStatus = getReturnStatus(actualReturnDate, plannedReturnDate, b.returnDetails?.kondisi)
        if (statusFilter.value === 'on_time') {
          matchStatus = returnStatus.includes('Tepat')
        } else if (statusFilter.value === 'late') {
          matchStatus = returnStatus.includes('Terlambat')
        } else if (statusFilter.value === 'damaged') {
          matchStatus = b.returnDetails?.kondisi !== 'baik'
        }
      }

      return matchSearch && matchStatus
    })
})

const totalReturns = computed(() => {
  return borrowings.value.filter(b => b.actual_return_date || b.tanggal_kembali).length
})

const onTimeCount = computed(() => {
  return borrowings.value.filter(b => {
    const actualReturnDate = b.actual_return_date || b.tanggal_kembali
    if (!actualReturnDate) return false
    const returnDate = new Date(actualReturnDate)
    const dueDate = new Date(b.tanggal_rencana_kembali || b.planned_return_date)
    return returnDate <= dueDate
  }).length
})

const lateCount = computed(() => {
  return borrowings.value.filter(b => {
    const actualReturnDate = b.actual_return_date || b.tanggal_kembali
    if (!actualReturnDate) return false
    const returnDate = new Date(actualReturnDate)
    const dueDate = new Date(b.tanggal_rencana_kembali || b.planned_return_date)
    return returnDate > dueDate
  }).length
})

const damagedCount = computed(() => {
  return borrowings.value.filter(b => b.returnDetails?.kondisi && b.returnDetails.kondisi !== 'baik').length
})

const loadBorrowings = async () => {
  try {
    isLoading.value = true
    const response = await apiClient.get('/borrowings')
    if (response.data.success) {
      borrowings.value = response.data.data
    }
  } catch (error) {
    // Silently handle borrowings loading error
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

const getConditionLabel = (kondisi) => {
  const labels = { baik: '✅ Baik', sedang: '⚠️ Sedang', rusak: '❌ Rusak' }
  return labels[kondisi] || 'Tidak Diketahui'
}

const getConditionClass = (kondisi) => {
  return kondisi || 'unknown'
}

const getReturnStatus = (tanggal_kembali, tanggal_rencana, kondisi) => {
  if (!tanggal_kembali) return 'Belum Dikembalikan'
  const returnDate = new Date(tanggal_kembali)
  const dueDate = new Date(tanggal_rencana)
  const isLate = returnDate > dueDate
  const isDamaged = kondisi && kondisi !== 'baik'
  
  if (isLate && isDamaged) return '⭠ Terlambat & Rusak'
  if (isLate) return '⚠️ Terlambat'
  if (isDamaged) return '🔴 Rusak'
  return '✅ Tepat Waktu'
}

const getStatusClass = (tanggal_kembali, tanggal_rencana, kondisi) => {
  if (!tanggal_kembali) return 'pending'
  const returnDate = new Date(tanggal_kembali)
  const dueDate = new Date(tanggal_rencana)
  return returnDate > dueDate ? 'late' : 'on-time'
}

onMounted(() => {
  loadBorrowings()
})
</script>

<style scoped>
.returns-table-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

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
}

.search-input:focus {
  outline: none;
  border-color: #0b7285;
}

.status-filter {
  padding: 10px 14px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 14px;
  cursor: pointer;
}

.status-filter:focus {
  outline: none;
  border-color: #0b7285;
}

.stats-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 12px;
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

.stat-box .value.on-time {
  color: #2f9e44;
}

.stat-box .value.late {
  color: #f59f00;
}

.stat-box .value.damaged {
  color: #c92a2a;
}

.table-wrapper {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  overflow: hidden;
}

.returns-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

.returns-table thead {
  background: linear-gradient(135deg, #0b7285 0%, #0c6b7d 100%);
  color: white;
  font-weight: 600;
}

.returns-table th {
  padding: 16px 12px;
  text-align: left;
}

.returns-table tbody tr {
  border-bottom: 1px solid #f0f0f0;
  transition: background-color 0.2s ease;
}

.returns-table tbody tr:hover {
  background-color: #f9f9f9;
}

.returns-table td {
  padding: 14px 12px;
}

.text-danger {
  color: #c92a2a;
  font-weight: 500;
}

.text-success {
  color: #2f9e44;
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

.condition-badge.unknown {
  background-color: #f0f0f0;
  color: #666;
}

.status-badge {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
}

.status-badge.on-time {
  background-color: #d3f9d8;
  color: #2f9e44;
}

.status-badge.late {
  background-color: #fff3e0;
  color: #f59f00;
}

.status-badge.pending {
  background-color: #e7f5ff;
  color: #0b7285;
}

.empty-state {
  padding: 60px 20px;
  text-align: center;
  color: #999;
  font-size: 16px;
}

@media (max-width: 768px) {
  .table-header {
    flex-direction: column;
  }

  .search-box {
    width: 100%;
  }

  .returns-table {
    font-size: 12px;
  }

  .returns-table th,
  .returns-table td {
    padding: 10px 8px;
  }
}
</style>
