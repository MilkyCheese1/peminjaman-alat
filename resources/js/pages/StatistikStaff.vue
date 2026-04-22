<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <SidebarStaff />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-6">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Statistik Staff</h1>
          <p class="text-slate-700 dark:text-slate-300">Ringkasan proses peminjaman dan pengembalian</p>
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
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Status Request</h2>
            <div class="space-y-4">
              <div v-for="row in requestRows" :key="row.label">
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
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Status Pengembalian</h2>
            <div class="space-y-4">
              <div v-for="row in returnRows" :key="row.label">
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

        <div class="bg-slate-900/90 border border-white/10 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
          <h2 class="text-xl font-bold text-white mb-6">Permintaan Pending</h2>
          <div v-if="loading" class="rounded-2xl bg-slate-800/50 p-4 text-sm text-slate-300">
            Memuat data permintaan...
          </div>
          <div v-else class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="text-left text-slate-400 text-sm border-b border-slate-800">
                  <th class="pb-3 font-semibold">Peminjam</th>
                  <th class="pb-3 font-semibold">Alat</th>
                  <th class="pb-3 font-semibold">Tanggal Request</th>
                  <th class="pb-3 font-semibold">Durasi</th>
                  <th class="pb-3 font-semibold">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="!pendingRequests.length">
                  <td colspan="5" class="py-10 text-center text-sm text-slate-400">Belum ada data permintaan.</td>
                </tr>
                <tr v-for="item in pendingRequests" :key="item.id" class="border-t border-slate-800">
                  <td class="py-4 text-slate-200">{{ item.namaPeminjam }}</td>
                  <td class="py-4 text-slate-200">{{ item.namaAlat }}</td>
                  <td class="py-4 text-slate-200">{{ formatDate(item.tanggalPinjam) }}</td>
                  <td class="py-4 text-slate-200">{{ durationLabel(item) }}</td>
                  <td class="py-4 text-slate-200">-</td>
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
import SidebarStaff from '../components/layout/SidebarStaff.vue'
import Navbar from '../components/layout/Navbar.vue'
import { apiRequest } from '../lib/api'

const pendingStatuses = ['pending']
const approvedStatuses = ['disetujui']
const returnPendingStatuses = ['dipinjam']
const verifiedReturnStatuses = ['dikembalikan', 'selesai']

export default {
  name: 'StatistikStaff',
  components: { SidebarStaff, Navbar },
  data() {
    return {
      loading: false,
      borrowings: [],
    }
  },
  computed: {
    cards() {
      return [
        { label: 'Pending Request', value: this.countPending, valueClass: 'text-cyan-600 dark:text-cyan-400', cardClass: 'bg-blue-100 dark:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
        { label: 'Disetujui Hari Ini', value: this.countApprovedToday, valueClass: 'text-green-600 dark:text-green-400', cardClass: 'bg-green-100 dark:bg-green-900/40 border border-green-300 dark:border-green-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40', icon: 'M5 13l4 4L19 7' },
        { label: 'Return Pending', value: this.countReturnPending, valueClass: 'text-purple-600 dark:text-purple-400', cardClass: 'bg-yellow-100 dark:bg-yellow-900/40 border border-yellow-300 dark:border-yellow-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40', icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' },
        { label: 'Ditolak', value: this.countRejected, valueClass: 'text-red-600 dark:text-red-400', cardClass: 'bg-red-100 dark:bg-red-900/40 border border-red-300 dark:border-red-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40', icon: 'M6 18L18 6M6 6l12 12' },
      ]
    },
    countPending() {
      return this.borrowings.filter((item) => pendingStatuses.includes(String(item.status).toLowerCase())).length
    },
    countApprovedToday() {
      const today = new Date().toISOString().slice(0, 10)
      return this.borrowings.filter((item) => approvedStatuses.includes(String(item.status).toLowerCase()) && String(item.tanggalPinjam || '').startsWith(today)).length
    },
    countReturnPending() {
      return this.borrowings.filter((item) => returnPendingStatuses.includes(String(item.status).toLowerCase())).length
    },
    countRejected() {
      return this.borrowings.filter((item) => String(item.status).toLowerCase() === 'ditolak').length
    },
    requestRows() {
      const total = this.borrowings.length || 1
      const pending = this.countPending
      const approved = this.borrowings.filter((item) => approvedStatuses.includes(String(item.status).toLowerCase())).length
      return [
        { label: 'Pending', value: pending, valueClass: 'text-cyan-400', barClass: 'bg-cyan-500', percent: Math.round((pending / total) * 100) },
        { label: 'Disetujui', value: approved, valueClass: 'text-green-400', barClass: 'bg-green-500', percent: Math.round((approved / total) * 100) },
      ]
    },
    returnRows() {
      const total = this.borrowings.length || 1
      const pending = this.countReturnPending
      const verified = this.borrowings.filter((item) => verifiedReturnStatuses.includes(String(item.status).toLowerCase())).length
      return [
        { label: 'Pending', value: pending, valueClass: 'text-cyan-400', barClass: 'bg-cyan-500', percent: Math.round((pending / total) * 100) },
        { label: 'Terverifikasi', value: verified, valueClass: 'text-green-400', barClass: 'bg-green-500', percent: Math.round((verified / total) * 100) },
      ]
    },
    pendingRequests() {
      return this.borrowings.filter((item) => pendingStatuses.includes(String(item.status).toLowerCase())).slice(0, 10)
    },
  },
  async created() {
    await this.loadBorrowings()
  },
  methods: {
    async loadBorrowings() {
      this.loading = true
      try {
        const result = await Promise.allSettled([apiRequest('/api/borrowings')])
        const data = result[0]
        this.borrowings = data.status === 'fulfilled' && Array.isArray(data.value) ? data.value : []
      } catch (error) {
        this.borrowings = []
      } finally {
        this.loading = false
      }
    },
    formatDate(value) {
      if (!value) return '-'
      try {
        return new Intl.DateTimeFormat('id-ID', {
          day: '2-digit',
          month: 'short',
          year: 'numeric',
        }).format(new Date(`${value}T00:00:00`))
      } catch (error) {
        return value
      }
    },
    durationLabel(item) {
      const start = item?.tanggalPinjam ? new Date(`${item.tanggalPinjam}T00:00:00`) : null
      const end = item?.tanggalKembaliRencana ? new Date(`${item.tanggalKembaliRencana}T00:00:00`) : null
      if (!start || !end) return '-'
      const diff = Math.max(1, Math.round((end - start) / 86400000))
      return `${diff} hari`
    },
  },
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
