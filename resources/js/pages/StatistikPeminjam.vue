<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col lg:flex-row">
    <SidebarPeminjam />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-4 sm:p-6">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Statistik Peminjaman Saya</h1>
          <p class="text-slate-700 dark:text-slate-300">Ringkasan aktivitas peminjaman Anda</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
          <div v-for="card in cards" :key="card.label" :class="card.cardClass">
            <div class="flex items-center justify-between">
              <div>
                <p :class="card.labelClass" class="text-sm">{{ card.label }}</p>
                <p :class="card.valueClass" class="text-3xl font-bold mt-2">{{ card.value }}</p>
              </div>
              <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="card.icon" />
              </svg>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <div class="app-card app-card--cyan p-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Aktivitas Terbaru</h2>
            <div v-if="loading" class="rounded-2xl bg-slate-100 dark:bg-slate-800/50 p-4 text-sm text-slate-700 dark:text-slate-300">
              Memuat aktivitas...
            </div>
            <div v-else-if="recentActivities.length" class="space-y-3">
              <div v-for="item in recentActivities" :key="item.id" class="rounded-2xl bg-slate-100 dark:bg-slate-800/50 p-4 text-sm text-slate-700 dark:text-slate-300">
                <p class="font-semibold text-slate-900 dark:text-white">{{ item.namaAlat }}</p>
                <p class="mt-1">{{ item.status }} pada {{ formatDate(item.tanggalPinjam) }}</p>
              </div>
            </div>
            <div v-else class="rounded-2xl bg-slate-100 dark:bg-slate-800/50 p-4 text-sm text-slate-700 dark:text-slate-300">
              Belum ada aktivitas.
            </div>
          </div>

          <div class="app-card app-card--cyan p-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Distribusi Status</h2>
            <div class="space-y-4">
              <div v-for="row in statusRows" :key="row.label">
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
          <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Riwayat Peminjaman</h2>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="text-left text-slate-600 dark:text-slate-400 text-sm border-b border-slate-200 dark:border-slate-800">
                  <th class="pb-3 font-semibold">Alat</th>
                  <th class="pb-3 font-semibold">Tanggal Pinjam</th>
                  <th class="pb-3 font-semibold">Tanggal Kembali</th>
                  <th class="pb-3 font-semibold">Status</th>
                  <th class="pb-3 font-semibold">Denda</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="!borrowings.length">
                  <td colspan="5" class="py-10 text-center text-sm text-slate-600 dark:text-slate-400">
                    Belum ada data peminjaman.
                  </td>
                </tr>
                <tr v-for="item in borrowings" :key="item.id" class="border-t border-slate-200 dark:border-slate-800">
                  <td class="py-4 font-semibold text-slate-900 dark:text-white">{{ item.namaAlat }}</td>
                  <td class="py-4">{{ formatDate(item.tanggalPinjam) }}</td>
                  <td class="py-4">{{ formatDate(item.tanggalKembaliRencana) }}</td>
                  <td class="py-4">{{ item.status }}</td>
                  <td class="py-4">{{ formatCurrency(item.biaya) }}</td>
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
import SidebarPeminjam from '../components/layout/SidebarPeminjam.vue'
import Navbar from '../components/layout/Navbar.vue'
import { apiRequest } from '../lib/api'
import { getAuthSession } from '../auth/session'

const ACTIVE_STATUSES = ['Pending', 'Disetujui', 'Dipinjam']
const COMPLETED_STATUSES = ['Dikembalikan', 'Selesai']

export default {
  name: 'StatistikPeminjam',
  components: {
    SidebarPeminjam,
    Navbar,
  },
  data() {
    return {
      loading: false,
      borrowings: [],
    }
  },
  computed: {
    session() {
      return getAuthSession()
    },
    userBorrowings() {
      const sessionId = Number(this.session?.id || 0)
      const sessionName = String(this.session?.nama || '').trim().toLowerCase()
      const sessionEmail = String(this.session?.email || '').trim().toLowerCase()

      const matched = (Array.isArray(this.borrowings) ? this.borrowings : []).filter((item) => {
        if (sessionId && Number(item.peminjamId || 0) === sessionId) return true
        const borrowerName = String(item.namaPeminjam || '').trim().toLowerCase()
        return borrowerName === sessionName || borrowerName === sessionEmail
      })

      return matched.length ? matched : (Array.isArray(this.borrowings) ? this.borrowings : [])
    },
    activeBorrowingsCount() {
      return this.userBorrowings.filter((item) => ACTIVE_STATUSES.includes(item.status)).length
    },
    completedBorrowingsCount() {
      return this.userBorrowings.filter((item) => COMPLETED_STATUSES.includes(item.status)).length
    },
    totalFine() {
      return this.userBorrowings.reduce((sum, item) => sum + Number(item.biaya || 0), 0)
    },
    lateCount() {
      const today = new Date()
      today.setHours(0, 0, 0, 0)

      return this.userBorrowings.filter((item) => {
        if (COMPLETED_STATUSES.includes(item.status)) return false
        if (!item.tanggalKembaliRencana) return false
        const due = new Date(`${item.tanggalKembaliRencana}T00:00:00`)
        return due < today
      }).length
    },
    latestBorrowing() {
      return this.userBorrowings[0] || null
    },
    cards() {
      return [
        {
          label: 'Peminjaman Aktif',
          value: this.activeBorrowingsCount,
          cardClass: 'bg-blue-100 dark:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40',
          labelClass: 'text-slate-600 dark:text-slate-400',
          valueClass: 'text-cyan-600 dark:text-cyan-400',
          icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        },
        {
          label: 'Selesai',
          value: this.completedBorrowingsCount,
          cardClass: 'bg-green-100 dark:bg-green-900/40 border border-green-300 dark:border-green-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40',
          labelClass: 'text-slate-600 dark:text-slate-400',
          valueClass: 'text-green-600 dark:text-green-400',
          icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        },
        {
          label: 'Total Denda',
          value: this.formatCurrency(this.totalFine),
          cardClass: 'bg-red-100 dark:bg-red-900/40 border border-red-300 dark:border-red-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40',
          labelClass: 'text-slate-600 dark:text-slate-400',
          valueClass: 'text-orange-600 dark:text-orange-400',
          icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        },
        {
          label: 'Keterlambatan',
          value: this.lateCount,
          cardClass: 'bg-yellow-100 dark:bg-yellow-900/40 border border-yellow-300 dark:border-yellow-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40',
          labelClass: 'text-slate-600 dark:text-slate-400',
          valueClass: 'text-red-600 dark:text-red-400',
          icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        },
      ]
    },
    recentActivities() {
      return this.userBorrowings.slice(0, 3)
    },
    statusRows() {
      const total = this.userBorrowings.length || 1
      const active = this.activeBorrowingsCount
      const completed = this.completedBorrowingsCount
      const late = this.lateCount
      return [
        { label: 'Aktif', value: active, percent: Math.round((active / total) * 100), valueClass: 'text-cyan-400', barClass: 'bg-cyan-500' },
        { label: 'Selesai', value: completed, percent: Math.round((completed / total) * 100), valueClass: 'text-green-400', barClass: 'bg-green-500' },
        { label: 'Terlambat', value: late, percent: Math.round((late / total) * 100), valueClass: 'text-orange-400', barClass: 'bg-orange-500' },
      ]
    },
  },
  async created() {
    await this.loadBorrowings()
  },
  methods: {
    async loadBorrowings() {
      this.loading = true
      try {
        const data = await apiRequest('/api/borrowings')
        this.borrowings = Array.isArray(data) ? data : []
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
    formatCurrency(value) {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
      }).format(Number(value || 0))
    },
  },
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
