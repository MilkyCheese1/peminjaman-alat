<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui'
import axios from 'axios'

const router = useRouter()
const userInfo = ref<any>(null)
const loading = ref(false)
const statistics = ref<any>(null)
const error = ref('')

type RoleType = 'owner' | 'admin' | 'staff' | 'customer'

const roleColors: Record<RoleType, string> = {
  owner: '#059669',
  admin: '#2563eb',
  staff: '#f59e0b',
  customer: '#8b5cf6'
}

const roleLabels: Record<RoleType, string> = {
  owner: 'Pemilik',
  admin: 'Admin',
  staff: 'Staff',
  customer: 'Pelanggan'
}

const currentRole = computed(() => (userInfo.value?.role || 'customer') as RoleType)
const roleColor = computed(() => roleColors[currentRole.value] || '#6366f1')
const roleLabel = computed(() => roleLabels[currentRole.value] || 'User')

// Check auth & load user data
onMounted(async () => {
  const userStr = localStorage.getItem('user')
  if (!userStr) {
    router.push('/login')
    return
  }
  
  userInfo.value = JSON.parse(userStr)
  
  // Load statistics
  await loadStatistics()
})

const loadStatistics = async () => {
  try {
    loading.value = true
    const response = await axios.get('/api/statistics/dashboard')
    statistics.value = response.data
  } catch (err: any) {
    error.value = err.message
    console.error('Failed to load statistics:', err)
  } finally {
    loading.value = false
  }
}

const handleLogout = () => {
  localStorage.removeItem('user')
  localStorage.removeItem('token')
  router.push('/login')
}
</script>

<template>
  <div v-if="!userInfo" class="text-center py-8">
    <p>Loading...</p>
  </div>

  <template v-else>
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-foreground">Dashboard</h1>
      <p class="text-muted-foreground mt-1">
        Selamat datang, <span class="font-semibold">{{ userInfo.name }}</span>
        <span class="ml-2 px-2 py-1 rounded text-sm text-white" :style="{ backgroundColor: roleColor }">
          {{ roleLabel }}
        </span>
      </p>
    </div>

    <!-- Error Message -->
    <div v-if="error" class="bg-destructive/10 border border-destructive text-destructive px-4 py-3 rounded-lg mb-6">
      {{ error }}
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <Card v-if="statistics">
        <CardHeader class="pb-2">
          <CardTitle class="text-sm font-medium text-muted-foreground">Total Alat</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="text-2xl font-bold">{{ statistics.total_equipment || 0 }}</div>
          <p class="text-xs text-muted-foreground mt-1">Alat tersedia</p>
        </CardContent>
      </Card>

      <Card v-if="statistics">
        <CardHeader class="pb-2">
          <CardTitle class="text-sm font-medium text-muted-foreground">Peminjaman Aktif</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="text-2xl font-bold">{{ statistics.active_borrowings || 0 }}</div>
          <p class="text-xs text-muted-foreground mt-1">Sedang dipinjam</p>
        </CardContent>
      </Card>

      <Card v-if="statistics">
        <CardHeader class="pb-2">
          <CardTitle class="text-sm font-medium text-muted-foreground">Pengguna</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="text-2xl font-bold">{{ statistics.total_active_users || 0 }}</div>
          <p class="text-xs text-muted-foreground mt-1">Pengguna aktif</p>
        </CardContent>
      </Card>

      <Card v-if="statistics">
        <CardHeader class="pb-2">
          <CardTitle class="text-sm font-medium text-muted-foreground">Kategori</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="text-2xl font-bold">{{ statistics.total_categories || 0 }}</div>
          <p class="text-xs text-muted-foreground mt-1">Jenis alat</p>
        </CardContent>
      </Card>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Recent Activity / Main Content -->
      <div class="lg:col-span-2">
        <Card>
          <CardHeader>
            <CardTitle>Aktivitas Terkini</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="space-y-4">
              <div v-if="loading" class="text-center py-8">
                <p class="text-muted-foreground">Memuat data...</p>
              </div>
              <template v-else>
                <div class="text-center py-8 text-muted-foreground">
                  <p>Tidak ada aktivitas terkini</p>
                </div>
              </template>
            </div>
          </CardContent>
        </Card>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- User Info Card -->
        <Card>
          <CardHeader>
            <CardTitle>Informasi Pengguna</CardTitle>
          </CardHeader>
          <CardContent class="space-y-3">
            <div>
              <p class="text-sm text-muted-foreground">Nama</p>
              <p class="font-medium">{{ userInfo.name }}</p>
            </div>
            <div>
              <p class="text-sm text-muted-foreground">Email</p>
              <p class="font-medium text-sm break-all">{{ userInfo.email }}</p>
            </div>
            <div>
              <p class="text-sm text-muted-foreground">Role</p>
              <p class="font-medium" :style="{ color: roleColor }">{{ roleLabel }}</p>
            </div>
            <button
              @click="handleLogout"
              class="w-full mt-4 px-4 py-2 bg-destructive text-destructive-foreground rounded-md hover:bg-destructive/90 transition-colors text-sm font-medium"
            >
              Logout
            </button>
          </CardContent>
        </Card>

        <!-- Quick Links -->
        <Card>
          <CardHeader>
            <CardTitle>Menu Cepat</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="space-y-2">
              <router-link
                to="/dashboard"
                class="block px-3 py-2 rounded-md hover:bg-accent transition-colors text-sm"
              >
                📊 Dashboard
              </router-link>
              <router-link
                to="/terms"
                class="block px-3 py-2 rounded-md hover:bg-accent transition-colors text-sm"
              >
                📋 Syarat & Ketentuan
              </router-link>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </template>
</template>

<style scoped>
/* No additional styles needed - using Tailwind CSS */
</style>
