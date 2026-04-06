<template>
  <div class="users-table-container">
    <!-- Header dengan Search dan Actions -->
    <div class="table-header">
      <div class="search-box">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="🔍 Cari pengguna..."
          class="search-input"
        />
      </div>
      <div class="header-actions">
        <select v-model="roleFilter" class="role-filter">
          <option value="">Semua Role</option>
          <option value="admin">Admin</option>
          <option value="staff">Staff</option>
          <option value="customer">Customer</option>
          <option value="owner">Owner</option>
        </select>
        <button @click="showAddModal = true" class="btn btn-primary">
          ➕ Tambah Pengguna
        </button>
      </div>
    </div>

    <!-- Info Stats -->
    <div class="stats-row">
      <div class="stat-box">
        <span class="label">Total Pengguna:</span>
        <span class="value">{{ totalUsers }}</span>
      </div>
      <div class="stat-box">
        <span class="label">Admin:</span>
        <span class="value admin">{{ adminCount }}</span>
      </div>
      <div class="stat-box">
        <span class="label">Staff:</span>
        <span class="value staff">{{ staffCount }}</span>
      </div>
      <div class="stat-box">
        <span class="label">Customer:</span>
        <span class="value customer">{{ customerCount }}</span>
      </div>
    </div>

    <!-- Tabel Pengguna -->
    <div class="table-wrapper">
      <table class="users-table" v-if="filteredUsers.length > 0">
        <thead>
          <tr>
            <th>No.</th>
            <th>Username</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>Role</th>
            <th>Telepon</th>
            <th>Kota</th>
            <th>Status</th>
            <th>Peminjaman</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(user, index) in filteredUsers" :key="user.id_user">
            <td>{{ index + 1 }}</td>
            <td>
              <strong>{{ user.username }}</strong>
            </td>
            <td>{{ user.nama_lengkap }}</td>
            <td>{{ user.email }}</td>
            <td>
              <span :class="['role-badge', user.role]">
                {{ getRoleLabel(user.role) }}
              </span>
            </td>
            <td>{{ user.phone || '-' }}</td>
            <td>{{ user.kota || '-' }}</td>
            <td>
              <button
                @click="toggleUserActive(user)"
                :class="['status-badge', user.is_active ? 'active' : 'inactive']"
              >
                {{ user.is_active ? '✓ Aktif' : '✗ Nonaktif' }}
              </button>
            </td>
            <td class="text-center">
              <span class="borrow-count">{{ user.borrowing_count || 0 }}</span>
            </td>
            <td>
              <div class="action-buttons">
                <button
                  @click="openEditModal(user)"
                  class="btn-action btn-edit"
                  title="Edit"
                >
                  ✏️
                </button>
                <button
                  @click="openDeleteConfirm(user)"
                  class="btn-action btn-delete"
                  title="Hapus"
                  :disabled="user.role === 'owner'"
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
        <p>📭 Tidak ada pengguna yang ditemukan</p>
      </div>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showAddModal || showEditModal" class="modal-overlay">
      <div class="modal-content">
        <div class="modal-header">
          <h3>{{ showEditModal ? '✏️ Edit Pengguna' : '➕ Tambah Pengguna Baru' }}</h3>
          <button @click="closeModals()" class="btn-close">✕</button>
        </div>

        <form @submit.prevent="saveUser" class="modal-form">
          <div class="form-group">
            <label>Username *</label>
            <input
              v-model="formData.username"
              type="text"
              placeholder="username_unik"
              required
            />
          </div>

          <div class="form-group">
            <label>Nama Lengkap *</label>
            <input
              v-model="formData.nama_lengkap"
              type="text"
              placeholder="Nama Lengkap"
              required
            />
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Email *</label>
              <input
                v-model="formData.email"
                type="email"
                placeholder="email@example.com"
                required
              />
            </div>

            <div class="form-group">
              <label>Telepon</label>
              <input
                v-model="formData.phone"
                type="tel"
                placeholder="081234567890"
              />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Role *</label>
              <select v-model="formData.role" required :disabled="showEditModal && formData.role === 'owner'">
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
                <option value="customer">Customer</option>
                <option value="owner">Owner</option>
              </select>
            </div>

            <div class="form-group">
              <label>Status</label>
              <select v-model="formData.is_active">
                <option :value="false">Nonaktif</option>
                <option :value="true">Aktif</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Kota</label>
              <input
                v-model="formData.kota"
                type="text"
                placeholder="Nama Kota"
              />
            </div>

            <div class="form-group">
              <label>Provinsi</label>
              <input
                v-model="formData.provinsi"
                type="text"
                placeholder="Nama Provinsi"
              />
            </div>
          </div>

          <div class="form-group">
            <label>Alamat</label>
            <textarea
              v-model="formData.alamat"
              placeholder="Alamat lengkap"
              rows="2"
            ></textarea>
          </div>

          <div class="form-group" v-if="!showEditModal || formData.password">
            <label>Password {{ showEditModal ? '(Kosongkan jika tidak ingin mengubah)' : '*' }}</label>
            <input
              v-model="formData.password"
              type="password"
              placeholder="Minimal 8 karakter"
              :required="!showEditModal"
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
            Apakah Anda yakin ingin menghapus pengguna
            <strong>{{ deleteUser?.username }}</strong
            >?
          </p>
          <p class="text-danger">Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <div class="modal-footer">
          <button @click="showDeleteConfirm = false" class="btn btn-secondary">
            Batal
          </button>
          <button @click="deleteUserConfirm()" class="btn btn-danger">
            Hapus Pengguna
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
import { ref, computed, onMounted } from 'vue'
import apiClient from '@/config/api'

const users = ref([])
const searchQuery = ref('')
const roleFilter = ref('')
const showAddModal = ref(false)
const showEditModal = ref(false)
const showDeleteConfirm = ref(false)
const isLoading = ref(false)
const isSubmitting = ref(false)
const formError = ref('')
const deleteUser = ref(null)

const formData = ref({
  username: '',
  nama_lengkap: '',
  email: '',
  phone: '',
  password: '',
  role: 'customer',
  alamat: '',
  kota: '',
  provinsi: '',
  kode_pos: '',
  is_active: true,
})

// Computed Properties
const filteredUsers = computed(() => {
  return users.value.filter((user) => {
    const matchSearch =
      user.username.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      user.nama_lengkap.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      user.email.toLowerCase().includes(searchQuery.value.toLowerCase())

    const matchRole = roleFilter.value === '' || user.role === roleFilter.value

    return matchSearch && matchRole
  })
})

const totalUsers = computed(() => users.value.length)

const adminCount = computed(
  () => users.value.filter((u) => u.role === 'admin').length
)

const staffCount = computed(
  () => users.value.filter((u) => u.role === 'staff').length
)

const customerCount = computed(
  () => users.value.filter((u) => u.role === 'customer').length
)

// Methods
const loadUsers = async () => {
  try {
    isLoading.value = true
    const response = await apiClient.get('/users')
    if (response.data.success) {
      users.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading users:', error)
    alert('Gagal memuat data pengguna')
  } finally {
    isLoading.value = false
  }
}

const getRoleLabel = (role) => {
  const labels = {
    admin: 'Administrator',
    staff: 'Staff/Petugas',
    customer: 'Customer/Peminjam',
    owner: 'Owner',
  }
  return labels[role] || role
}

const openEditModal = (user) => {
  formData.value = { ...user, password: '' }
  showEditModal.value = true
  formError.value = ''
}

const closeModals = () => {
  showAddModal.value = false
  showEditModal.value = false
  formData.value = {
    username: '',
    nama_lengkap: '',
    email: '',
    phone: '',
    password: '',
    role: 'customer',
    alamat: '',
    kota: '',
    provinsi: '',
    kode_pos: '',
    is_active: true,
  }
  formError.value = ''
}

const saveUser = async () => {
  try {
    isSubmitting.value = true
    formError.value = ''

    const payload = { ...formData.value }
    if (showEditModal.value && !payload.password) {
      delete payload.password
    }

    if (showEditModal.value) {
      const response = await apiClient.put(`/users/${formData.value.id_user}`, payload)
      if (response.data.success) {
        const index = users.value.findIndex(
          (u) => u.id_user === formData.value.id_user
        )
        if (index > -1) {
          users.value[index] = response.data.data
        }
        closeModals()
        alert('Pengguna berhasil diperbarui!')
      }
    } else {
      const response = await apiClient.post('/users', payload)
      if (response.data.success) {
        users.value.push(response.data.data)
        closeModals()
        alert('Pengguna berhasil ditambahkan!')
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

const openDeleteConfirm = (user) => {
  deleteUser.value = user
  showDeleteConfirm.value = true
}

const deleteUserConfirm = async () => {
  try {
    isSubmitting.value = true
    const response = await apiClient.delete(`/users/${deleteUser.value.id_user}`)
    if (response.data.success) {
      users.value = users.value.filter(
        (u) => u.id_user !== deleteUser.value.id_user
      )
      showDeleteConfirm.value = false
      alert('Pengguna berhasil dihapus!')
    }
  } catch (error) {
    alert(error.response?.data?.message || 'Gagal menghapus pengguna')
  } finally {
    isSubmitting.value = false
  }
}

const toggleUserActive = async (user) => {
  try {
    const response = await apiClient.post(`/users/${user.id_user}/toggle-active`)
    if (response.data.success) {
      const index = users.value.findIndex((u) => u.id_user === user.id_user)
      if (index > -1) {
        users.value[index] = response.data.data
      }
    }
  } catch (error) {
    alert('Gagal mengubah status pengguna')
  }
}

// Lifecycle
onMounted(() => {
  loadUsers()
})
</script>

<style scoped>
.users-table-container {
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

.role-filter {
  padding: 10px 14px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 14px;
  cursor: pointer;
  transition: border-color 0.3s ease;
}

.role-filter:focus {
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

.stat-box .value.admin {
  color: #c92a2a;
}

.stat-box .value.staff {
  color: #f59f00;
}

.stat-box .value.customer {
  color: #2f9e44;
}

/* Table Wrapper */
.table-wrapper {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  overflow: hidden;
}

.users-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

.users-table thead {
  background: linear-gradient(135deg, #0b7285 0%, #0c6b7d 100%);
  color: white;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.users-table th {
  padding: 16px 12px;
  text-align: left;
  border-bottom: 2px solid #0b7285;
}

.users-table tbody tr {
  border-bottom: 1px solid #f0f0f0;
  transition: background-color 0.2s ease;
}

.users-table tbody tr:hover {
  background-color: #f9f9f9;
}

.users-table td {
  padding: 14px 12px;
  vertical-align: middle;
}

.users-table td strong {
  color: #1a1a2e;
}

/* Role and Status Badges */
.role-badge {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.role-badge.admin {
  background-color: #ffe3e3;
  color: #c92a2a;
}

.role-badge.staff {
  background-color: #fff3e0;
  color: #f59f00;
}

.role-badge.customer {
  background-color: #e3f2fd;
  color: #1565c0;
}

.role-badge.owner {
  background-color: #e8f5e9;
  color: #2e7d32;
}

.status-badge {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 6px;
  border: none;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.status-badge.active {
  background-color: #d3f9d8;
  color: #2f9e44;
}

.status-badge.inactive {
  background-color: #ffe3e3;
  color: #c92a2a;
}

.status-badge:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Borrow Count */
.borrow-count {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  background-color: #e7f5ff;
  color: #0b7285;
  border-radius: 50%;
  font-weight: 600;
  font-size: 14px;
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

  .users-table {
    font-size: 12px;
  }

  .users-table th,
  .users-table td {
    padding: 10px 8px;
  }

  .action-buttons {
    flex-direction: column;
  }

  .btn-action {
    width: 100%;
  }
}
</style>
