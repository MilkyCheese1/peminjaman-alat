<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Clock, Package, FileText, Settings, CheckCircle, AlertCircle } from 'lucide-vue-next'

const activities = ref<any[]>([])
const selectedFilter = ref('all')

const activityIcons: Record<string, any> = {
  login: Clock,
  equipment: Package,
  report: FileText,
  settings: Settings
}

const getActivityIcon = (type: string) => {
  return activityIcons[type] || Clock
}

const getActivityColor = (type: string) => {
  const colors: Record<string, string> = {
    success: 'text-green-600',
    warning: 'text-yellow-600',
    error: 'text-red-600',
    info: 'text-blue-600'
  }
  return colors[type] || 'text-gray-600'
}

const formatDate = (date: string) => {
  const d = new Date(date)
  return d.toLocaleDateString('id-ID', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => {
  // Sample activity data
  activities.value = [
    {
      id: 1,
      type: 'success',
      icon: 'login',
      title: 'Login Berhasil',
      description: 'Anda berhasil login ke sistem',
      date: new Date(Date.now() - 1000 * 60 * 5).toISOString(),
      ip: '192.168.1.1'
    },
    {
      id: 2,
      type: 'success',
      icon: 'equipment',
      title: 'Peminjaman Alat',
      description: 'Anda meminjam Laptop Dell XPS',
      date: new Date(Date.now() - 1000 * 60 * 30).toISOString(),
      ip: '192.168.1.1'
    },
    {
      id: 3,
      type: 'success',
      icon: 'equipment',
      title: 'Pengembalian Alat',
      description: 'Anda mengembalikan Kamera Canon EOS',
      date: new Date(Date.now() - 1000 * 60 * 60 * 2).toISOString(),
      ip: '192.168.1.1'
    },
    {
      id: 4,
      type: 'success',
      icon: 'report',
      title: 'Download Laporan',
      description: 'Anda mendownload laporan peminjaman',
      date: new Date(Date.now() - 1000 * 60 * 60 * 24).toISOString(),
      ip: '192.168.1.1'
    },
    {
      id: 5,
      type: 'warning',
      icon: 'equipment',
      title: 'Pengembalian Tertunda',
      description: 'Alat Proyektor belum dikembalikan',
      date: new Date(Date.now() - 1000 * 60 * 60 * 48).toISOString(),
      ip: '192.168.1.1'
    }
  ]
})

const filteredActivities = computed(() => {
  if (selectedFilter.value === 'all') return activities.value
  return activities.value.filter(a => a.type === selectedFilter.value)
})

import { computed } from 'vue'
</script>

<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div>
      <h1 class="text-3xl font-bold">Riwayat Aktivitas</h1>
      <p class="text-muted-foreground mt-2">Lihat semua aktivitas akun Anda</p>
    </div>

    <!-- Filter -->
    <div class="flex gap-2 pb-4 border-b overflow-x-auto">
      <button
        @click="selectedFilter = 'all'"
        :class="[
          'px-4 py-2 rounded-lg whitespace-nowrap transition-colors',
          selectedFilter === 'all'
            ? 'bg-primary text-primary-foreground'
            : 'hover:bg-accent'
        ]"
      >
        Semua
      </button>
      <button
        @click="selectedFilter = 'success'"
        :class="[
          'px-4 py-2 rounded-lg whitespace-nowrap transition-colors flex items-center gap-2',
          selectedFilter === 'success'
            ? 'bg-primary text-primary-foreground'
            : 'hover:bg-accent'
        ]"
      >
        <CheckCircle :size="16" />
        Berhasil
      </button>
      <button
        @click="selectedFilter = 'warning'"
        :class="[
          'px-4 py-2 rounded-lg whitespace-nowrap transition-colors flex items-center gap-2',
          selectedFilter === 'warning'
            ? 'bg-primary text-primary-foreground'
            : 'hover:bg-accent'
        ]"
      >
        <AlertCircle :size="16" />
        Peringatan
      </button>
    </div>

    <!-- Activity List -->
    <div class="space-y-3">
      <div v-for="activity in filteredActivities" :key="activity.id" class="bg-card border rounded-lg p-4 hover:border-primary/50 transition-colors">
        <div class="flex items-start gap-4">
          <div :class="['p-2 rounded-lg bg-accent', getActivityColor(activity.type)]">
            <component :is="getActivityIcon(activity.icon)" :size="20" />
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-4">
              <div>
                <h3 class="font-semibold text-sm">{{ activity.title }}</h3>
                <p class="text-sm text-muted-foreground mt-1">{{ activity.description }}</p>
              </div>
              <span :class="['text-xs px-2 py-1 rounded-full font-medium whitespace-nowrap', 
                activity.type === 'success' ? 'bg-green-100 text-green-700' :
                activity.type === 'warning' ? 'bg-yellow-100 text-yellow-700' :
                'bg-red-100 text-red-700'
              ]">
                {{ activity.type === 'success' ? 'Berhasil' : activity.type === 'warning' ? 'Peringatan' : 'Error' }}
              </span>
            </div>
            <div class="flex items-center gap-4 mt-3 text-xs text-muted-foreground">
              <span>{{ formatDate(activity.date) }}</span>
              <span>IP: {{ activity.ip }}</span>
            </div>
          </div>
        </div>
      </div>

      <div v-if="filteredActivities.length === 0" class="text-center py-12">
        <p class="text-muted-foreground">Tidak ada aktivitas yang ditemukan</p>
      </div>
    </div>
  </div>
</template>
