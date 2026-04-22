<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <SidebarStaff />

    <div class="flex-1 flex flex-col">
      <Navbar />

      <main class="flex-1 p-6">
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Dashboard Staff</h1>
          <p class="text-slate-700 dark:text-slate-300">Kelola proses peminjaman dan pengembalian alat</p>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3 mb-8">
          <article v-for="card in cards" :key="card.label" :class="card.cardClass">
            <p class="text-sm font-medium opacity-80">{{ card.label }}</p>
            <p class="mt-3 text-3xl font-bold">{{ card.value }}</p>
            <p class="mt-2 text-sm opacity-80">{{ card.caption }}</p>
          </article>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-8">
          <section class="app-card app-card--cyan p-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Daftar Alat</h2>
            <div v-if="loading" class="rounded-2xl bg-slate-100 p-4 text-sm text-slate-600 dark:bg-slate-800/50 dark:text-slate-300">
              Memuat data alat...
            </div>
            <div v-else class="overflow-x-auto">
              <table class="w-full min-w-[520px]">
                <thead class="bg-slate-100 dark:bg-slate-800">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Nama Alat</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Status</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-800 dark:bg-slate-950">
                  <tr v-if="!recentTools.length">
                    <td colspan="3" class="px-4 py-10 text-center text-sm text-slate-600 dark:text-slate-400">
                      Belum ada data alat.
                    </td>
                  </tr>
                  <tr v-for="item in recentTools" :key="item.id">
                    <td class="px-4 py-4 font-semibold text-slate-900 dark:text-white">{{ item.namaAlat || item.nama_alat || '-' }}</td>
                    <td class="px-4 py-4 text-slate-700 dark:text-slate-200">{{ item.kategori || '-' }}</td>
                    <td class="px-4 py-4 text-slate-700 dark:text-slate-200">{{ item.status || '-' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>

          <section class="app-card app-card--cyan p-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Proses Peminjaman Pending</h2>
            <div v-if="loading" class="rounded-2xl bg-slate-100 p-4 text-sm text-slate-600 dark:bg-slate-800/50 dark:text-slate-300">
              Memuat data peminjaman...
            </div>
            <div v-else class="overflow-x-auto">
              <table class="w-full min-w-[620px]">
                <thead class="bg-slate-100 dark:bg-slate-800">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Pengguna</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Alat</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Tanggal Pinjam</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Status</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-800 dark:bg-slate-950">
                  <tr v-if="!pendingBorrowings.length">
                    <td colspan="4" class="px-4 py-10 text-center text-sm text-slate-600 dark:text-slate-400">
                      Tidak ada peminjaman pending saat ini.
                    </td>
                  </tr>
                  <tr v-for="item in pendingBorrowings" :key="item.id">
                    <td class="px-4 py-4 text-slate-700 dark:text-slate-200">{{ item.namaPeminjam || '-' }}</td>
                    <td class="px-4 py-4 text-slate-700 dark:text-slate-200">{{ item.namaAlat || '-' }}</td>
                    <td class="px-4 py-4 text-slate-700 dark:text-slate-200">{{ formatDate(item.tanggalPinjam) }}</td>
                    <td class="px-4 py-4 text-slate-700 dark:text-slate-200">{{ item.status || '-' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <section class="app-card app-card--cyan p-6">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Proses Pengembalian Hari Ini</h2>
            <div v-if="loading" class="rounded-2xl bg-slate-100 p-4 text-sm text-slate-600 dark:bg-slate-800/50 dark:text-slate-300">
              Memuat data pengembalian...
            </div>
            <div v-else class="overflow-x-auto">
              <table class="w-full min-w-[700px]">
                <thead class="bg-slate-100 dark:bg-slate-800">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Pengguna</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Alat</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Jatuh Tempo</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700 dark:text-slate-400">Status</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-800 dark:bg-slate-950">
                  <tr v-if="!todayReturns.length">
                    <td colspan="4" class="px-4 py-10 text-center text-sm text-slate-600 dark:text-slate-400">
                      Tidak ada pengembalian yang perlu dicek hari ini.
                    </td>
                  </tr>
                  <tr v-for="item in todayReturns" :key="item.id">
                    <td class="px-4 py-4 text-slate-700 dark:text-slate-200">{{ item.namaPeminjam || '-' }}</td>
                    <td class="px-4 py-4 text-slate-700 dark:text-slate-200">{{ item.namaAlat || '-' }}</td>
                    <td class="px-4 py-4 text-slate-700 dark:text-slate-200">{{ formatDate(item.tanggalKembaliRencana) }}</td>
                    <td class="px-4 py-4 text-slate-700 dark:text-slate-200">{{ item.statusPengembalian || 'Belum Dikembalikan' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>

          <section class="app-card app-card--cyan p-6">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Transaksi Terbaru</h2>
            <div v-if="recentBorrowings.length" class="space-y-3">
              <article
                v-for="item in recentBorrowings"
                :key="item.id"
                class="rounded-2xl bg-slate-100 p-4 text-sm text-slate-700 dark:bg-slate-800/50 dark:text-slate-300"
              >
                <p class="font-semibold text-slate-900 dark:text-white">{{ item.kode }}</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ item.namaPeminjam }} - {{ item.namaAlat }}</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ item.status }}</p>
              </article>
            </div>
            <div v-else class="rounded-2xl bg-slate-100 p-4 text-sm text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
              Belum ada data peminjaman terbaru.
            </div>
          </section>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import SidebarStaff from '../components/layout/SidebarStaff.vue'
import Navbar from '../components/layout/Navbar.vue'
import { apiRequest } from '../lib/api'
import { getTodayLocalISODate } from '../data/staffBorrowing'

export default {
  name: 'DashboardStaff',
  components: {
    SidebarStaff,
    Navbar,
  },
  data() {
    return {
      loading: false,
      borrowings: [],
      tools: [],
    }
  },
  computed: {
    today() {
      return getTodayLocalISODate()
    },
    cards() {
      return [
        {
          label: 'Peminjaman Hari Ini',
          value: this.todayBorrowings.length,
          caption: 'Transaksi yang dibuat pada hari ini.',
          cardClass: 'bg-blue-100 dark:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40',
        },
        {
          label: 'Pengembalian Hari Ini',
          value: this.todayReturns.length,
          caption: 'Transaksi yang perlu atau sudah dicek.',
          cardClass: 'bg-green-100 dark:bg-green-900/40 border border-green-300 dark:border-green-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40',
        },
        {
          label: 'Pending Approval',
          value: this.pendingBorrowings.length,
          caption: 'Permintaan yang menunggu tindakan.',
          cardClass: 'bg-yellow-100 dark:bg-yellow-900/40 border border-yellow-300 dark:border-yellow-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40',
        },
      ]
    },
    todayBorrowings() {
      return this.borrowings.filter((item) => String(item.tanggalPinjam || '').startsWith(this.today))
    },
    pendingBorrowings() {
      return this.borrowings.filter((item) => String(item.status || '').toLowerCase() === 'pending')
    },
    todayReturns() {
      return this.borrowings.filter((item) => {
        const dueDate = String(item.tanggalKembaliRencana || '')
        const actualDate = String(item.tanggalKembaliAktual || '')
        const status = String(item.statusPengembalian || '').toLowerCase()

        return dueDate.startsWith(this.today) || actualDate.startsWith(this.today) || (status === 'belum dikembalikan' && String(item.status || '').toLowerCase() === 'dipinjam')
      })
    },
    recentTools() {
      return [...this.tools]
        .sort((left, right) => Number(right.id || 0) - Number(left.id || 0))
        .slice(0, 5)
    },
    recentBorrowings() {
      return [...this.borrowings]
        .sort((left, right) => new Date(right.updatedAt || right.createdAt || 0) - new Date(left.updatedAt || left.createdAt || 0))
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
  },
}
</script>
