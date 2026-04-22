<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <SidebarAdmin />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-6">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Statistik Sistem</h1>
          <p class="text-slate-700 dark:text-slate-300">Ringkasan data sistem peminjaman alat</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div v-for="card in cards" :key="card.label" :class="card.cardClass">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-600 dark:text-slate-400 text-sm">{{ card.label }}</p>
                <p class="text-3xl font-bold mt-2" :class="card.valueClass">{{ card.value }}</p>
              </div>
              <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="card.icon"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <div class="app-card app-card--cyan p-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Distribusi Pengguna</h2>
            <div class="space-y-4">
              <div v-for="row in userDistribution" :key="row.label">
                <div class="flex justify-between mb-2">
                  <span class="text-slate-700 dark:text-slate-300">{{ row.label }}</span>
                  <span :class="row.valueClass" class="font-semibold">{{ row.value }}</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-2">
                  <div :class="row.barClass" class="h-2 rounded-full" :style="{ width: `${row.percent}%` }"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="app-card app-card--cyan p-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Status Peminjaman</h2>
            <div class="space-y-4">
              <div v-for="row in borrowingDistribution" :key="row.label">
                <div class="flex justify-between mb-2">
                  <span class="text-slate-700 dark:text-slate-300">{{ row.label }}</span>
                  <span :class="row.valueClass" class="font-semibold">{{ row.value }}</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-2">
                  <div :class="row.barClass" class="h-2 rounded-full" :style="{ width: `${row.percent}%` }"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="app-card app-card--cyan p-6">
          <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Aktivitas Terbaru</h2>
          <div v-if="loading" class="rounded-2xl bg-slate-100 dark:bg-slate-800/50 p-4 text-sm text-slate-700 dark:text-slate-300">
            Memuat aktivitas...
          </div>
          <div v-else-if="recentActivities.length" class="space-y-3">
            <div v-for="item in recentActivities" :key="item.id" class="rounded-2xl bg-slate-100 dark:bg-slate-800/50 p-4 text-sm text-slate-700 dark:text-slate-300">
              <p class="font-semibold text-slate-900 dark:text-white">{{ item.deskripsi }}</p>
              <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ item.aksi }} • {{ item.entitas }} • {{ formatDateTime(item.createdAt) }}</p>
            </div>
          </div>
          <div v-else class="rounded-2xl bg-slate-100 dark:bg-slate-800/50 p-4 text-sm text-slate-700 dark:text-slate-300">
            Belum ada aktivitas.
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
  name: 'StatistikAdmin',
  components: { SidebarAdmin, Navbar },
  data() {
    return {
      loading: false,
      users: [],
      tools: [],
      borrowings: [],
      activityLogs: [],
    }
  },
  computed: {
    cards() {
      return [
        { label: 'Total Pengguna', value: this.users.length, valueClass: 'text-cyan-600 dark:text-cyan-400', cardClass: 'bg-blue-100 dark:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40', icon: 'M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM15 20H9m6 0h.01M9 20H3v-2a6 6 0 0112 0v2z' },
        { label: 'Total Alat', value: this.tools.length, valueClass: 'text-green-600 dark:text-green-400', cardClass: 'bg-green-100 dark:bg-green-900/40 border border-green-300 dark:border-green-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40', icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' },
        { label: 'Total Peminjaman', value: this.borrowings.length, valueClass: 'text-purple-600 dark:text-purple-400', cardClass: 'bg-blue-100 dark:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40', icon: 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z' },
        { label: 'Aktivitas Log', value: this.activityLogs.length, valueClass: 'text-orange-600 dark:text-orange-400', cardClass: 'bg-red-100 dark:bg-red-900/40 border border-red-300 dark:border-red-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40', icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' },
      ]
    },
    userDistribution() {
      const total = this.users.length || 1
      const byRole = (role) => this.users.filter((item) => String(item.role || '').toLowerCase() === role).length
      const rows = [
        { label: 'Peminjam', value: byRole('peminjam'), valueClass: 'text-cyan-400', barClass: 'bg-cyan-500' },
        { label: 'Staff', value: byRole('staff'), valueClass: 'text-green-400', barClass: 'bg-green-500' },
        { label: 'Owner', value: byRole('owner'), valueClass: 'text-purple-400', barClass: 'bg-purple-500' },
        { label: 'Admin', value: byRole('admin'), valueClass: 'text-orange-400', barClass: 'bg-orange-500' },
      ]
      return rows.map((row) => ({ ...row, percent: Math.round((row.value / total) * 100) }))
    },
    borrowingDistribution() {
      const total = this.borrowings.length || 1
      const count = (status) => this.borrowings.filter((item) => String(item.status || '').toLowerCase() === status).length
      const rows = [
        { label: 'Aktif', value: count('dipinjam') + count('disetujui') + count('pending'), valueClass: 'text-cyan-400', barClass: 'bg-cyan-500' },
        { label: 'Selesai', value: count('dikembalikan') + count('selesai'), valueClass: 'text-green-400', barClass: 'bg-green-500' },
        { label: 'Terlambat', value: 0, valueClass: 'text-orange-400', barClass: 'bg-orange-500' },
      ]
      return rows.map((row) => ({ ...row, percent: Math.round((row.value / total) * 100) }))
    },
    recentActivities() {
      return this.activityLogs.slice(0, 5)
    },
  },
  async created() {
    await this.loadData()
  },
  methods: {
    async loadData() {
      this.loading = true
      try {
        const [users, tools, borrowings, logs] = await Promise.allSettled([
          apiRequest('/api/users'),
          apiRequest('/api/tools'),
          apiRequest('/api/borrowings'),
          apiRequest('/api/activity-logs'),
        ])
        this.users = users.status === 'fulfilled' && Array.isArray(users.value) ? users.value : []
        this.tools = tools.status === 'fulfilled' && Array.isArray(tools.value) ? tools.value : []
        this.borrowings = borrowings.status === 'fulfilled' && Array.isArray(borrowings.value) ? borrowings.value : []
        this.activityLogs = logs.status === 'fulfilled' && Array.isArray(logs.value) ? logs.value : []
      } catch (error) {
        this.users = []
        this.tools = []
        this.borrowings = []
        this.activityLogs = []
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
