<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <SidebarStaff />

    <div class="flex-1 flex flex-col">
      <Navbar />

      <main class="flex-1 p-6">
        <div class="mb-8 flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
          <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Management Peminjaman</h1>
            <p class="mt-2 text-slate-700 dark:text-slate-300">
              Proses permintaan pinjam, penyerahan alat, pengembalian, dan penutupan transaksi oleh staff.
            </p>
          </div>

          <router-link
            to="/laporan-staff"
            class="inline-flex items-center justify-center rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
          >
            Buka Laporan Staff
          </router-link>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
          <article class="bg-blue-100 dark:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <p class="text-sm font-medium opacity-80">Total Peminjaman</p>
            <p class="mt-3 text-3xl font-bold">{{ items.length }}</p>
            <p class="mt-2 text-sm opacity-80">Semua transaksi peminjaman yang tersimpan di browser staff.</p>
          </article>

          <article class="bg-yellow-100 dark:bg-yellow-900/40 border border-yellow-300 dark:border-yellow-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <p class="text-sm font-medium opacity-80">Menunggu Tindakan</p>
            <p class="mt-3 text-3xl font-bold">{{ pendingCount }}</p>
            <p class="mt-2 text-sm opacity-80">Permintaan yang perlu diproses staff lebih lanjut.</p>
          </article>

          <article class="bg-green-100 dark:bg-green-900/40 border border-green-300 dark:border-green-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <p class="text-sm font-medium opacity-80">Di Pinjam</p>
            <p class="mt-3 text-3xl font-bold">{{ activeCount }}</p>
            <p class="mt-2 text-sm opacity-80">Status aktif dari approval sampai alat kembali ke staff.</p>
          </article>

          <article class="bg-purple-100 dark:bg-purple-900/40 border border-purple-300 dark:border-purple-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <p class="text-sm font-medium opacity-80">Akumulasi Denda</p>
            <p class="mt-3 text-3xl font-bold">{{ formatCompactCurrency(totalBiaya) }}</p>
            <p class="mt-2 text-sm opacity-80">Akumulasi nominal seluruh transaksi.</p>
          </article>
        </div>

        <section class="app-card app-card--cyan p-6">
          <div :class="['mb-5 rounded-2xl border px-4 py-3 text-sm', feedbackClass]">
            {{ feedback.text }}
          </div>

          <div class="mb-5 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
              <h2 class="text-xl font-bold text-slate-900 dark:text-white">Daftar Peminjaman</h2>
              <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Total {{ items.length }} transaksi, tampil {{ filteredItems.length }} transaksi.
              </p>
            </div>

            <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
              <input
                :value="search"
                type="search"
                placeholder="Cari peminjam, alat, divisi, atau kode..."
                class="w-full rounded-full border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 lg:w-72 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @input="search = $event.target.value"
              />

              <select
                :value="statusFilter"
                class="rounded-full border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @change="statusFilter = $event.target.value"
              >
                <option value="Semua">Semua Status</option>
                <option v-for="status in staffBorrowingStatusOptions" :key="status" :value="status">
                  {{ status }}
                </option>
              </select>

              <button
                type="button"
                class="rounded-full bg-cyan-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400"
                @click="openCreateModal"
              >
                - Tambah -
              </button>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full">
              <thead>
                <tr class="border-b border-slate-200 dark:border-slate-800">
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Kode</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Peminjam</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Alat</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Status</th>
                  <th class="px-4 py-3 text-right text-sm font-semibold text-slate-700 dark:text-slate-300">Denda</th>
                  <th class="px-4 py-3 text-right text-sm font-semibold text-slate-700 dark:text-slate-300">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="item in filteredItems"
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
                    <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ item.keperluan }}</div>
                  </td>
                  <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-200">
                    <div class="font-semibold">{{ item.namaAlat }}</div>
                    <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ item.kategori }}</div>
                  </td>
                  <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-200">
                    <div>Pinjam: {{ formatDateIndonesia(item.tanggalPinjam) }}</div>
                    <div class="mt-1">Rencana: {{ formatDateIndonesia(item.tanggalKembaliRencana) }}</div>
                    <div class="mt-1">Aktual: {{ formatDateIndonesia(item.tanggalKembaliAktual) }}</div>
                  </td>
                  <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-200">
                    <span :class="['inline-flex rounded-full px-3 py-1 text-xs font-semibold', statusToneClass(item.status)]">
                      {{ item.status }}
                    </span>
                    <p class="mt-2 max-w-xs text-xs text-slate-500 dark:text-slate-400">
                      {{ item.catatan || 'Belum ada catatan staff.' }}
                    </p>
                  </td>
                  <td class="px-4 py-4 text-right text-sm text-slate-700 dark:text-slate-200">
                    {{ formatRupiah(item.biaya) }}
                  </td>
                  <td class="px-4 py-4">
                    <div class="flex flex-wrap justify-end gap-2">
                      <button
                        v-for="action in getQuickActions(item)"
                        :key="action.key"
                        type="button"
                        :class="['rounded-full border px-3 py-2 text-xs font-semibold transition', action.className]"
                        @click="applyQuickAction(item, action.key)"
                      >
                        {{ action.label }}
                      </button>
                      <button
                        type="button"
                        class="rounded-full border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
                        @click="startEdit(item)"
                      >
                        Edit
                      </button>
                      <button
                        type="button"
                        class="rounded-full border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-700 transition hover:bg-rose-50 dark:border-rose-500/20 dark:text-rose-200 dark:hover:bg-rose-500/10"
                        @click="removeItem(item)"
                      >
                        Hapus
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>

            <div
              v-if="!filteredItems.length"
              class="rounded-3xl border border-dashed border-slate-300 px-6 py-12 text-center dark:border-slate-700"
            >
              <h3 class="text-lg font-bold text-slate-900 dark:text-white">Transaksi tidak ditemukan</h3>
              <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                Coba ubah pencarian atau filter status, atau tambahkan transaksi baru dari tombol di atas.
              </p>
            </div>
          </div>
        </section>
      </main>
    </div>

    <div
      v-if="isModalOpen"
      class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4"
      @click.self="closeModal()"
    >
      <section class="w-full max-w-3xl max-h-[90vh] overflow-hidden rounded-[2rem] border border-cyan-200 bg-white shadow-2xl shadow-slate-950/40 dark:border-cyan-500/20 dark:bg-slate-900 flex flex-col">
        <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
          <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-600 dark:text-cyan-300">
              {{ editingId ? 'Mode Edit' : 'Mode Tambah' }}
            </p>
            <h2 class="mt-2 text-xl font-bold text-slate-900 dark:text-white">{{ editingId ? 'Edit Peminjaman' : 'Tambah Peminjaman' }}</h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
              Lengkapi data transaksi. Kode peminjaman akan dibuat otomatis untuk transaksi baru.
            </p>
          </div>

          <button
            type="button"
            class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
            @click="closeModal()"
          >
            Tutup
          </button>
        </div>

        <form class="min-h-0 flex-1 flex flex-col" @submit.prevent="submitForm">
          <div class="min-h-0 flex-1 overflow-y-auto px-6 py-6">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Peminjam</label>
              <input
                :value="form.namaPeminjam"
                type="text"
                placeholder="Contoh: Andi Saputra"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @input="form.namaPeminjam = $event.target.value"
              />
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Divisi</label>
              <input
                :value="form.divisi"
                type="text"
                placeholder="Contoh: Operasional"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @input="form.divisi = $event.target.value"
              />
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Alat</label>
              <input
                :value="form.namaAlat"
                type="text"
                placeholder="Contoh: Multimeter Digital"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @input="form.namaAlat = $event.target.value"
              />
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Kategori</label>
              <input
                :value="form.kategori"
                type="text"
                placeholder="Contoh: Elektronik"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @input="form.kategori = $event.target.value"
              />
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Pinjam</label>
              <input
                :value="form.tanggalPinjam"
                type="date"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @input="form.tanggalPinjam = $event.target.value"
              />
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Kembali Rencana</label>
              <input
                :value="form.tanggalKembaliRencana"
                type="date"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @input="form.tanggalKembaliRencana = $event.target.value"
              />
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Kembali Aktual</label>
              <input
                :value="form.tanggalKembaliAktual"
                type="date"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @input="form.tanggalKembaliAktual = $event.target.value"
              />
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Status</label>
              <select
                :value="form.status"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @change="form.status = $event.target.value"
              >
                <option v-for="status in staffBorrowingStatusOptions" :key="status" :value="status">
                  {{ status }}
                </option>
              </select>
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Petugas Staff</label>
              <input
                :value="form.petugas"
                type="text"
                placeholder="Contoh: Raka Staff"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @input="form.petugas = $event.target.value"
              />
            </div>

            <div class="md:col-span-2">
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Keperluan</label>
              <textarea
                :value="form.keperluan"
                rows="4"
                placeholder="Jelaskan tujuan peminjaman"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @input="form.keperluan = $event.target.value"
              />
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Denda</label>
              <input
                :value="form.biaya"
                type="number"
                min="0"
                step="1000"
                placeholder="0"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @input="form.biaya = Number($event.target.value || 0)"
              />
            </div>

            <div class="md:col-span-2">
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Catatan Staff</label>
              <textarea
                :value="form.catatan"
                rows="3"
                placeholder="Tambahkan catatan verifikasi atau kondisi alat"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @input="form.catatan = $event.target.value"
              />
            </div>

            <div class="md:col-span-2">
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Gambar</label>
              <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <input
                  type="file"
                  accept="image/*"
                  class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 sm:flex-1"
                  @change="handleImageChange"
                />
                <button
                  v-if="form.gambar"
                  type="button"
                  class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
                  @click="form.gambar = ''"
                >
                  Hapus Gambar
                </button>
              </div>

              <div
                v-if="form.gambar"
                class="mt-3 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-950"
              >
                <img :src="form.gambar" alt="Preview gambar" class="h-44 w-full rounded-xl object-cover" />
              </div>

              <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Opsional. Gambar disimpan di browser (localStorage).</p>
            </div>
          </div>
          </div>

          <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-5 sm:flex-row sm:justify-end dark:border-slate-800">
            <button
              type="button"
              class="rounded-full border border-slate-300 px-5 py-3 font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
              @click="restoreForm"
            >
              Reset Form
            </button>
            <button
              type="button"
              class="rounded-full border border-slate-300 px-5 py-3 font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
              @click="closeModal()"
            >
              Batal
            </button>
            <button
              type="submit"
              class="rounded-full bg-cyan-500 px-5 py-3 font-semibold text-slate-950 transition hover:bg-cyan-400"
            >
              {{ editingId ? 'Simpan Perubahan' : 'Tambah Peminjaman' }}
            </button>
          </div>
        </form>
      </section>
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue'
import SidebarStaff from '../components/layout/SidebarStaff.vue'
import Navbar from '../components/layout/Navbar.vue'
import {
  cloneStaffBorrowing,
  createBorrowingCode,
  createBorrowingId,
  formatDateIndonesia,
  formatRupiah,
  getStaffBorrowings,
  saveStaffBorrowings,
  staffBorrowingStatusOptions,
  staffReportReferenceDate,
} from '../data/staffBorrowing'

const items = ref([])
const search = ref('')
const statusFilter = ref('Semua')
const editingId = ref(null)
const isModalOpen = ref(false)
const editingSnapshot = ref(null)
const feedback = ref({
  type: 'info',
  text: '',
})

const form = reactive(createEmptyForm())

const filteredItems = computed(() => {
  const keyword = search.value.trim().toLowerCase()

  return items.value.filter((item) => {
    const matchesStatus = statusFilter.value === 'Semua' || item.status === statusFilter.value
    const matchesKeyword =
      !keyword ||
      [
        item.kode,
        item.namaPeminjam,
        item.divisi,
        item.namaAlat,
        item.kategori,
        item.petugas,
      ].some((value) => String(value ?? '').toLowerCase().includes(keyword))

    return matchesStatus && matchesKeyword
  })
})

const pendingCount = computed(() =>
  items.value.filter((item) => ['Pending', 'Disetujui', 'Dikembalikan'].includes(item.status)).length,
)

const activeCount = computed(() =>
  items.value.filter((item) => ['Pending', 'Disetujui', 'Dipinjam', 'Dikembalikan'].includes(item.status)).length,
)

const totalBiaya = computed(() =>
  items.value.reduce((total, item) => total + Number(item.biaya || 0), 0),
)

const feedbackClass = computed(() => {
  const map = {
    info: 'border-cyan-200 bg-cyan-50 text-cyan-900 dark:border-cyan-500/20 dark:bg-cyan-500/10 dark:text-cyan-100',
    success: 'border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-100',
    error: 'border-rose-200 bg-rose-50 text-rose-900 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-100',
  }

  return map[feedback.value.type] ?? map.info
})

onMounted(() => {
  loadItems()
  setFeedback('info', 'Data peminjaman staff siap dikelola. Gunakan tombol aksi cepat untuk memproses transaksi.')

  if (typeof window !== 'undefined') {
    window.addEventListener('keydown', handleWindowKeydown)
  }
})

onBeforeUnmount(() => {
  if (typeof window !== 'undefined') {
    window.removeEventListener('keydown', handleWindowKeydown)
  }
})

function createEmptyForm() {
  return {
    namaPeminjam: '',
    divisi: '',
    namaAlat: '',
    kategori: '',
    tanggalPinjam: staffReportReferenceDate,
    tanggalKembaliRencana: staffReportReferenceDate,
    tanggalKembaliAktual: '',
    status: 'Pending',
    petugas: 'Raka Staff',
    keperluan: '',
    biaya: 0,
    catatan: '',
    gambar: '',
  }
}

function setFeedback(type, text) {
  feedback.value = { type, text }
}

function loadItems() {
  items.value = getStaffBorrowings()
}

function persistItems() {
  saveStaffBorrowings(items.value)
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

function statusToneClass(status) {
  const map = {
    Pending: 'bg-amber-100 text-amber-800 dark:bg-amber-500/15 dark:text-amber-200',
    Disetujui: 'bg-cyan-100 text-cyan-800 dark:bg-cyan-500/15 dark:text-cyan-200',
    Dipinjam: 'bg-violet-100 text-violet-800 dark:bg-violet-500/15 dark:text-violet-200',
    Dikembalikan: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-200',
    Selesai: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-200',
    Ditolak: 'bg-rose-100 text-rose-800 dark:bg-rose-500/15 dark:text-rose-200',
  }

  return map[status] ?? 'bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-100'
}

function getQuickActions(item) {
  const actionsByStatus = {
    Pending: [
      {
        key: 'approve',
        label: 'Approve',
        className: 'border-emerald-200 text-emerald-700 hover:bg-emerald-50 dark:border-emerald-500/20 dark:text-emerald-200 dark:hover:bg-emerald-500/10',
      },
      {
        key: 'reject',
        label: 'Tolak',
        className: 'border-rose-200 text-rose-700 hover:bg-rose-50 dark:border-rose-500/20 dark:text-rose-200 dark:hover:bg-rose-500/10',
      },
    ],
    Disetujui: [
      {
        key: 'handover',
        label: 'Serahkan Alat',
        className: 'border-cyan-200 text-cyan-700 hover:bg-cyan-50 dark:border-cyan-500/20 dark:text-cyan-200 dark:hover:bg-cyan-500/10',
      },
    ],
    Dipinjam: [
      {
        key: 'return',
        label: 'Konfirmasi Kembali',
        className: 'border-amber-200 text-amber-700 hover:bg-amber-50 dark:border-amber-500/20 dark:text-amber-200 dark:hover:bg-amber-500/10',
      },
    ],
    Dikembalikan: [
      {
        key: 'complete',
        label: 'Selesaikan',
        className: 'border-emerald-200 text-emerald-700 hover:bg-emerald-50 dark:border-emerald-500/20 dark:text-emerald-200 dark:hover:bg-emerald-500/10',
      },
    ],
  }

  return actionsByStatus[item.status] ?? []
}

function openCreateModal() {
  resetFormState()
  isModalOpen.value = true
  setFeedback('info', 'Lengkapi form untuk menambahkan transaksi peminjaman baru.')
}

function startEdit(item) {
  editingId.value = item.id
  editingSnapshot.value = cloneStaffBorrowing(item)
  Object.assign(form, createEmptyForm(), cloneStaffBorrowing(item))
  isModalOpen.value = true
  setFeedback('info', `Transaksi ${item.kode} siap diedit.`)
}

function restoreForm() {
  if (editingId.value && editingSnapshot.value) {
    Object.assign(form, createEmptyForm(), cloneStaffBorrowing(editingSnapshot.value))
    setFeedback('info', 'Perubahan form dikembalikan ke data awal transaksi.')
    return
  }

  Object.assign(form, createEmptyForm())
  setFeedback('info', 'Form transaksi baru sudah dikosongkan.')
}

function resetFormState() {
  Object.assign(form, createEmptyForm())
  editingId.value = null
  editingSnapshot.value = null
}

function closeModal({ keepFeedback = false } = {}) {
  isModalOpen.value = false
  resetFormState()

  if (!keepFeedback) {
    setFeedback('info', 'Data peminjaman staff siap dikelola. Gunakan tombol aksi cepat untuk memproses transaksi.')
  }
}

function validateForm() {
  const requiredFields = [
    ['namaPeminjam', 'Nama peminjam'],
    ['divisi', 'Divisi'],
    ['namaAlat', 'Nama alat'],
    ['kategori', 'Kategori'],
    ['tanggalPinjam', 'Tanggal pinjam'],
    ['tanggalKembaliRencana', 'Tanggal kembali rencana'],
    ['status', 'Status'],
    ['petugas', 'Petugas staff'],
    ['keperluan', 'Keperluan'],
  ]

  const missingField = requiredFields.find(([key]) => String(form[key] ?? '').trim() === '')

  if (missingField) {
    setFeedback('error', `${missingField[1]} wajib diisi sebelum transaksi disimpan.`)
    return false
  }

  return true
}

function buildPayload() {
  return {
    namaPeminjam: String(form.namaPeminjam).trim(),
    divisi: String(form.divisi).trim(),
    namaAlat: String(form.namaAlat).trim(),
    kategori: String(form.kategori).trim(),
    tanggalPinjam: form.tanggalPinjam,
    tanggalKembaliRencana: form.tanggalKembaliRencana,
    tanggalKembaliAktual: form.tanggalKembaliAktual,
    status: form.status,
    petugas: String(form.petugas).trim(),
    keperluan: String(form.keperluan).trim(),
    biaya: Number(form.biaya || 0),
    catatan: String(form.catatan || '').trim(),
    gambar: String(form.gambar || ''),
  }
}

function handleImageChange(event) {
  const file = event?.target?.files?.[0]

  if (!file) {
    return
  }

  if (typeof window === 'undefined') {
    return
  }

  const reader = new FileReader()

  reader.onload = () => {
    form.gambar = String(reader.result || '')
  }

  reader.readAsDataURL(file)
}

function submitForm() {
  if (!validateForm()) {
    return
  }

  const payload = buildPayload()

  if (editingId.value) {
    items.value = items.value.map((item) =>
      item.id === editingId.value
        ? {
            ...item,
            ...payload,
          }
        : item,
    )

    persistItems()
    closeModal({ keepFeedback: true })
    setFeedback('success', 'Transaksi peminjaman berhasil diperbarui.')
    return
  }

  items.value = [
    {
      id: createBorrowingId(),
      kode: createBorrowingCode(items.value, payload.tanggalPinjam),
      ...payload,
    },
    ...items.value,
  ]

  persistItems()
  closeModal({ keepFeedback: true })
  setFeedback('success', 'Transaksi peminjaman baru berhasil ditambahkan.')
}

function applyQuickAction(item, action) {
  const actionMap = {
    approve: {
      status: 'Disetujui',
      catatan: 'Permintaan telah disetujui dan siap diserahkan.',
    },
    reject: {
      status: 'Ditolak',
      catatan: 'Permintaan ditolak oleh staff setelah proses verifikasi.',
    },
    handover: {
      status: 'Dipinjam',
      catatan: 'Alat sudah diserahkan ke peminjam.',
    },
    return: {
      status: 'Dikembalikan',
      tanggalKembaliAktual: staffReportReferenceDate,
      catatan: 'Alat sudah kembali dan menunggu penutupan transaksi.',
    },
    complete: {
      status: 'Selesai',
      catatan: 'Transaksi selesai dan alat telah diperiksa staff.',
    },
  }

  const nextState = actionMap[action]

  if (!nextState) {
    return
  }

  items.value = items.value.map((row) =>
    row.id === item.id
      ? {
          ...row,
          ...nextState,
          tanggalKembaliAktual: nextState.tanggalKembaliAktual ?? row.tanggalKembaliAktual,
        }
      : row,
  )

  persistItems()
  setFeedback('success', `Transaksi ${item.kode} berhasil diperbarui ke status ${nextState.status}.`)
}

function removeItem(item) {
  if (typeof window !== 'undefined') {
    const confirmed = window.confirm(`Hapus transaksi ${item.kode}?`)

    if (!confirmed) {
      return
    }
  }

  items.value = items.value.filter((row) => row.id !== item.id)
  persistItems()

  if (editingId.value === item.id) {
    closeModal({ keepFeedback: true })
  }

  setFeedback('success', `Transaksi ${item.kode} berhasil dihapus.`)
}

function handleWindowKeydown(event) {
  if (event.key === 'Escape' && isModalOpen.value) {
    closeModal()
  }
}
</script>
