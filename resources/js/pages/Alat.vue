<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col lg:flex-row">
    <SidebarPeminjam />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-4 sm:p-6">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Jelajahi Alat</h1>
          <p class="text-slate-700 dark:text-slate-300">Temukan alat yang tersedia untuk peminjaman.</p>
        </div>

        <div class="mb-8">
          <div class="flex gap-4 mb-6">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari alat..."
              class="flex-1 rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-500 focus:border-cyan-500 focus:ring-cyan-500/20 focus:outline-none"
            />
          </div>
          <div class="app-card app-card--cyan p-6">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-6">Filter Alat</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm mb-2 text-slate-700 dark:text-slate-300">Kategori</label>
                <select
                  v-model="selectedCategory"
                  class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 text-slate-900 dark:text-slate-100 focus:border-cyan-500 focus:ring-cyan-500/20 focus:outline-none"
                >
                  <option value="">Semua Kategori</option>
                  <option v-for="category in categories" :key="category.id" :value="category.namaKategori">
                    {{ category.namaKategori }}
                  </option>
                </select>
              </div>
              <div>
                <label class="block text-sm mb-2 text-slate-700 dark:text-slate-300">Status</label>
                <select
                  v-model="selectedStatus"
                  class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 text-slate-900 dark:text-slate-100 focus:border-cyan-500 focus:ring-cyan-500/20 focus:outline-none"
                >
                  <option value="">Semua Status</option>
                  <option value="Tersedia">Tersedia</option>
                  <option value="Dipinjam">Dipinjam</option>
                  <option value="Maintenance">Maintenance</option>
                </select>
              </div>
            </div>
            <div class="mt-6 flex gap-3">
              <button
                type="button"
                class="flex-1 inline-flex items-center justify-center rounded-full bg-cyan-500 px-5 py-3 text-sm font-semibold text-white dark:text-slate-950 transition hover:bg-cyan-600 dark:hover:bg-cyan-400"
                @click="applyFilters"
              >
                Terapkan Filter
              </button>
              <button
                type="button"
                class="flex-1 inline-flex items-center justify-center rounded-full bg-slate-300 dark:bg-slate-800 px-5 py-3 text-sm font-semibold text-slate-900 dark:text-slate-200 transition hover:bg-slate-400 dark:hover:bg-slate-700"
                @click="resetFilters"
              >
                Reset
              </button>
            </div>
          </div>
        </div>

        <section>
          <div class="flex items-center justify-between mb-6">
            <div>
              <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Daftar Alat Tersedia</h2>
              <p class="text-slate-600 dark:text-slate-400">Pilih alat yang ingin Anda pinjam dan lihat detailnya.</p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <ToolCard
              v-for="tool in displayedTools"
              :key="tool.id"
              :tool="tool"
              @borrow="handleBorrow"
              @detail="handleDetail"
            />
          </div>

          <div v-if="!displayedTools.length" class="mt-8 rounded-3xl border border-dashed border-slate-300 px-6 py-12 text-center dark:border-slate-700">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Belum ada data alat</h3>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
              Data alat akan muncul setelah ditambahkan oleh admin melalui Management Alat.
            </p>
          </div>
        </section>

        <BorrowToolModal
          :open="isBorrowModalOpen"
          :tool="selectedTool"
          @close="closeBorrowModal"
          @submit="submitBorrowRequest"
        />
      </main>
    </div>
  </div>
</template>

<script>
import SidebarPeminjam from '../components/layout/SidebarPeminjam.vue';
import Navbar from '../components/layout/Navbar.vue';
import ToolCard from '../components/tools/ToolCard.vue'
import BorrowToolModal from '../components/tools/BorrowToolModal.vue'
import { apiRequest } from '../lib/api'
import { getAuthSession } from '../auth/session'
import { getTodayLocalISODate } from '../data/staffBorrowing'

export default {
  name: 'Alat',
  components: {
    SidebarPeminjam,
    Navbar,
    ToolCard,
    BorrowToolModal,
  },
  data() {
    return {
      tools: [],
      categories: [],
      searchQuery: '',
      selectedCategory: '',
      selectedStatus: '',
      appliedCategory: '',
      appliedStatus: '',
      loading: false,
      error: null,
      isBorrowModalOpen: false,
      selectedTool: null,
    }
  },
  computed: {
    displayedTools() {
      const keyword = String(this.searchQuery || '').trim().toLowerCase()
      const category = String(this.appliedCategory || '').trim()
      const status = String(this.appliedStatus || '').trim()

      return (Array.isArray(this.tools) ? this.tools : [])
        .filter((tool) => {
          if (category && tool.kategori !== category) return false
          if (status && tool.status !== status) return false
          if (!keyword) return true

          return [tool.namaAlat, tool.kategori, tool.status, tool.kondisi, tool.lokasi]
            .some((value) => String(value ?? '').toLowerCase().includes(keyword))
        })
    },
  },
  async created() {
    await this.loadData()
  },
  methods: {
    async loadData() {
      this.loading = true
      this.error = null

      try {
        const [tools, categories] = await Promise.all([
          apiRequest('/api/tools'),
          apiRequest('/api/categories?status=Aktif'),
        ])

        this.tools = Array.isArray(tools) ? tools : []
        this.categories = Array.isArray(categories) ? categories : []
      } catch (error) {
        this.tools = []
        this.categories = []
        this.error = error?.message || 'Gagal memuat data alat.'
      } finally {
        this.loading = false
      }
    },
    applyFilters() {
      this.appliedCategory = this.selectedCategory
      this.appliedStatus = this.selectedStatus
    },
    resetFilters() {
      this.searchQuery = ''
      this.selectedCategory = ''
      this.selectedStatus = ''
      this.appliedCategory = ''
      this.appliedStatus = ''
    },
    handleBorrow(tool) {
      this.selectedTool = tool ?? null
      this.isBorrowModalOpen = true
    },
    handleDetail(tool) {
      if (typeof window === 'undefined') {
        return
      }

      window.alert(
        [
          `Nama: ${tool?.namaAlat ?? '-'}`,
          `Kategori: ${tool?.kategori ?? '-'}`,
          `Status: ${tool?.status ?? '-'}`,
          `Kondisi: ${tool?.kondisi ?? '-'}`,
          `Stok: ${tool?.stok ?? 0}`,
          `Lokasi: ${tool?.lokasi ?? '-'}`,
        ].join('\n'),
      )
    },
    closeBorrowModal() {
      this.isBorrowModalOpen = false
      this.selectedTool = null
    },
    async submitBorrowRequest(payload) {
      if (typeof window === 'undefined') {
        this.closeBorrowModal()
        return
      }

      try {
        const session = getAuthSession()
        const borrowerName = String(session?.nama || session?.email || 'Peminjam').trim()
        const borrowerDivision = 'Internal'
        const today = getTodayLocalISODate()
        const hari = Number(payload?.hari || 0)
        const tglKembaliRencana = new Date(`${today}T00:00:00`)
        tglKembaliRencana.setDate(tglKembaliRencana.getDate() + Math.max(1, hari))
        const normalizedRencana = new Date(tglKembaliRencana.getTime() - tglKembaliRencana.getTimezoneOffset() * 60 * 1000)
        const tanggalKembaliRencana = normalizedRencana.toISOString().slice(0, 10)

        await apiRequest('/api/borrowings', {
          method: 'POST',
          body: {
            peminjamId: session?.id ?? null,
            namaPeminjam: borrowerName,
            divisi: borrowerDivision,
            namaAlat: this.selectedTool?.namaAlat,
            kategori: this.selectedTool?.kategori,
            tanggalPinjam: today,
            tanggalKembaliRencana,
            tanggalKembaliAktual: null,
            status: 'Pending',
            petugas: 'Belum Ditugaskan',
            keperluan: payload?.alasan,
            biaya: 0,
            catatan: '',
          },
        })

        window.alert('Pengajuan peminjaman berhasil dikirim. Silakan tunggu staff memproses permintaan Anda.')
      } catch (error) {
        window.alert(error?.message || 'Gagal mengirim pengajuan peminjaman.')
      } finally {
        this.closeBorrowModal()
      }
    },
  },
};
</script>

<style scoped>
/* Custom styles if needed */
</style>
