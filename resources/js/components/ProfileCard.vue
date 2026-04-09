<template>
  <div class="profile-container">
    <div class="profile-header">
      <div class="profile-avatar" :style="{ background: avatarColor }">
        {{ userInitial }}
      </div>
      <div class="profile-info">
        <h2>{{ userInfo?.nama_lengkap || 'User' }}</h2>
        <p class="role">{{ roleLabel }}</p>
        <p class="email">{{ userInfo?.email }}</p>
      </div>
    </div>

    <div class="profile-details">
      <div class="detail-section">
        <h3>Informasi Pribadi</h3>
        <div class="info-row">
          <span class="label">Username:</span>
          <span class="value">{{ userInfo?.username }}</span>
        </div>
        <div class="info-row">
          <span class="label">Nama Lengkap:</span>
          <span class="value">{{ userInfo?.nama_lengkap }}</span>
        </div>
        <div class="info-row">
          <span class="label">Email:</span>
          <span class="value">{{ userInfo?.email }}</span>
        </div>
        <div class="info-row">
          <span class="label">Telepon:</span>
          <span class="value">{{ userInfo?.phone || '-' }}</span>
        </div>
      </div>

      <div class="detail-section">
        <h3>Alamat</h3>
        <div class="info-row">
          <span class="label">Alamat:</span>
          <span class="value">{{ userInfo?.alamat || '-' }}</span>
        </div>
        <div class="info-row">
          <span class="label">Kota:</span>
          <span class="value">{{ userInfo?.kota || '-' }}</span>
        </div>
        <div class="info-row">
          <span class="label">Provinsi:</span>
          <span class="value">{{ userInfo?.provinsi || '-' }}</span>
        </div>
      </div>

      <div class="detail-section">
        <h3>Statistik</h3>
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-number">{{ borrowingStats?.total || 0 }}</div>
            <div class="stat-label">Total Peminjaman</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ borrowingStats?.active || 0 }}</div>
            <div class="stat-label">Peminjaman Aktif</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ borrowingStats?.overdue || 0 }}</div>
            <div class="stat-label">Terlambat</div>
          </div>
        </div>
      </div>

      <div class="action-buttons">
        <button @click="editProfile" class="btn btn-primary">✏️ Edit Profil</button>
        <button @click="logout" class="btn btn-secondary">🚪 Logout</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import apiClient from '@/config/api'

const userInfo = ref(null)
const borrowingStats = ref({ total: 0, active: 0, overdue: 0 })

const roleLabel = computed(() => {
  const roles = {
    admin: 'Administrator',
    staff: 'Staff / Petugas',
    customer: 'Pelanggan / Peminjam',
    owner: 'Pemilik'
  }
  return roles[userInfo.value?.role] || userInfo.value?.role || 'User'
})

const avatarColor = computed(() => {
  const colors = {
    admin: '#c92a2a',
    staff: '#0b7285',
    customer: '#2f9e44',
    owner: '#f59f00'
  }
  return colors[userInfo.value?.role] || '#999'
})

const userInitial = computed(() => {
  return userInfo.value?.nama_lengkap?.charAt(0).toUpperCase() || 'U'
})

const loadUserProfile = async () => {
  try {
    const userStr = localStorage.getItem('user')
    if (userStr) {
      userInfo.value = JSON.parse(userStr)
      
      // Load borrowing stats
      if (userInfo.value?.id_user) {
        const response = await apiClient.get(`/borrowings?user_id=${userInfo.value.id_user}`)
        if (response.data.success) {
          const borrowings = response.data.data
          borrowingStats.value = {
            total: borrowings.length,
            active: borrowings.filter(b => b.status === 'picked_up').length,
            overdue: borrowings.filter(b => {
              const now = new Date()
              const dueDate = new Date(b.tanggal_rencana_kembali || b.planned_return_date)
              return b.status === 'picked_up' && now > dueDate
            }).length
          }
        }
      }
    }
  } catch (error) {
    console.error('Error loading profile:', error)
  }
}

const editProfile = () => {
  alert('Fitur edit profil sedang dalam pengembangan')
}

const logout = () => {
  localStorage.removeItem('user')
  localStorage.removeItem('userRole')
  window.location.href = '/login'
}

onMounted(() => {
  loadUserProfile()
})
</script>

<style scoped>
.profile-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
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

.profile-header {
  background: linear-gradient(135deg, #0b7285 0%, #0c6b7d 100%);
  color: white;
  padding: 30px;
  border-radius: 12px;
  display: flex;
  gap: 20px;
  align-items: flex-start;
}

.profile-avatar {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2.5rem;
  font-weight: bold;
  color: white;
  flex-shrink: 0;
}

.profile-info h2 {
  margin: 0 0 5px 0;
  font-size: 1.5rem;
}

.profile-info .role {
  margin: 0 0 5px 0;
  font-size: 0.9rem;
  opacity: 0.8;
}

.profile-info .email {
  margin: 0;
  font-size: 0.85rem;
  opacity: 0.8;
}

.profile-details {
  background: white;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.detail-section {
  margin-bottom: 30px;
}

.detail-section:last-of-type {
  margin-bottom: 0;
}

.detail-section h3 {
  margin: 0 0 15px 0;
  color: #1a1a2e;
  font-size: 1.1rem;
  border-bottom: 2px solid #f0f0f0;
  padding-bottom: 10px;
}

.info-row {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  border-bottom: 1px solid #f5f5f5;
  font-size: 0.95rem;
}

.info-row:last-child {
  border-bottom: none;
}

.info-row .label {
  font-weight: 600;
  color: #666;
  min-width: 150px;
}

.info-row .value {
  color: #1a1a2e;
  word-break: break-word;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  gap: 15px;
}

.stat-card {
  background: linear-gradient(135deg, #0b7285 0%, #0c6b7d 100%);
  color: white;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
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

.action-buttons {
  display: flex;
  gap: 12px;
  margin-top: 20px;
  padding-top: 20px;
  border-top: 2px solid #f0f0f0;
}

.btn {
  flex: 1;
  padding: 12px 20px;
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

.btn-primary:hover {
  background-color: #0a5f6d;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(11, 114, 133, 0.3);
}

.btn-secondary {
  background-color: #e0e0e0;
  color: #333;
}

.btn-secondary:hover {
  background-color: #d0d0d0;
}

@media (max-width: 768px) {
  .profile-header {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .info-row {
    flex-direction: column;
  }

  .info-row .label {
    min-width: auto;
    margin-bottom: 5px;
  }

  .action-buttons {
    flex-direction: column;
  }

  .btn {
    width: 100%;
  }
}
</style>
