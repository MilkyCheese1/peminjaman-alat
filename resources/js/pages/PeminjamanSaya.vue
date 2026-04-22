<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col lg:flex-row">
    <SidebarPeminjam />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-4 sm:p-6">
        <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Peminjaman Saya</h1>
            <p class="text-slate-700 dark:text-slate-300">Lihat riwayat dan status peminjaman alat Anda.</p>
          </div>
          <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600 shadow-sm dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">
            {{ userLabel }}
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="app-card app-card--cyan p-6">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Ringkasan Peminjaman</h2>
            <div class="space-y-4 text-slate-700 dark:text-slate-300">
              <div class="rounded-3xl bg-slate-200 dark:bg-slate-800 p-4">
                <p class="text-sm">Peminjaman Aktif</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ activeBorrowingsCount }}</p>
              </div>
              <div class="rounded-3xl bg-slate-200 dark:bg-slate-800 p-4">
                <p class="text-sm">Total Denda</p>
                <p class="mt-2 text-3xl font-semibold text-cyan-600 dark:text-cyan-300">{{ formatCurrency(totalFine) }}</p>
              </div>
            </div>
          </div>

          <div class="app-card app-card--cyan p-6">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Status Terbaru</h2>
            <div class="rounded-3xl bg-slate-200 dark:bg-slate-800 p-4 text-slate-700 dark:text-slate-300">
              <template v-if="latestBorrowing">
                <p class="font-semibold text-slate-900 dark:text-white">{{ latestBorrowing.namaAlat }}</p>
                <p class="mt-2 text-sm">{{ latestBorrowing.status }}</p>
              </template>
              <template v-else>
                Belum ada data peminjaman.
              </template>
            </div>
          </div>

          <div class="app-card app-card--cyan p-6">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Total Riwayat</h2>
            <div class="rounded-3xl bg-slate-200 dark:bg-slate-800 p-4">
              <p class="text-sm text-slate-700 dark:text-slate-300">Seluruh transaksi milik akun ini</p>
              <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ borrowings.length }}</p>
            </div>
          </div>
        </div>

        <section>
          <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Riwayat Peminjaman</h2>
            <p class="text-slate-600 dark:text-slate-400">Daftar peminjaman alat yang pernah Anda lakukan.</p>
          </div>
          <div class="app-card app-card--cyan overflow-hidden">
            <div v-if="loading" class="p-10 text-center text-sm text-slate-600 dark:text-slate-400">
              Memuat riwayat peminjaman...
            </div>
            <div v-else class="overflow-x-auto">
              <table class="w-full min-w-[720px]">
                <thead class="bg-slate-100 dark:bg-slate-800">
                  <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.16em] text-slate-700 dark:text-slate-300">Alat</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.16em] text-slate-700 dark:text-slate-300">Tanggal Pinjam</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.16em] text-slate-700 dark:text-slate-300">Tanggal Kembali</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.16em] text-slate-700 dark:text-slate-300">Status</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.16em] text-slate-700 dark:text-slate-300">Total</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-slate-700 dark:text-slate-200">
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
                    <td class="px-6 py-4 text-sm">
                      <span class="rounded-full px-3 py-1 text-xs font-semibold" :class="statusClass(item.status)">
                        {{ item.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-white">{{ formatCurrency(item.biaya) }}</td>
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
import SidebarPeminjam from '../components/layout/SidebarPeminjam.vue'
import Navbar from '../components/layout/Navbar.vue'
import { apiRequest } from '../lib/api'
import { getAuthSession } from '../auth/session'

export default {
  name: 'PeminjamanSaya',
  components: {
    SidebarPeminjam,
    Navbar,
  },
  data() {
    return {
      loading: false,
      items: [],
    }
  },
  computed: {
    session() {
      return getAuthSession()
    },
    userLabel() {
      if (!this.session) {
        return 'Belum login'
      }

      return `Akun: ${this.session.nama || this.session.email || 'Peminjam'}`
    },
    borrowings() {
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
    activeBorrowingsCount() {
      return this.borrowings.filter((item) => ['Pending', 'Disetujui', 'Dipinjam'].includes(item.status)).length
    },
    totalFine() {
      return this.borrowings.reduce((sum, item) => sum + Number(item.biaya || 0), 0)
    },
    latestBorrowing() {
      return this.borrowings[0] || null
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
        this.items = Array.isArray(data) ? data : []
      } catch (error) {
        this.items = []
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
    statusClass(status) {
      const key = String(status || '').toLowerCase()
      if (key === 'pending') return 'bg-amber-100 text-amber-800 dark:bg-amber-500/15 dark:text-amber-200'
      if (key === 'disetujui') return 'bg-cyan-100 text-cyan-800 dark:bg-cyan-500/15 dark:text-cyan-200'
      if (key === 'dipinjam') return 'bg-violet-100 text-violet-800 dark:bg-violet-500/15 dark:text-violet-200'
      if (key === 'dikembalikan' || key === 'selesai') return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-200'
      if (key === 'ditolak') return 'bg-rose-100 text-rose-800 dark:bg-rose-500/15 dark:text-rose-200'
      return 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200'
    },
  },
}
</script>

<style scoped>
/* Custom styles if needed */
</style>
