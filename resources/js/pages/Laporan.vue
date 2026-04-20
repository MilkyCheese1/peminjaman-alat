<template>
  <div class="print-root min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <aside class="no-print">
      <SidebarStaff />
    </aside>

    <div class="flex-1 flex flex-col">
      <div class="no-print">
        <Navbar />
      </div>

      <main class="flex-1 p-6">
        <section class="no-print mb-8 flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
          <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Laporan Staff</h1>
            <p class="mt-2 text-slate-700 dark:text-slate-300">
              Rekap transaksi peminjaman dalam format harian, bulanan, tahunan, dan sepanjang waktu dengan form resmi siap cetak.
            </p>
          </div>

          <div class="flex flex-col gap-3 sm:flex-row">
            <router-link
              to="/management-peminjaman"
              class="inline-flex items-center justify-center rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
            >
              Kembali ke Management Peminjaman
            </router-link>
            <button
              type="button"
              class="inline-flex items-center justify-center rounded-full bg-cyan-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400"
              @click="printOfficialForm"
            >
              Cetak Form Resmi
            </button>
          </div>
        </section>

        <section class="no-print mb-6 app-card app-card--cyan p-6">
          <div class="flex flex-wrap gap-3">
            <button
              v-for="mode in reportModes"
              :key="mode.key"
              type="button"
              :class="[
                'rounded-full px-4 py-3 text-sm font-semibold transition',
                selectedMode === mode.key
                  ? 'bg-cyan-500 text-slate-950'
                  : 'border border-slate-300 text-slate-700 hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800',
              ]"
              @click="selectedMode = mode.key"
            >
              {{ mode.label }}
            </button>
          </div>

          <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-[minmax(0,1fr)_280px]">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">
              <p class="text-sm font-semibold text-slate-900 dark:text-white">Periode Aktif</p>
              <p class="mt-2 text-lg font-bold text-slate-900 dark:text-white">{{ reportLabel }}</p>
              <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                Filter laporan menggunakan tanggal pinjam sebagai dasar rekap transaksi staff.
              </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                {{ controlLabel }}
              </label>

              <input
                v-if="selectedMode === 'daily'"
                :value="dailyDate"
                type="date"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100"
                @input="dailyDate = $event.target.value"
              />

              <input
                v-else-if="selectedMode === 'monthly'"
                :value="monthlyValue"
                type="month"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100"
                @input="monthlyValue = $event.target.value"
              />

              <select
                v-else-if="selectedMode === 'yearly'"
                :value="selectedYear"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100"
                @change="selectedYear = $event.target.value"
              >
                <option v-for="year in availableYears" :key="year" :value="year">
                  {{ year }}
                </option>
              </select>

              <p v-else class="rounded-2xl border border-dashed border-slate-300 px-4 py-3 text-sm text-slate-600 dark:border-slate-700 dark:text-slate-300">
                Mode ini menampilkan seluruh riwayat transaksi yang tersimpan.
              </p>
            </div>
          </div>
        </section>

        <div class="no-print mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
          <article class="rounded-3xl border border-cyan-200 bg-cyan-50 p-5 text-cyan-950 shadow-2xl shadow-slate-950/20 dark:border-cyan-500/20 dark:bg-cyan-500/10 dark:text-cyan-100">
            <p class="text-sm font-medium opacity-80">Total Transaksi</p>
            <p class="mt-3 text-3xl font-bold">{{ reportSummary.total }}</p>
            <p class="mt-2 text-sm opacity-80">Jumlah transaksi dalam periode laporan aktif.</p>
          </article>

          <article class="rounded-3xl border border-emerald-200 bg-emerald-50 p-5 text-emerald-950 shadow-2xl shadow-slate-950/20 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-100">
            <p class="text-sm font-medium opacity-80">Selesai</p>
            <p class="mt-3 text-3xl font-bold">{{ reportSummary.completed }}</p>
            <p class="mt-2 text-sm opacity-80">Transaksi yang telah ditutup staff pada periode ini.</p>
          </article>

          <article class="rounded-3xl border border-amber-200 bg-amber-50 p-5 text-amber-950 shadow-2xl shadow-slate-950/20 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-100">
            <p class="text-sm font-medium opacity-80">Masih Aktif</p>
            <p class="mt-3 text-3xl font-bold">{{ reportSummary.active }}</p>
            <p class="mt-2 text-sm opacity-80">Termasuk pending, disetujui, dipinjam, atau dikembalikan.</p>
          </article>

          <article class="rounded-3xl border border-violet-200 bg-violet-50 p-5 text-violet-950 shadow-2xl shadow-slate-950/20 dark:border-violet-500/20 dark:bg-violet-500/10 dark:text-violet-100">
            <p class="text-sm font-medium opacity-80">Total Nilai</p>
            <p class="mt-3 text-3xl font-bold">{{ formatCompactCurrency(reportSummary.totalBiaya) }}</p>
            <p class="mt-2 text-sm opacity-80">Akumulasi nominal transaksi pada laporan terpilih.</p>
          </article>
        </div>

        <section class="print-area rounded-[2rem] border border-slate-200 bg-white p-8 shadow-2xl shadow-slate-950/20 dark:border-white/10 dark:bg-white">
          <div class="mb-8 flex items-start justify-between gap-6 border-b border-slate-200 pb-6">
            <div class="flex items-center gap-4">
              <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-900 text-lg font-bold text-white">
                SA
              </div>
              <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-500">Sistem Peminjaman Alat</p>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Form Laporan Resmi Staff</h2>
                <p class="mt-2 max-w-2xl text-sm text-slate-600">
                  Dokumen rekapitulasi transaksi peminjaman alat internal yang disusun untuk kebutuhan monitoring dan pelaporan operasional.
                </p>
              </div>
            </div>

            <div class="min-w-60 rounded-3xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
              <p><span class="font-semibold">No. Dokumen:</span> {{ reportDocumentNumber }}</p>
              <p class="mt-2"><span class="font-semibold">Jenis Laporan:</span> {{ activeModeLabel }}</p>
              <p class="mt-2"><span class="font-semibold">Periode:</span> {{ reportLabel }}</p>
              <p class="mt-2"><span class="font-semibold">Tanggal Cetak:</span> {{ printedAtLabel }}</p>
            </div>
          </div>

          <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
              <p class="text-sm text-slate-500">Total Transaksi</p>
              <p class="mt-2 text-2xl font-bold text-slate-900">{{ reportSummary.total }}</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
              <p class="text-sm text-slate-500">Selesai</p>
              <p class="mt-2 text-2xl font-bold text-slate-900">{{ reportSummary.completed }}</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
              <p class="text-sm text-slate-500">Masih Aktif</p>
              <p class="mt-2 text-2xl font-bold text-slate-900">{{ reportSummary.active }}</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
              <p class="text-sm text-slate-500">Total Nilai</p>
              <p class="mt-2 text-2xl font-bold text-slate-900">{{ formatRupiah(reportSummary.totalBiaya) }}</p>
            </div>
          </div>

          <div class="rounded-3xl border border-slate-200 p-5">
            <div class="mb-4 flex items-center justify-between gap-4">
              <div>
                <h3 class="text-xl font-bold text-slate-900">Rekap Transaksi</h3>
                <p class="mt-1 text-sm text-slate-600">
                  Daftar transaksi berdasarkan periode laporan aktif dan tanggal pinjam.
                </p>
              </div>
              <div class="rounded-2xl bg-slate-100 px-4 py-2 text-sm text-slate-700">
                Dicetak oleh: <span class="font-semibold">Staff Operasional</span>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="min-w-full border-collapse">
                <thead>
                  <tr class="bg-slate-100">
                    <th class="border border-slate-200 px-3 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-700">No</th>
                    <th class="border border-slate-200 px-3 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-700">Kode</th>
                    <th class="border border-slate-200 px-3 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-700">Peminjam</th>
                    <th class="border border-slate-200 px-3 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-700">Alat</th>
                    <th class="border border-slate-200 px-3 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-700">Tanggal</th>
                    <th class="border border-slate-200 px-3 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-700">Status</th>
                    <th class="border border-slate-200 px-3 py-3 text-right text-xs font-semibold uppercase tracking-[0.2em] text-slate-700">Denda</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in reportItems" :key="item.id">
                    <td class="border border-slate-200 px-3 py-3 text-sm text-slate-700">{{ index + 1 }}</td>
                    <td class="border border-slate-200 px-3 py-3 text-sm text-slate-700">
                      <div class="font-semibold">{{ item.kode }}</div>
                      <div class="mt-1 text-xs text-slate-500">{{ item.petugas }}</div>
                    </td>
                    <td class="border border-slate-200 px-3 py-3 text-sm text-slate-700">
                      <div class="font-semibold">{{ item.namaPeminjam }}</div>
                      <div class="mt-1 text-xs text-slate-500">{{ item.divisi }}</div>
                    </td>
                    <td class="border border-slate-200 px-3 py-3 text-sm text-slate-700">
                      <div class="font-semibold">{{ item.namaAlat }}</div>
                      <div class="mt-1 text-xs text-slate-500">{{ item.kategori }}</div>
                    </td>
                    <td class="border border-slate-200 px-3 py-3 text-sm text-slate-700">
                      <div>Pinjam: {{ formatDateIndonesia(item.tanggalPinjam) }}</div>
                      <div class="mt-1 text-xs text-slate-500">Kembali: {{ formatDateIndonesia(item.tanggalKembaliAktual || item.tanggalKembaliRencana) }}</div>
                    </td>
                    <td class="border border-slate-200 px-3 py-3 text-sm text-slate-700">{{ item.status }}</td>
                    <td class="border border-slate-200 px-3 py-3 text-right text-sm text-slate-700">{{ formatRupiah(item.biaya) }}</td>
                  </tr>
                  <tr v-if="!reportItems.length">
                    <td colspan="7" class="border border-slate-200 px-3 py-8 text-center text-sm text-slate-500">
                      Tidak ada data transaksi untuk periode laporan ini.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
              <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Catatan Laporan</h4>
              <p class="mt-3 text-sm leading-7 text-slate-700">
                Laporan ini dibuat berdasarkan data transaksi peminjaman yang tersimpan pada browser staff dan digunakan untuk evaluasi operasional harian serta arsip internal.
              </p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
              <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Distribusi Status</h4>
              <div class="mt-3 space-y-2 text-sm text-slate-700">
                <div class="flex items-center justify-between">
                  <span>Pending</span>
                  <span class="font-semibold">{{ statusDistribution.Pending }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span>Disetujui</span>
                  <span class="font-semibold">{{ statusDistribution.Disetujui }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span>Dipinjam</span>
                  <span class="font-semibold">{{ statusDistribution.Dipinjam }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span>Dikembalikan</span>
                  <span class="font-semibold">{{ statusDistribution.Dikembalikan }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span>Selesai</span>
                  <span class="font-semibold">{{ statusDistribution.Selesai }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span>Ditolak</span>
                  <span class="font-semibold">{{ statusDistribution.Ditolak }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-10 grid grid-cols-1 gap-10 md:grid-cols-2">
            <div class="text-center">
              <p class="text-sm text-slate-600">Disusun oleh,</p>
              <div class="h-24"></div>
              <p class="font-semibold text-slate-900">Staff Operasional</p>
              <p class="text-sm text-slate-500">Pengelola Peminjaman Alat</p>
            </div>
            <div class="text-center">
              <p class="text-sm text-slate-600">Mengetahui,</p>
              <div class="h-24"></div>
              <p class="font-semibold text-slate-900">Koordinator Operasional</p>
              <p class="text-sm text-slate-500">Supervisor Unit</p>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import SidebarStaff from '../components/layout/SidebarStaff.vue'
import Navbar from '../components/layout/Navbar.vue'
import {
  formatDateIndonesia,
  formatRupiah,
  getStaffBorrowings,
  staffReportReferenceDate,
} from '../data/staffBorrowing'

const items = ref([])
const selectedMode = ref('daily')
const dailyDate = ref(staffReportReferenceDate)
const monthlyValue = ref(staffReportReferenceDate.slice(0, 7))
const selectedYear = ref(staffReportReferenceDate.slice(0, 4))

const reportModes = [
  { key: 'daily', label: 'Harian' },
  { key: 'monthly', label: 'Bulanan' },
  { key: 'yearly', label: 'Tahunan' },
  { key: 'all-time', label: 'Sepanjang Waktu' },
]

onMounted(() => {
  items.value = getStaffBorrowings()
})

const availableYears = computed(() => {
  const years = new Set(items.value.map((item) => String(item.tanggalPinjam).slice(0, 4)))
  years.add(staffReportReferenceDate.slice(0, 4))
  return Array.from(years).sort((left, right) => Number(right) - Number(left))
})

const reportItems = computed(() => {
  return items.value.filter((item) => {
    const pinjamDate = String(item.tanggalPinjam)

    if (selectedMode.value === 'daily') {
      return pinjamDate === dailyDate.value
    }

    if (selectedMode.value === 'monthly') {
      return pinjamDate.startsWith(monthlyValue.value)
    }

    if (selectedMode.value === 'yearly') {
      return pinjamDate.startsWith(selectedYear.value)
    }

    return true
  })
})

const reportSummary = computed(() => {
  const totalBiaya = reportItems.value.reduce((total, item) => total + Number(item.biaya || 0), 0)
  const completed = reportItems.value.filter((item) => item.status === 'Selesai').length
  const active = reportItems.value.filter((item) => ['Pending', 'Disetujui', 'Dipinjam', 'Dikembalikan'].includes(item.status)).length

  return {
    total: reportItems.value.length,
    completed,
    active,
    totalBiaya,
  }
})

const statusDistribution = computed(() =>
  reportItems.value.reduce(
    (accumulator, item) => {
      accumulator[item.status] = (accumulator[item.status] || 0) + 1
      return accumulator
    },
    {
      Pending: 0,
      Disetujui: 0,
      Dipinjam: 0,
      Dikembalikan: 0,
      Selesai: 0,
      Ditolak: 0,
    },
  ),
)

const controlLabel = computed(() => {
  const labels = {
    daily: 'Pilih Tanggal',
    monthly: 'Pilih Bulan',
    yearly: 'Pilih Tahun',
    'all-time': 'Mode Sepanjang Waktu',
  }

  return labels[selectedMode.value]
})

const activeModeLabel = computed(() => {
  const currentMode = reportModes.find((mode) => mode.key === selectedMode.value)
  return currentMode?.label ?? 'Laporan'
})

const reportLabel = computed(() => {
  if (selectedMode.value === 'daily') {
    return formatDateIndonesia(dailyDate.value)
  }

  if (selectedMode.value === 'monthly') {
    return formatMonthYear(monthlyValue.value)
  }

  if (selectedMode.value === 'yearly') {
    return `Tahun ${selectedYear.value}`
  }

  return 'Seluruh riwayat transaksi'
})

const reportDocumentNumber = computed(() => {
  const suffix = {
    daily: dailyDate.value.replaceAll('-', ''),
    monthly: monthlyValue.value.replace('-', ''),
    yearly: selectedYear.value,
    'all-time': 'ALL',
  }

  return `LAP-STF/${suffix[selectedMode.value]}/${String(reportItems.value.length).padStart(3, '0')}`
})

const printedAtLabel = computed(() => formatDateIndonesia(staffReportReferenceDate))

function formatMonthYear(value) {
  if (!value) {
    return '-'
  }

  return new Intl.DateTimeFormat('id-ID', {
    month: 'long',
    year: 'numeric',
  }).format(new Date(`${value}-01`))
}

function formatCompactCurrency(value) {
  const amount = Number(value || 0)

  if (amount >= 1000000) {
    return `Rp ${(amount / 1000000).toFixed(1)} jt`
  }

  if (amount >= 1000) {
    return `Rp ${(amount / 1000).toFixed(0)} rb`
  }

  return `Rp ${amount}`
}

function printOfficialForm() {
  if (typeof window !== 'undefined') {
    window.print()
  }
}
</script>

<style scoped>
@media print {
  .no-print {
    display: none !important;
  }

  .print-root {
    display: block !important;
    min-height: auto !important;
    background: white !important;
  }

  .print-area {
    border: none !important;
    box-shadow: none !important;
    border-radius: 0 !important;
    padding: 0 !important;
  }

  main {
    padding: 0 !important;
  }
}
</style>
