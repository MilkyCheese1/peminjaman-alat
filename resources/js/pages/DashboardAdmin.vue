<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <SidebarAdmin />
    <div class="flex-1 flex flex-col">
      <Navbar />

      <main class="flex-1 p-6">
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Dashboard Admin</h1>
          <p class="text-slate-700 dark:text-slate-300">Kelola data sistem peminjaman alat</p>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-4 mb-8">
          <article v-for="card in cards" :key="card.label" :class="card.cardClass">
            <p class="text-sm font-medium opacity-80">{{ card.label }}</p>
            <p class="mt-3 text-3xl font-bold">{{ card.value }}</p>
            <p class="mt-2 text-sm opacity-80">{{ card.caption }}</p>
          </article>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <router-link to="/management-alat" class="app-card app-card--cyan p-4 transition duration-300 hover:bg-cyan-100 dark:hover:bg-cyan-500/15 block">
            <svg class="mb-2 h-8 w-8 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Management Alat
          </router-link>
          <router-link to="/management-kategori" class="app-card app-card--cyan p-4 transition duration-300 hover:bg-cyan-100 dark:hover:bg-cyan-500/15 block">
            <svg class="mb-2 h-8 w-8 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            Management Kategori
          </router-link>
          <router-link to="/management-user" class="app-card app-card--cyan p-4 transition duration-300 hover:bg-cyan-100 dark:hover:bg-cyan-500/15 block">
            <svg class="mb-2 h-8 w-8 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Management User
          </router-link>
          <router-link to="/laporan-admin" class="app-card app-card--cyan p-4 transition duration-300 hover:bg-cyan-100 dark:hover:bg-cyan-500/15 block">
            <svg class="mb-2 h-8 w-8 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Laporan
          </router-link>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
          <section class="app-card app-card--cyan overflow-hidden">
            <div class="border-b border-slate-200 p-6 dark:border-slate-800">
              <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Manajemen Alat</h2>
            </div>
            <div class="p-6">
              <div v-if="loading" class="rounded-2xl bg-slate-100 p-4 text-sm text-slate-600 dark:bg-slate-800/50 dark:text-slate-300">
                Memuat data alat...
              </div>
              <div v-else class="overflow-x-auto">
                <table class="w-full min-w-[560px]">
                  <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800">
                      <th class="px-4 py-2 text-left font-semibold text-slate-700 dark:text-slate-300">Nama Alat</th>
                      <th class="px-4 py-2 text-left font-semibold text-slate-700 dark:text-slate-300">Kategori</th>
                      <th class="px-4 py-2 text-left font-semibold text-slate-700 dark:text-slate-300">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-if="!recentTools.length">
                      <td colspan="3" class="px-4 py-10 text-center text-sm text-slate-600 dark:text-slate-400">
                        Belum ada data alat.
                      </td>
                    </tr>
                    <tr v-for="item in recentTools" :key="item.id" class="border-b border-slate-200 dark:border-slate-800">
                      <td class="px-4 py-4 text-sm font-semibold text-slate-900 dark:text-white">{{ item.namaAlat || item.nama_alat || '-' }}</td>
                      <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-200">{{ item.kategori || '-' }}</td>
                      <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-200">{{ item.status || '-' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </section>

          <section class="app-card app-card--cyan overflow-hidden">
            <div class="border-b border-slate-200 p-6 dark:border-slate-800">
              <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Manajemen Pengguna</h2>
            </div>
            <div class="p-6">
              <div v-if="loading" class="rounded-2xl bg-slate-100 p-4 text-sm text-slate-600 dark:bg-slate-800/50 dark:text-slate-300">
                Memuat data pengguna...
              </div>
              <div v-else class="overflow-x-auto">
                <table class="w-full min-w-[560px]">
                  <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800">
                      <th class="px-4 py-2 text-left font-semibold text-slate-700 dark:text-slate-300">Nama</th>
                      <th class="px-4 py-2 text-left font-semibold text-slate-700 dark:text-slate-300">Role</th>
                      <th class="px-4 py-2 text-left font-semibold text-slate-700 dark:text-slate-300">Email</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-if="!recentUsers.length">
                      <td colspan="3" class="px-4 py-10 text-center text-sm text-slate-600 dark:text-slate-400">
                        Belum ada data pengguna.
                      </td>
                    </tr>
                    <tr v-for="item in recentUsers" :key="item.id" class="border-b border-slate-200 dark:border-slate-800">
                      <td class="px-4 py-4 text-sm font-semibold text-slate-900 dark:text-white">{{ item.nama || item.name || '-' }}</td>
                      <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-200">{{ item.role || '-' }}</td>
                      <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-200">{{ item.email || '-' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </section>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
          <section class="app-card app-card--cyan p-6">
            <div class="mb-4 flex items-center justify-between gap-3">
              <h2 class="text-xl font-bold text-slate-900 dark:text-white">Transaksi Terkini</h2>
              <router-link
                to="/laporan-admin"
                class="inline-flex items-center justify-center rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
              >
                Buka Laporan
              </router-link>
            </div>

            <div v-if="recentBorrowings.length" class="space-y-3">
              <article
                v-for="item in recentBorrowings"
                :key="item.id"
                class="rounded-2xl bg-slate-100 p-4 text-sm text-slate-700 dark:bg-slate-800/50 dark:text-slate-300"
              >
                <p class="font-semibold text-slate-900 dark:text-white">{{ item.kode }}</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                  {{ item.namaPeminjam }} - {{ item.namaAlat }} - {{ item.status }}
                </p>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                  {{ formatDateTime(item.updatedAt || item.createdAt) }}
                </p>
              </article>
            </div>
            <div v-else class="rounded-2xl bg-slate-100 p-4 text-sm text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
              Belum ada transaksi terbaru.
            </div>
          </section>

          <section class="app-card app-card--cyan p-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Aktivitas Terbaru</h2>
            <div v-if="recentActivities.length" class="space-y-3">
              <article
                v-for="item in recentActivities"
                :key="item.id"
                class="rounded-2xl bg-slate-100 p-4 text-sm text-slate-700 dark:bg-slate-800/50 dark:text-slate-300"
              >
                <p class="font-semibold text-slate-900 dark:text-white">{{ item.deskripsi || item.aksi || 'Aktivitas' }}</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                  {{ item.aksi || '-' }} · {{ item.entitas || '-' }} · {{ formatDateTime(item.createdAt) }}
                </p>
              </article>
            </div>
            <div v-else class="rounded-2xl bg-slate-100 p-4 text-sm text-slate-700 dark:bg-slate-800/50 dark:text-slate-300">
              Belum ada aktivitas.
            </div>
          </section>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import SidebarAdmin from '../components/layout/SidebarAdmin.vue'
import Navbar from '../components/layout/Navbar.vue'
import { apiRequest } from '../lib/api'
import { calculateBorrowingFine } from '../data/returnFine'
import { getTodayLocalISODate } from '../data/staffBorrowing'

export default {
  name: 'DashboardAdmin',
  components: {
    SidebarAdmin,
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
          label: 'Total Pengguna',
          value: this.users.length,
          caption: 'Data pengguna yang tersimpan di database.',
          cardClass: 'bg-blue-100 dark:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40',
        },
        {
          label: 'Total Alat',
          value: this.tools.length,
          caption: 'Seluruh inventaris yang aktif.',
          cardClass: 'bg-green-100 dark:bg-green-900/40 border border-green-300 dark:border-green-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40',
        },
        {
          label: 'Total Peminjaman',
          value: this.borrowings.length,
          caption: 'Semua transaksi yang tercatat.',
          cardClass: 'bg-purple-100 dark:bg-purple-900/40 border border-purple-300 dark:border-purple-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40',
        },
        {
          label: 'Terlambat Aktif',
          value: this.overdueBorrowings.length,
          caption: 'Transaksi yang butuh tindak lanjut.',
          cardClass: 'bg-red-100 dark:bg-red-900/40 border border-red-300 dark:border-red-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40',
        },
      ]
    },
    overdueBorrowings() {
      return this.borrowings.filter((item) => {
        const status = String(item.status || '').toLowerCase()
        if (!['pending', 'disetujui', 'dipinjam'].includes(status) && item.statusPengembalian !== 'Belum Dikembalikan') {
          return false
        }

        const preview = calculateBorrowingFine({
          price: Number(item.alatHargaAsli || item.alat_harga_asli || 0),
          dueDate: item.tanggalKembaliRencana,
          actualDate: getTodayLocalISODate(),
          statusPengembalian: item.statusPengembalian || 'Belum Dikembalikan',
          kondisiPengembalian: item.kondisiPengembalian || 'Normal',
        })

        return preview.daysLate > 0
      })
    },
    recentTools() {
      return [...this.tools]
        .sort((left, right) => Number(right.id || 0) - Number(left.id || 0))
        .slice(0, 5)
    },
    recentUsers() {
      return [...this.users]
        .sort((left, right) => Number(right.id || 0) - Number(left.id || 0))
        .slice(0, 5)
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
