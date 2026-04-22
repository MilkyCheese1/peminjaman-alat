<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <SidebarStaff />

    <div class="flex-1 flex flex-col">
      <Navbar />

      <main class="flex-1 p-6">
        <div class="mb-8 flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
          <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Pengembalian</h1>
            <p class="mt-2 text-slate-700 dark:text-slate-300">
              Staff dapat mengonfirmasi barang sudah kembali atau belum, lalu sistem menghitung denda secara otomatis.
            </p>
          </div>

        </div>

        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
          <article class="app-card app-card--cyan p-6">
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Perlu Dicek</p>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ awaitingCount }}</p>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Transaksi yang masih berstatus dipinjam.</p>
          </article>

          <article class="app-card app-card--cyan p-6">
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Terlambat</p>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ overdueCount }}</p>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Pengembalian yang melewati tanggal rencana.</p>
          </article>

          <article class="app-card app-card--cyan p-6">
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Denda Terhitung</p>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ formatCurrency(totalFine) }}</p>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Akumulasi denda dari data yang sedang aktif.</p>
          </article>

          <article class="app-card app-card--cyan p-6">
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Sudah Dikonfirmasi</p>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ confirmedCount }}</p>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Transaksi yang sudah melalui proses pengembalian.</p>
          </article>
        </div>

        <section class="app-card app-card--cyan p-6">
          <div :class="['mb-5 rounded-2xl border px-4 py-3 text-sm', feedbackClass]">
            {{ feedback.text }}
          </div>

          <div class="mb-5 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
              <h2 class="text-xl font-bold text-slate-900 dark:text-white">Daftar Pengembalian</h2>
              <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Klik salah satu transaksi untuk mengisi konfirmasi pengembalian dan laporan kondisi barang.
              </p>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full">
              <thead>
                <tr class="border-b border-slate-200 dark:border-slate-800">
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Kode</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Peminjam</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Alat</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Jatuh Tempo</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Status Pengembalian</th>
                  <th class="px-4 py-3 text-right text-sm font-semibold text-slate-700 dark:text-slate-300">Denda</th>
                  <th class="px-4 py-3 text-right text-sm font-semibold text-slate-700 dark:text-slate-300">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="item in filteredBorrowings"
                  :key="item.id"
                  class="border-b border-slate-200 align-top transition hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800/40"
                >
                  <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-200">
                    <div class="font-semibold">{{ item.kode }}</div>
                    <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ item.petugas }}</div>
                  </td>
                  <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-200">
                    <div class="font-semibold">{{ item.namaPeminjam }}</div>
                    <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ item.divisi }}</div>
                  </td>
                  <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-200">
                    <div class="font-semibold">{{ item.namaAlat }}</div>
                    <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ item.kategori }}</div>
                  </td>
                  <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-200">
                    <div>Rencana: {{ formatDateIndonesia(item.tanggalKembaliRencana) }}</div>
                    <div class="mt-1">Aktual: {{ formatDateIndonesia(item.tanggalKembaliAktual) }}</div>
                    <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ formatLateLabel(item) }}</div>
                  </td>
                  <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-200">
                    <span
                      :class="[
                        'inline-flex items-center gap-2 text-xs font-semibold tracking-wide',
                        resolveReturnStatusBadge(item).toneClass,
                      ]"
                    >
                      <span :class="resolveReturnStatusBadge(item).iconClass" aria-hidden="true"></span>
                      {{ resolveReturnStatusBadge(item).label }}
                    </span>
                  </td>
                  <td class="px-4 py-4 text-right text-sm text-slate-700 dark:text-slate-200">
                    {{ formatCurrency(item.biaya) }}
                  </td>
                  <td class="px-4 py-4">
                    <div class="flex flex-wrap justify-end gap-2">
                      <button
                        type="button"
                        class="rounded-full border border-cyan-200 px-3 py-2 text-xs font-semibold text-cyan-700 transition hover:bg-cyan-50 dark:border-cyan-500/20 dark:text-cyan-200 dark:hover:bg-cyan-500/10"
                        @click="openReview(item)"
                      >
                        Konfirmasi
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>

            <div
              v-if="!filteredBorrowings.length"
              class="rounded-3xl border border-dashed border-slate-300 px-6 py-12 text-center dark:border-slate-700"
            >
              <h3 class="text-lg font-bold text-slate-900 dark:text-white">Tidak ada data pengembalian</h3>
              <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                Semua transaksi yang perlu diverifikasi akan muncul di sini.
              </p>
            </div>
          </div>
        </section>
      </main>
    </div>

    <div
      v-if="isModalOpen"
      class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4"
      @click.self="closeReview"
    >
      <section class="flex max-h-[90vh] w-full max-w-4xl flex-col overflow-hidden rounded-[2rem] border border-cyan-200 bg-white shadow-2xl shadow-slate-950/40 dark:border-cyan-500/20 dark:bg-slate-900">
        <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
          <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-600 dark:text-cyan-300">Form Pengembalian</p>
            <h2 class="mt-2 text-xl font-bold text-slate-900 dark:text-white">{{ selectedBorrowing?.kode || 'Transaksi' }}</h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
              Isi status pengembalian dan laporan kondisi barang dari peminjam.
            </p>
          </div>

          <button
            type="button"
            class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
            @click="closeReview"
          >
            Tutup
          </button>
        </div>

        <div class="min-h-0 flex-1 overflow-y-auto grid grid-cols-1 gap-0 lg:grid-cols-2">
          <section class="border-b border-slate-200 px-6 py-6 dark:border-slate-800 lg:border-b-0 lg:border-r">
            <div class="rounded-3xl bg-slate-100 px-5 py-4 dark:bg-slate-800/60">
              <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Status Saat Dipinjam</p>
              <p class="mt-2 text-lg font-bold text-slate-900 dark:text-white">{{ selectedBorrowing?.status || 'Dipinjam' }}</p>
              <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                Status ini menandai bahwa barang sedang berada di tangan peminjam.
              </p>
            </div>

            <div class="rounded-3xl bg-slate-100 px-5 py-4 dark:bg-slate-800/60">
              <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Ringkasan Denda</p>
              <div class="mt-3 space-y-1 text-sm text-slate-700 dark:text-slate-200">
                <p>Kerusakan: {{ formatCurrency(previewFine.dendaKerusakan) }}</p>
                <p>Kehilangan: {{ formatCurrency(previewFine.dendaKehilangan) }}</p>
                <p>Keterlambatan: {{ formatCurrency(previewFine.dendaKeterlambatan) }}</p>
                <p class="pt-2 text-base font-bold text-slate-900 dark:text-white">Total: {{ formatCurrency(previewFine.total) }}</p>
              </div>
            </div>

            <div class="mt-5 rounded-3xl bg-slate-100 px-5 py-4 dark:bg-slate-800/60">
              <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Harga Asli Alat</p>
              <p class="mt-2 text-lg font-bold text-slate-900 dark:text-white">{{ formatCurrency(selectedBorrowingPrice) }}</p>
              <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                Denda kerusakan = 1x harga asli. Denda kehilangan = 1,5x harga asli. Denda terlambat = 0,5x harga asli per hari, lalu 1x harga asli per hari setelah lewat 2 hari.
              </p>
            </div>

            <div class="mt-5 rounded-3xl bg-slate-100 px-5 py-4 dark:bg-slate-800/60">
              <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Bukti Pengambilan</p>
              <div v-if="selectedBorrowingEvidence" class="mt-3 overflow-hidden rounded-2xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900">
                <img :src="selectedBorrowingEvidence" alt="Bukti pengambilan" class="h-44 w-full object-cover" />
              </div>
              <p v-else class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                Bukti pengambilan belum tersedia.
              </p>
            </div>
          </section>

          <section class="px-6 py-6">
            <form class="space-y-5" @submit.prevent="submitReview">
              <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Status Konfirmasi</label>
                <select
                  v-model="form.statusPengembalian"
                  class="w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                >
                  <option value="Belum Dikembalikan">Belum Dikembalikan</option>
                  <option value="Dikembalikan">Dikembalikan</option>
                </select>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                  Pilih `Dikembalikan` jika transaksi sudah ditutup, walaupun barang dinyatakan hilang saat pemeriksaan.
                </p>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Kondisi Barang Saat Dicek</label>
                <select
                  v-model="form.kondisiPengembalian"
                  class="w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                >
                  <option value="Normal">Normal</option>
                  <option value="Rusak">Rusak</option>
                  <option value="Hilang">Hilang</option>
                </select>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                  `Hilang` berarti barang tidak kembali dan dinyatakan hilang saat verifikasi, jadi denda kehilangan bisa digabung dengan denda keterlambatan.
                </p>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Tanggal Kembali Aktual</label>
                <input
                  v-model="form.tanggalKembaliAktual"
                  type="date"
                  class="w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                >
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                  Jika masih belum kembali, biarkan kosong dan sistem akan menghitung denda keterlambatan berdasarkan hari ini.
                </p>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Laporan Peminjam</label>
                <textarea
                  v-model.trim="form.laporanPeminjam"
                  rows="4"
                  class="w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                  placeholder="Catatan atau laporan yang disampaikan peminjam"
                ></textarea>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Laporan Staff</label>
                <textarea
                  v-model.trim="form.laporanStaff"
                  rows="4"
                  class="w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                  placeholder="Catatan verifikasi staff setelah pemeriksaan"
                ></textarea>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Bukti Pengembalian</label>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                  <input
                    type="file"
                    accept="image/*"
                    class="w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 sm:flex-1"
                    @change="handleBuktiPengembalianChange"
                  />
                  <button
                    v-if="form.buktiPengembalian"
                    type="button"
                    class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
                    @click="clearBuktiPengembalian"
                  >
                    Hapus Bukti
                  </button>
                </div>

                <div
                  v-if="form.buktiPengembalian"
                  class="mt-3 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-950"
                >
                  <img :src="form.buktiPengembalian" alt="Preview bukti pengembalian" class="h-44 w-full rounded-xl object-cover" />
                </div>

                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                  Bukti ini akan masuk ke laporan admin dan owner.
                </p>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Catatan Tambahan</label>
                <textarea
                  v-model.trim="form.catatan"
                  rows="3"
                  class="w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                  placeholder="Opsional"
                ></textarea>
              </div>

              <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                <button
                  type="button"
                  class="inline-flex items-center justify-center rounded-full bg-slate-200 px-6 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-300 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                  @click="resetForm"
                >
                  Reset
                </button>
                <button
                  type="submit"
                  class="inline-flex items-center justify-center rounded-full bg-cyan-500 px-6 py-3 text-sm font-extrabold text-white transition hover:bg-cyan-600 disabled:cursor-not-allowed disabled:opacity-60 dark:text-slate-950 dark:hover:bg-cyan-400"
                  :disabled="saving"
                >
                  {{ saving ? 'Menyimpan...' : 'Simpan Konfirmasi' }}
                </button>
              </div>
            </form>
          </section>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue'
import SidebarStaff from '../components/layout/SidebarStaff.vue'
import Navbar from '../components/layout/Navbar.vue'
import { apiRequest } from '../lib/api'
import { calculateBorrowingFine } from '../data/returnFine'
import { formatDateIndonesia, formatRupiah, getTodayLocalISODate } from '../data/staffBorrowing'
import { resolveReturnStatusBadge } from '../utils/returnStatusBadge'

const borrowings = ref([])
const isModalOpen = ref(false)
const selectedBorrowing = ref(null)
const saving = ref(false)
const buktiPengembalianFile = ref(null)
const buktiPengembalianRemoved = ref(false)
const feedback = ref({
  type: 'info',
  text: '',
})

const form = reactive(createEmptyForm())

const activeBorrowings = computed(() =>
  borrowings.value.filter((item) => ['Dipinjam', 'Belum Dikembalikan'].includes(item.statusPengembalian) || item.status === 'Dipinjam'),
)

const confirmedBorrowings = computed(() =>
  borrowings.value.filter((item) => item.statusPengembalian === 'Dikembalikan' || item.status === 'Dikembalikan'),
)

const awaitingCount = computed(() => activeBorrowings.value.length)

const overdueCount = computed(() =>
  activeBorrowings.value.filter((item) => calculateBorrowingFine({
    price: selectedPrice(item),
    dueDate: item.tanggalKembaliRencana,
    actualDate: getTodayLocalISODate(),
    statusPengembalian: 'Belum Dikembalikan',
  }).daysLate > 0).length,
)

const totalFine = computed(() =>
  activeBorrowings.value.reduce((total, item) => {
    const preview = calculateBorrowingFine({
      price: selectedPrice(item),
      dueDate: item.tanggalKembaliRencana,
      actualDate: getTodayLocalISODate(),
      statusPengembalian: item.statusPengembalian || 'Belum Dikembalikan',
      kondisiPengembalian: item.kondisiPengembalian || 'Normal',
    })

    return total + Number(item.biaya || preview.total || 0)
  }, 0),
)

const confirmedCount = computed(() => confirmedBorrowings.value.length)

const filteredBorrowings = computed(() => {
  return borrowings.value.filter((item) => ['Dipinjam', 'Dikembalikan'].includes(item.status) || item.statusPengembalian === 'Belum Dikembalikan')
})

const previewFine = computed(() => {
  if (!selectedBorrowing.value) {
    return { dendaKerusakan: 0, dendaKehilangan: 0, dendaKeterlambatan: 0, total: 0 }
  }

  const actualDate = form.statusPengembalian === 'Dikembalikan'
    ? (form.tanggalKembaliAktual || getTodayLocalISODate())
    : getTodayLocalISODate()

  return calculateBorrowingFine({
    price: selectedBorrowingPrice.value,
    dueDate: selectedBorrowing.value.tanggalKembaliRencana,
    actualDate,
    statusPengembalian: form.statusPengembalian,
    kondisiPengembalian: form.kondisiPengembalian || 'Normal',
  })
})

const selectedBorrowingPrice = computed(() => selectedPrice(selectedBorrowing.value))
const selectedBorrowingEvidence = computed(() => selectedBorrowing.value?.buktiPengambilan || selectedBorrowing.value?.gambar || '')

const feedbackClass = computed(() => {
  const map = {
    info: 'border-cyan-200 bg-cyan-50 text-cyan-900 dark:border-cyan-500/20 dark:bg-cyan-500/10 dark:text-cyan-100',
    success: 'border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-100',
    error: 'border-rose-200 bg-rose-50 text-rose-900 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-100',
  }

  return map[feedback.value.type] ?? map.info
})

onMounted(async () => {
  await loadBorrowings()
  setFeedback('info', 'Pilih transaksi untuk mengonfirmasi pengembalian dan menghitung denda secara otomatis.')
})

onBeforeUnmount(() => {})

function createEmptyForm() {
  return {
    statusPengembalian: 'Belum Dikembalikan',
    kondisiPengembalian: 'Normal',
    tanggalKembaliAktual: '',
    laporanPeminjam: '',
    laporanStaff: '',
    buktiPengembalian: '',
    catatan: '',
  }
}

function selectedPrice(item) {
  if (!item) {
    return 0
  }

  return Number(item.alatHargaAsli || item.alat_harga_asli || item.hargaAsli || 0)
}

function setFeedback(type, text) {
  feedback.value = { type, text }
}

async function loadBorrowings() {
  try {
    const data = await apiRequest('/api/borrowings')
    borrowings.value = Array.isArray(data) ? data : []
  } catch (error) {
    borrowings.value = []
    setFeedback('error', error?.message || 'Gagal memuat data pengembalian.')
  }
}

function openReview(item) {
  selectedBorrowing.value = item
  form.statusPengembalian = item.statusPengembalian || (item.status === 'Dikembalikan' ? 'Dikembalikan' : 'Belum Dikembalikan')
  form.kondisiPengembalian = item.kondisiPengembalian || 'Normal'
  form.tanggalKembaliAktual = item.tanggalKembaliAktual || ''
  form.laporanPeminjam = item.laporanPeminjam || ''
  form.laporanStaff = item.laporanStaff || ''
  form.buktiPengembalian = item.buktiPengembalian || ''
  form.catatan = item.catatan || ''
  buktiPengembalianFile.value = null
  buktiPengembalianRemoved.value = false
  isModalOpen.value = true
}

function closeReview() {
  isModalOpen.value = false
  selectedBorrowing.value = null
  resetForm()
}

function resetForm() {
  form.statusPengembalian = 'Belum Dikembalikan'
  form.kondisiPengembalian = 'Normal'
  form.tanggalKembaliAktual = ''
  form.laporanPeminjam = ''
  form.laporanStaff = ''
  form.buktiPengembalian = ''
  form.catatan = ''
  buktiPengembalianFile.value = null
  buktiPengembalianRemoved.value = false
}

function formatLateLabel(item) {
  const preview = calculateBorrowingFine({
    price: selectedPrice(item),
    dueDate: item.tanggalKembaliRencana,
    actualDate: item.tanggalKembaliAktual || getTodayLocalISODate(),
    statusPengembalian: item.statusPengembalian || 'Belum Dikembalikan',
    kondisiPengembalian: item.kondisiPengembalian || 'Normal',
  })

  if (!preview.daysLate) {
    return 'Tidak terlambat'
  }

  return `${preview.daysLate} hari terlambat`
}

function formatCurrency(value) {
  return formatRupiah(value)
}

function handleBuktiPengembalianChange(event) {
  const file = event?.target?.files?.[0]

  if (!file) {
    return
  }

  const reader = new FileReader()

  reader.onload = () => {
    form.buktiPengembalian = String(reader.result || '')
  }

  reader.readAsDataURL(file)
  buktiPengembalianFile.value = file
  buktiPengembalianRemoved.value = false
}

function clearBuktiPengembalian() {
  form.buktiPengembalian = ''
  buktiPengembalianFile.value = null
  buktiPengembalianRemoved.value = true
}

function toFormData(payload, file) {
  const formData = new FormData()

  for (const [key, value] of Object.entries(payload || {})) {
    if (value === undefined) {
      continue
    }

    if (value === null) {
      formData.append(key, '')
      continue
    }

    formData.append(key, String(value))
  }

  if (file) {
    formData.append('buktiPengembalian', file)
  }

  formData.append('_method', 'PUT')

  return formData
}

async function submitReview() {
  if (!selectedBorrowing.value) {
    return
  }

  if (form.statusPengembalian === 'Dikembalikan' && !form.tanggalKembaliAktual) {
    form.tanggalKembaliAktual = getTodayLocalISODate()
  }

  saving.value = true
  setFeedback('info', 'Menyimpan konfirmasi pengembalian...')

  try {
    const payload = {
      statusPengembalian: form.statusPengembalian,
      kondisiPengembalian: form.statusPengembalian === 'Dikembalikan' ? form.kondisiPengembalian || 'Normal' : '',
      tanggalKembaliAktual: form.tanggalKembaliAktual || null,
      laporanPeminjam: form.laporanPeminjam,
      laporanStaff: form.laporanStaff,
      catatan: form.catatan,
      hapusBuktiPengembalian: buktiPengembalianRemoved.value ? 1 : 0,
    }

    const body = toFormData(payload, buktiPengembalianFile.value)

    const updated = await apiRequest(`/api/borrowings/${selectedBorrowing.value.id}/return`, {
      method: 'POST',
      body,
    })

    borrowings.value = borrowings.value.map((item) => (item.id === updated.id ? updated : item))
    setFeedback('success', `Transaksi ${updated.kode} berhasil diperbarui.`)
    closeReview()
  } catch (error) {
    setFeedback('error', error?.message || 'Gagal menyimpan konfirmasi pengembalian.')
  } finally {
    saving.value = false
  }
}
</script>
