<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Trash2, CheckCircle, AlertCircle, Info, Clock } from 'lucide-vue-next'

const notifications = ref<any[]>([])
const selectedFilter = ref('all')

const notificationIcons: Record<string, any> = {
  success: CheckCircle,
  warning: AlertCircle,
  info: Info
}

const getNotificationIcon = (type: string) => {
  return notificationIcons[type] || Info
}

const formatDate = (date: string) => {
  const d = new Date(date)
  const now = new Date()
  const diffMs = now.getTime() - d.getTime()
  const diffMins = Math.floor(diffMs / 60000)
  const diffHours = Math.floor(diffMs / 3600000)
  const diffDays = Math.floor(diffMs / 86400000)

  if (diffMins < 1) return 'Baru saja'
  if (diffMins < 60) return `${diffMins} menit lalu`
  if (diffHours < 24) return `${diffHours} jam lalu`
  if (diffDays < 7) return `${diffDays} hari lalu`
  return d.toLocaleDateString('id-ID')
}

const deleteNotification = (id: number) => {
  notifications.value = notifications.value.filter(n => n.id !== id)
}

const markAsRead = (id: number) => {
  const notification = notifications.value.find(n => n.id === id)
  if (notification) {
    notification.read = true
  }
}

const filteredNotifications = computed(() => {
  if (selectedFilter.value === 'all') return notifications.value
  if (selectedFilter.value === 'unread') return notifications.value.filter(n => !n.read)
  return notifications.value.filter(n => n.type === selectedFilter.value)
})

onMounted(() => {
  notifications.value = [
    {
      id: 1,
      type: 'success',
      title: 'Alat Siap Diambil',
      description: 'Laptop Dell XPS Anda siap untuk diambil di gudang',
      date: new Date(Date.now() - 1000 * 60 * 10).toISOString(),
      read: false
    },
    {
      id: 2,
      type: 'warning',
      title: 'Pengembalian Tertunda',
      description: 'Kamera Canon EOS harus dikembalikan hari ini',
      date: new Date(Date.now() - 1000 * 60 * 60).toISOString(),
      read: false
    },
    {
      id: 3,
      type: 'info',
      title: 'Update Sistem',
      description: 'Sistem akan di-update pada tanggal 20 April pukul 02:00 WIB',
      date: new Date(Date.now() - 1000 * 60 * 60 * 3).toISOString(),
      read: true
    },
    {
      id: 4,
      type: 'success',
      title: 'Peminjaman Disetujui',
      description: 'Permintaan peminjaman Proyektor telah disetujui',
      date: new Date(Date.now() - 1000 * 60 * 60 * 24).toISOString(),
      read: true
    },
    {
      id: 5,
      type: 'info',
      title: 'Maintenance Rutin',
      description: 'Perawatan rutin peralatan telah selesai',
      date: new Date(Date.now() - 1000 * 60 * 60 * 48).toISOString(),
      read: true
    }
  ]
})
</script>

<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div>
      <h1 class="text-3xl font-bold">Notifikasi</h1>
      <p class="text-muted-foreground mt-2">Kelola notifikasi Anda</p>
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
        Semua ({{ notifications.length }})
      </button>
      <button
        @click="selectedFilter = 'unread'"
        :class="[
          'px-4 py-2 rounded-lg whitespace-nowrap transition-colors',
          selectedFilter === 'unread'
            ? 'bg-primary text-primary-foreground'
            : 'hover:bg-accent'
        ]"
      >
        Belum Dibaca ({{ notifications.filter(n => !n.read).length }})
      </button>
      <button
        @click="selectedFilter = 'success'"
        :class="[
          'px-4 py-2 rounded-lg whitespace-nowrap transition-colors',
          selectedFilter === 'success'
            ? 'bg-primary text-primary-foreground'
            : 'hover:bg-accent'
        ]"
      >
        Berhasil
      </button>
      <button
        @click="selectedFilter = 'warning'"
        :class="[
          'px-4 py-2 rounded-lg whitespace-nowrap transition-colors',
          selectedFilter === 'warning'
            ? 'bg-primary text-primary-foreground'
            : 'hover:bg-accent'
        ]"
      >
        Peringatan
      </button>
    </div>

    <!-- Notifications List -->
    <div class="space-y-3">
      <div
        v-for="notification in filteredNotifications"
        :key="notification.id"
        :class="[
          'rounded-lg p-4 border transition-colors cursor-pointer',
          notification.read
            ? 'bg-background hover:bg-accent/50'
            : 'bg-accent/50 border-primary/50 hover:bg-accent'
        ]"
        @click="markAsRead(notification.id)"
      >
        <div class="flex items-start gap-4">
          <div :class="[
            'p-2 rounded-lg flex-shrink-0',
            notification.type === 'success' ? 'bg-green-100 text-green-600' :
            notification.type === 'warning' ? 'bg-yellow-100 text-yellow-600' :
            'bg-blue-100 text-blue-600'
          ]">
            <component :is="getNotificationIcon(notification.type)" :size="20" />
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-4">
              <div>
                <h3 :class="['font-semibold text-sm', notification.read ? 'text-foreground' : 'text-foreground']">
                  {{ notification.title }}
                </h3>
                <p class="text-sm text-muted-foreground mt-1">{{ notification.description }}</p>
              </div>
              <button
                @click.stop="deleteNotification(notification.id)"
                class="p-1 hover:bg-destructive/20 rounded transition-colors flex-shrink-0"
              >
                <Trash2 :size="16" class="text-destructive" />
              </button>
            </div>
            <div class="flex items-center gap-2 mt-3">
              <Clock :size="14" class="text-muted-foreground" />
              <span class="text-xs text-muted-foreground">{{ formatDate(notification.date) }}</span>
              <span v-if="!notification.read" class="ml-auto inline-block w-2 h-2 rounded-full bg-primary"></span>
            </div>
          </div>
        </div>
      </div>

      <div v-if="filteredNotifications.length === 0" class="text-center py-12">
        <p class="text-muted-foreground">Tidak ada notifikasi</p>
      </div>
    </div>
  </div>
</template>
