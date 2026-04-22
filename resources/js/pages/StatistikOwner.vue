<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <SidebarOwner />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-6">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Statistik Owner</h1>
          <p class="text-slate-700 dark:text-slate-300">Ringkasan data alat dan peminjaman Anda</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
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
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Status Alat</h2>
            <div class="space-y-4">
              <div v-for="row in toolStatusRows" :key="row.label">
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
              <div v-for="row in borrowingRows" :key="row.label">
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
          <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Daftar Alat</h2>
          <div v-if="loading" class="rounded-2xl bg-slate-100 dark:bg-slate-800/50 p-4 text-sm text-slate-700 dark:text-slate-300">
            Memuat data alat...
          </div>
          <div v-else class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="text-left text-slate-600 dark:text-slate-400 text-sm border-b border-slate-200 dark:border-slate-800">
                  <th class="pb-3 font-semibold">Nama Alat</th>
                  <th class="pb-3 font-semibold">Kategori</th>
                  <th class="pb-3 font-semibold">Status</th>
                  <th class="pb-3 font-semibold">Kondisi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="!tools.length">
                  <td colspan="4" class="py-10 text-center text-sm text-slate-600 dark:text-slate-400">Belum ada data alat.</td>
                </tr>
                <tr v-for="item in tools" :key="item.id" class="border-t border-slate-200 dark:border-slate-800">
                  <td class="py-4 font-semibold text-slate-900 dark:text-white">{{ item.namaAlat }}</td>
                  <td class="py-4">{{ item.kategori }}</td>
                  <td class="py-4">{{ item.status }}</td>
                  <td class="py-4">{{ item.kondisi }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import SidebarOwner from '../components/layout/SidebarOwner.vue'
import Navbar from '../components/layout/Navbar.vue'
import { apiRequest } from '../lib/api'

const availableStatuses = ['tersedia']
const unavailableStatuses = ['dipinjam', 'maintenance']
const activeStatuses = ['pending', 'disetujui', 'dipinjam']
const completedStatuses = ['dikembalikan', 'selesai']

export default {
  name: 'StatistikOwner',
  components: { SidebarOwner, Navbar },
  data() {
    return {
      loading: false,
      tools: [],
      borrowings: [],
    }
  },
  computed: {
    cards() {
      return [
        { label: 'Total Alat', value: this.tools.length, valueClass: 'text-cyan-600 dark:text-cyan-400', cardClass: 'bg-blue-100 dark:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40', icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' },
        { label: 'Alat Tersedia', value: this.availableCount, valueClass: 'text-green-600 dark:text-green-400', cardClass: 'bg-green-100 dark:bg-green-900/40 border border-green-300 dark:border-green-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
        { label: 'Total Peminjaman', value: this.borrowings.length, valueClass: 'text-purple-600 dark:text-purple-400', cardClass: 'bg-blue-100 dark:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40', icon: 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z' },
        { label: 'Alat Tidak Tersedia', value: this.unavailableCount, valueClass: 'text-orange-600 dark:text-orange-400', cardClass: 'bg-red-100 dark:bg-red-900/40 border border-red-300 dark:border-red-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40', icon: 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z' },
      ]
    },
    availableCount() {
      return this.tools.filter((item) => availableStatuses.includes(String(item.status).toLowerCase())).length
    },
    unavailableCount() {
      return this.tools.filter((item) => unavailableStatuses.includes(String(item.status).toLowerCase())).length
    },
    toolStatusRows() {
      const total = this.tools.length || 1
      const available = this.availableCount
      const unavailable = this.unavailableCount
      return [
        { label: 'Tersedia', value: available, valueClass: 'text-green-400', barClass: 'bg-green-500', percent: Math.round((available / total) * 100) },
        { label: 'Tidak Tersedia', value: unavailable, valueClass: 'text-cyan-400', barClass: 'bg-cyan-500', percent: Math.round((unavailable / total) * 100) },
      ]
    },
    borrowingRows() {
      const total = this.borrowings.length || 1
      const active = this.borrowings.filter((item) => activeStatuses.includes(String(item.status).toLowerCase())).length
      const completed = this.borrowings.filter((item) => completedStatuses.includes(String(item.status).toLowerCase())).length
      return [
        { label: 'Aktif', value: active, valueClass: 'text-cyan-400', barClass: 'bg-cyan-500', percent: Math.round((active / total) * 100) },
        { label: 'Selesai', value: completed, valueClass: 'text-green-400', barClass: 'bg-green-500', percent: Math.round((completed / total) * 100) },
      ]
    },
  },
  async created() {
    await this.loadData()
  },
  methods: {
    async loadData() {
      this.loading = true
      try {
        const [tools, borrowings] = await Promise.allSettled([
          apiRequest('/api/tools'),
          apiRequest('/api/borrowings'),
        ])
        this.tools = tools.status === 'fulfilled' && Array.isArray(tools.value) ? tools.value : []
        this.borrowings = borrowings.status === 'fulfilled' && Array.isArray(borrowings.value) ? borrowings.value : []
      } catch (error) {
        this.tools = []
        this.borrowings = []
      } finally {
        this.loading = false
      }
    },
  },
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
