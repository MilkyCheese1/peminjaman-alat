<script setup lang="ts">
import { ref } from 'vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui'
import { Settings } from 'lucide-vue-next'

const settings = ref({
  appName: 'Peminjaman Alat',
  appVersion: '1.0.0',
  maintenanceMode: false,
  notificationsEnabled: true,
  debugMode: false
})

const handleSave = () => {
  alert('Pengaturan disimpan!')
}

const handleReset = () => {
  alert('Pengaturan direset ke default!')
}
</script>

<template>
  <div>
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-foreground flex items-center gap-2">
        <Settings :size="32" />
        Pengaturan Sistem
      </h1>
      <p class="text-muted-foreground mt-1">Kelola konfigurasi sistem dan preferensi</p>
    </div>

    <!-- Settings Form -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
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
  </div>
</template>

<style scoped>
/* No additional styles needed - using Tailwind CSS */
</style>
