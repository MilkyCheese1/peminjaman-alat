<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col lg:flex-row">
    <SidebarPeminjam />

    <div class="flex-1 flex flex-col">
      <Navbar />

      <main class="flex-1 p-4 sm:p-6">
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Dashboard Peminjam</h1>
          <p class="text-slate-700 dark:text-slate-300">Jelajahi dan kelola peminjaman alat Anda</p>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3 mb-8">
          <article v-for="card in cards" :key="card.label" :class="card.cardClass">
            <p class="text-sm font-medium opacity-80">{{ card.label }}</p>
            <p class="mt-3 text-3xl font-bold">{{ card.value }}</p>
            <p class="mt-2 text-sm opacity-80">{{ card.caption }}</p>
          </article>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mb-8">
          <router-link to="/alat" class="app-card app-card--cyan p-6 transition hover:bg-cyan-100 dark:hover:bg-cyan-500/15 block">
            <h2 class="text-xl font-bold mb-2">Jelajahi Alat</h2>
            <p class="mb-4 text-slate-700 dark:text-slate-300">Temukan alat yang Anda butuhkan</p>
            <span class="inline-flex items-center rounded-full bg-cyan-500 px-4 py-2 text-sm font-semibold text-white dark:text-slate-950">Buka Daftar Alat</span>
          </router-link>
          <router-link to="/peminjaman-saya" class="app-card app-card--cyan p-6 transition hover:bg-cyan-100 dark:hover:bg-cyan-500/15 block">
            <h2 class="text-xl font-bold mb-2">Peminjaman Saya</h2>
            <p class="mb-4 text-slate-700 dark:text-slate-300">Lihat status peminjaman Anda</p>
            <span class="inline-flex items-center rounded-full bg-cyan-500 px-4 py-2 text-sm font-semibold text-white dark:text-slate-950">Lihat Riwayat</span>
          </router-link>
        </div>

        <div class="mb-8">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Jelajahi Alat Tersedia</h2>
          <div v-if="loading" class="app-card app-card--cyan p-6 text-slate-700 dark:text-slate-300">
            Memuat data alat...
          </div>
          <div v-else class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            <article
              v-for="item in availableTools"
              :key="item.id"
              class="app-card app-card--cyan p-6"
            >
              <p class="text-lg font-bold text-slate-900 dark:text-white">{{ item.namaAlat || item.nama_alat || '-' }}</p>
              <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ item.kategori || '-' }}</p>
              <p class="mt-3 text-sm text-slate-700 dark:text-slate-300">{{ item.status || 'Tersedia' }}</p>
            </article>
            <div v-if="!availableTools.length" class="app-card app-card--cyan p-6 text-slate-700 dark:text-slate-300">
              Belum ada alat yang tersedia.
            </div>
          </div>
        </div>

        <div>
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Peminjaman Saya</h2>
          <div class="app-card app-card--cyan overflow-hidden">
            <div v-if="loading" class="p-10 text-center text-sm text-slate-600 dark:text-slate-400">
              Memuat riwayat peminjaman...
            </div>
            <div v-else class="overflow-x-auto">
              <table class="w-full min-w-[720px]">
                <thead class="bg-slate-100 dark:bg-slate-800">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Tanggal Kembali</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Total Denda</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white text-slate-700 dark:divide-slate-800 dark:bg-slate-950 dark:text-slate-200">
                  <tr v-if="!borrowings.length">
                    <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-600 dark:text-slate-400">
                      Belum ada data peminjaman.
                    </td>
                  </tr>
                  <tr v-for="item in borrowings" :key="item.id">
                    <td class="px-6 py-4">
                      <div class="font-semibold text-slate-900 dark:text-white">{{ item.namaAlat }}</div>
                      <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ item.kode }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ formatDate(item.tanggalPinjam) }}</td>
                    <td class="px-6 py-4 text-sm">{{ formatDate(item.tanggalKembaliRencana) }}</td>
                    <td class="px-6 py-4 text-sm">{{ item.status }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-white">{{ formatCurrency(item.biaya) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
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

export default {
  name: 'DashboardPeminjam',
  components: {
    SidebarPeminjam,
    Navbar,
  },
  data() {
    return {
      loading: false,
      items: [],
      tools: [],
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

      return (Array.isArray(this.items) ? this.items : []).filter((item) => {
        if (sessionId && Number(item.peminjamId || 0) === sessionId) {
          return true
        }

        const borrowerName = String(item.namaPeminjam || '').trim().toLowerCase()
        return borrowerName === sessionName || borrowerName === sessionEmail
      })
    },
    availableTools() {
      return this.tools.filter((item) => String(item.status || '').toLowerCase() === 'tersedia').slice(0, 6)
    },
    activeBorrowingsCount() {
      return this.userBorrowings.filter((item) => ['Pending', 'Disetujui', 'Dipinjam'].includes(item.status)).length
    },
    totalFine() {
      return this.userBorrowings.reduce((sum, item) => sum + Number(item.biaya || 0), 0)
    },
    latestBorrowing() {
      return this.userBorrowings[0] || null
    },
    cards() {
      return [
        {
          label: 'Peminjaman Aktif',
          value: this.activeBorrowingsCount,
          caption: 'Transaksi yang masih berjalan.',
          cardClass: 'bg-blue-100 dark:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40',
        },
        {
          label: 'Total Denda',
          value: this.formatCurrency(this.totalFine),
          caption: 'Akumulasi biaya pada akun ini.',
          cardClass: 'bg-red-100 dark:bg-red-900/40 border border-red-300 dark:border-red-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40',
        },
        {
          label: 'Riwayat Terbaru',
          value: this.userBorrowings.length,
          caption: this.latestBorrowing ? `Terakhir: ${this.latestBorrowing.namaAlat}` : 'Belum ada peminjaman.',
          cardClass: 'bg-green-100 dark:bg-green-900/40 border border-green-300 dark:border-green-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40',
        },
      ]
    },
    borrowings() {
      return [...this.userBorrowings].sort((left, right) => new Date(right.updatedAt || right.createdAt || 0) - new Date(left.updatedAt || left.createdAt || 0))
    },
  },
  async created() {
    await this.loadData()
  },
  methods: {
    async loadData() {
      this.loading = true

      try {
        const sessionId = Number(this.session?.id || 0)
        const sessionName = String(this.session?.nama || '').trim()
        const sessionEmail = String(this.session?.email || '').trim()
        const query = new URLSearchParams()

        if (sessionId) query.set('peminjamId', String(sessionId))
        if (sessionName) query.set('peminjamNama', sessionName)
        if (sessionEmail) query.set('peminjamEmail', sessionEmail)

        const [borrowings, tools] = await Promise.allSettled([
          apiRequest(query.toString() ? `/api/borrowings?${query.toString()}` : '/api/borrowings'),
          apiRequest('/api/tools'),
        ])

        this.items = borrowings.status === 'fulfilled' && Array.isArray(borrowings.value) ? borrowings.value : []
        this.tools = tools.status === 'fulfilled' && Array.isArray(tools.value) ? tools.value : []
      } catch (error) {
        this.items = []
        this.tools = []
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
