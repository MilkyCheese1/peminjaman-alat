<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui'
import { Settings, Lock } from 'lucide-vue-next'
import { useToast } from '@/composables/useToast'
import CustomerService from '@/components/CustomerService.vue'
import StaffServices from '@/components/StaffServices.vue'

const userInfo = ref<any>(null)
const { success: showSuccess, error: showError } = useToast()
const settings = ref({
  appName: 'Peminjaman Alat',
  appVersion: '1.0.0',
  maintenanceMode: false,
  notificationsEnabled: true,
  debugMode: false
})

const securitySettings = ref({
  currentPassword: '',
  newPassword: '',
  confirmPassword: ''
})

const isAdminOrOwner = computed(() => {
  return userInfo.value?.role === 'admin' || userInfo.value?.role === 'owner'
})

const handleSave = () => {
  showSuccess('Pengaturan disimpan!')
}

const handleReset = () => {
  showSuccess('Pengaturan direset ke default!')
}

const handleChangePassword = () => {
  if (securitySettings.value.newPassword !== securitySettings.value.confirmPassword) {
    showError('Password tidak cocok!')
    return
  }
  showSuccess('Password berhasil diubah!')
  securitySettings.value = {
    currentPassword: '',
    newPassword: '',
    confirmPassword: ''
  }
}

onMounted(() => {
  const userStr = localStorage.getItem('user')
  if (userStr) {
    userInfo.value = JSON.parse(userStr)
  }
})
</script>

<template>
  <div>
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-foreground flex items-center gap-2">
        <Settings :size="32" />
        {{ isAdminOrOwner ? 'Pengaturan Sistem' : 'Keamanan Akun' }}
      </h1>
      <p class="text-muted-foreground mt-1">{{ isAdminOrOwner ? 'Kelola konfigurasi sistem dan preferensi' : 'Kelola keamanan akun Anda' }}</p>
    </div>

    <!-- Admin/Owner Settings -->
    <div v-if="isAdminOrOwner" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- General Settings -->
      <Card>
        <CardHeader>
          <CardTitle>Pengaturan Umum</CardTitle>
        </CardHeader>
        <CardContent class="space-y-6">
          <div>
            <label class="block text-sm font-medium mb-2">Nama Aplikasi</label>
            <input
              v-model="settings.appName"
              type="text"
              class="w-full px-3 py-2 border border-input rounded-md bg-background focus:outline-none focus:ring-2 focus:ring-primary"
              placeholder="Masukkan nama aplikasi"
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Versi Aplikasi</label>
            <input
              v-model="settings.appVersion"
              type="text"
              disabled
              class="w-full px-3 py-2 border border-input rounded-md bg-muted text-muted-foreground cursor-not-allowed"
            />
          </div>

          <div class="flex items-center justify-between">
            <label class="block text-sm font-medium">Mode Pemeliharaan</label>
            <input
              v-model="settings.maintenanceMode"
              type="checkbox"
              class="w-4 h-4 rounded border-input focus:ring-primary"
            />
          </div>

          <button
            @click="handleSave"
            class="w-full px-4 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90 transition-colors font-medium"
          >
            Simpan Pengaturan
          </button>
        </CardContent>
      </Card>

      <!-- Feature Settings -->
      <Card>
        <CardHeader>
          <CardTitle>Pengaturan Fitur</CardTitle>
        </CardHeader>
        <CardContent class="space-y-6">
          <div class="flex items-center justify-between">
            <label class="block text-sm font-medium">Notifikasi</label>
            <input
              v-model="settings.notificationsEnabled"
              type="checkbox"
              class="w-4 h-4 rounded border-input focus:ring-primary"
            />
          </div>

          <div class="flex items-center justify-between">
            <label class="block text-sm font-medium">Mode Debug</label>
            <input
              v-model="settings.debugMode"
              type="checkbox"
              class="w-4 h-4 rounded border-input focus:ring-primary"
            />
          </div>

          <button
            @click="handleReset"
            class="w-full px-4 py-2 bg-secondary text-secondary-foreground rounded-md hover:bg-secondary/80 transition-colors font-medium"
          >
            Reset ke Default
          </button>
        </CardContent>
      </Card>

      <!-- System Info -->
      <Card class="lg:col-span-2">
        <CardHeader>
          <CardTitle>Informasi Sistem</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-background rounded-lg">
              <p class="text-sm text-muted-foreground">Database</p>
              <p class="font-semibold">MySQL</p>
            </div>
            <div class="p-4 bg-background rounded-lg">
              <p class="text-sm text-muted-foreground">Framework</p>
              <p class="font-semibold">Laravel 10</p>
            </div>
            <div class="p-4 bg-background rounded-lg">
              <p class="text-sm text-muted-foreground">Frontend</p>
              <p class="font-semibold">Vue.js 3</p>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Staff/Customer Security Settings -->
    <div v-else class="space-y-6">
      <!-- Security Card -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center gap-2">
              <Lock :size="24" />
              Keamanan Akun
            </CardTitle>
          </CardHeader>
          <CardContent class="space-y-6">
            <!-- Current Password -->
            <div>
              <label class="block text-sm font-medium mb-2">Password Saat Ini</label>
              <input
                v-model="securitySettings.currentPassword"
                type="password"
                class="w-full px-4 py-2 border border-input rounded-lg bg-background focus:outline-none focus:ring-2 focus:ring-primary"
                placeholder="Masukkan password saat ini"
              />
            </div>

            <!-- New Password -->
            <div>
              <label class="block text-sm font-medium mb-2">Password Baru</label>
              <input
                v-model="securitySettings.newPassword"
                type="password"
                class="w-full px-4 py-2 border border-input rounded-lg bg-background focus:outline-none focus:ring-2 focus:ring-primary"
                placeholder="Masukkan password baru"
              />
            </div>

            <!-- Confirm Password -->
            <div>
              <label class="block text-sm font-medium mb-2">Konfirmasi Password</label>
              <input
                v-model="securitySettings.confirmPassword"
                type="password"
                class="w-full px-4 py-2 border border-input rounded-lg bg-background focus:outline-none focus:ring-2 focus:ring-primary"
                placeholder="Konfirmasi password baru"
              />
            </div>

            <!-- Save Button -->
            <button
              @click="handleChangePassword"
              class="w-full px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors font-medium"
            >
              Ubah Password
            </button>
          </CardContent>
        </Card>

        <!-- Service Component -->
        <div>
          <CustomerService v-if="userInfo?.role === 'customer'" />
          <StaffServices v-else-if="userInfo?.role === 'staff'" />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* No additional styles needed - using Tailwind CSS */
</style>
