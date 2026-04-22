<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col lg:flex-row">
    <SidebarOwner />

    <div class="flex-1 flex flex-col">
      <Navbar />

      <main class="flex-1 p-4 sm:p-6">
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Dashboard Owner</h1>
          <p class="text-slate-700 dark:text-slate-300">Selamat datang di dashboard owner</p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 mb-8">
          <article v-for="card in cards" :key="card.label" :class="card.cardClass">
            <p class="text-sm font-medium opacity-80">{{ card.label }}</p>
            <p class="mt-3 text-3xl font-bold">{{ card.value }}</p>
            <p class="mt-2 text-sm opacity-80">{{ card.caption }}</p>
          </article>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-8">
          <section class="app-card app-card--cyan p-6">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Daftar Alat</h2>
            <div v-if="loading" class="rounded-2xl bg-slate-100 p-4 text-sm text-slate-600 dark:bg-slate-800/50 dark:text-slate-300">
              Memuat data alat...
            </div>
            <div v-else class="space-y-3">
              <article
                v-for="item in recentTools"
                :key="item.id"
                class="rounded-2xl bg-slate-100 p-4 text-sm text-slate-700 dark:bg-slate-800/50 dark:text-slate-300"
              >
                <p class="font-semibold text-slate-900 dark:text-white">{{ item.namaAlat || item.nama_alat || '-' }}</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ item.kategori || '-' }} · {{ item.status || '-' }}</p>
              </article>
              <div v-if="!recentTools.length" class="rounded-2xl bg-slate-100 p-4 text-sm text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                Belum ada data alat untuk ditampilkan.
              </div>
            </div>
          </section>

          <section class="app-card app-card--cyan p-6">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Laporan Pendapatan</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <div class="rounded-3xl bg-slate-100 p-4 dark:bg-slate-800/50">
                <p class="text-sm text-slate-600 dark:text-slate-400">Akumulasi Denda</p>
                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ formatCurrency(totalFine) }}</p>
              </div>
              <div class="rounded-3xl bg-slate-100 p-4 dark:bg-slate-800/50">
                <p class="text-sm text-slate-600 dark:text-slate-400">Transaksi Aktif</p>
                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ activeBorrowings.length }}</p>
              </div>
            </div>
            <div class="mt-5 space-y-3">
              <article
                v-for="item in recentBorrowings"
                :key="item.id"
                class="rounded-2xl bg-slate-100 p-4 text-sm text-slate-700 dark:bg-slate-800/50 dark:text-slate-300"
              >
                <p class="font-semibold text-slate-900 dark:text-white">{{ item.kode }}</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ item.namaPeminjam }} · {{ formatCurrency(item.biaya) }}</p>
              </article>
              <div v-if="!recentBorrowings.length" class="rounded-2xl bg-slate-100 p-4 text-sm text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
                Belum ada data pendapatan.
              </div>
            </div>
          </section>
        </div>

        <section>
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Log Aktivitas</h2>
          <div class="app-card app-card--cyan overflow-hidden">
            <div v-if="loading" class="p-6 text-sm text-slate-600 dark:text-slate-300">
              Memuat log aktivitas...
            </div>
            <div v-else class="overflow-x-auto">
              <table class="w-full min-w-[640px]">
                <thead class="bg-slate-100 dark:bg-slate-800">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Aktivitas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Pengguna</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-800 dark:bg-slate-950">
                  <tr v-if="!recentActivities.length">
                    <td colspan="3" class="px-6 py-10 text-center text-sm text-slate-600 dark:text-slate-400">
                      Belum ada aktivitas.
                    </td>
                  </tr>
                  <tr v-for="item in recentActivities" :key="item.id">
                    <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-200">{{ formatDateTime(item.createdAt) }}</td>
                    <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-200">{{ item.deskripsi || item.aksi || '-' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-200">{{ item.userName || item.user || '-' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>
</template>

<script>
import SidebarOwner from '../components/layout/SidebarOwner.vue'
import Navbar from '../components/layout/Navbar.vue'
import { apiRequest } from '../lib/api'

export default {
  name: 'DashboardOwner',
  components: {
    SidebarOwner,
    Navbar,
  },
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
        {
          label: 'Total Peminjaman',
          value: this.borrowings.length,
          caption: 'Semua transaksi yang tersimpan.',
          cardClass: 'bg-blue-100 dark:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40',
        },
        {
          label: 'Alat Tersedia',
          value: this.availableTools.length,
          caption: 'Inventaris yang siap dipakai.',
          cardClass: 'bg-green-100 dark:bg-green-900/40 border border-green-300 dark:border-green-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40',
        },
        {
          label: 'Akumulasi Denda',
          value: this.formatCurrency(this.totalFine),
          caption: 'Total denda dari transaksi tersimpan.',
          cardClass: 'bg-purple-100 dark:bg-purple-900/40 border border-purple-300 dark:border-purple-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40',
        },
        {
          label: 'Total Pengguna',
          value: this.users.length,
          caption: 'Seluruh akun yang aktif di sistem.',
          cardClass: 'bg-cyan-100 dark:bg-cyan-900/40 border border-cyan-300 dark:border-cyan-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40',
        },
      ]
    },
    availableTools() {
      return this.tools.filter((item) => String(item.status || '').toLowerCase() === 'tersedia')
    },
    activeBorrowings() {
      return this.borrowings.filter((item) => ['Pending', 'Disetujui', 'Dipinjam'].includes(item.status) || item.statusPengembalian === 'Belum Dikembalikan')
    },
    totalFine() {
      return this.borrowings.reduce((total, item) => total + Number(item.biaya || 0), 0)
    },
    recentTools() {
      return [...this.tools]
        .sort((left, right) => Number(right.id || 0) - Number(left.id || 0))
        .slice(0, 6)
    },
    recentBorrowings() {
      return [...this.borrowings]
        .sort((left, right) => new Date(right.updatedAt || right.createdAt || 0) - new Date(left.updatedAt || left.createdAt || 0))
        .slice(0, 5)
    },
    recentActivities() {
      return [...this.activityLogs]
        .sort((left, right) => new Date(right.createdAt || 0) - new Date(left.createdAt || 0))
        .slice(0, 5)
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
    formatCurrency(value) {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
      }).format(Number(value || 0))
    },
    formatDateTime(value) {
      if (!value) return '-'
      try {
        return new Intl.DateTimeFormat('id-ID', { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(String(value).replace(' ', 'T')))
      } catch (error) {
        return value
      }
    },
  },
}
</script>
