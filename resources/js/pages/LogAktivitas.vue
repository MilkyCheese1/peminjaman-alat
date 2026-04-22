<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <SidebarAdmin />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-6">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Log Aktivitas</h1>
          <p class="text-slate-700 dark:text-slate-300">Riwayat aktivitas admin (audit trail).</p>
        </div>

        <div class="app-card app-card--cyan p-6">
          <div v-if="loading" class="rounded-2xl bg-slate-100 dark:bg-slate-800/50 p-4 text-sm text-slate-700 dark:text-slate-300">
            Memuat log aktivitas...
          </div>
          <div v-else-if="items.length" class="space-y-3">
            <div v-for="item in items" :key="item.id" class="rounded-2xl bg-slate-100 dark:bg-slate-800/50 p-4 text-sm text-slate-700 dark:text-slate-300">
              <p class="font-semibold text-slate-900 dark:text-white">{{ item.deskripsi }}</p>
              <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ item.aksi }} • {{ item.entitas }} • {{ formatDateTime(item.createdAt) }}</p>
            </div>
          </div>
          <div v-else class="rounded-2xl bg-slate-100 dark:bg-slate-800/50 p-4 text-sm text-slate-700 dark:text-slate-300">
            Belum ada data log aktivitas.
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import SidebarAdmin from '../components/layout/SidebarAdmin.vue'
import Navbar from '../components/layout/Navbar.vue'
import { apiRequest } from '../lib/api'

export default {
  name: 'LogAktivitas',
  components: {
    SidebarAdmin,
    Navbar,
  },
  data() {
    return {
      loading: false,
      items: [],
    }
  },
  async created() {
    await this.loadData()
  },
  methods: {
    async loadData() {
      this.loading = true
      try {
        const data = await apiRequest('/api/activity-logs')
        this.items = Array.isArray(data) ? data : []
      } catch (error) {
        this.items = []
      } finally {
        this.loading = false
      }
    },
    formatDateTime(value) {
      if (!value) return '-'
      try {
        return new Intl.DateTimeFormat('id-ID', { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(value.replace(' ', 'T')))
      } catch (error) {
        return value
      }
    },
  },
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
