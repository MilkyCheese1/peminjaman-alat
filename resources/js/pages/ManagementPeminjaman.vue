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

        <div :class="['mb-5 rounded-2xl border px-4 py-3 text-sm', feedbackClass]">
          {{ feedback.text }}
        </div>

        <BorrowingSummaryCards
          :total-items="items.length"
          :pending-count="pendingCount"
          :active-count="activeCount"
          :total-biaya-label="formatCompactCurrency(totalBiaya)"
        />

        <BorrowingToolbar
          :search="search"
          :status-filter="statusFilter"
          :status-options="staffBorrowingStatusOptions"
          :total-items="items.length"
          :filtered-items-count="filteredItems.length"
          @update-search="search = $event"
          @update-status-filter="statusFilter = $event"
          @add="openCreateModal"
        />

        <BorrowingTable
          :items="filteredItems"
          :format-date-indonesia="formatDateIndonesia"
          :format-rupiah="formatRupiah"
          :get-quick-actions="getQuickActions"
          @quick-action="applyQuickAction"
          @edit="startEdit"
          @remove="removeItem"
        />
      </main>
    </div>

    <BorrowingModal
      :is-open="isModalOpen"
      :editing-id="editingId"
      :form="form"
      :staff-borrowing-status-options="staffBorrowingStatusOptions"
      :is-saving="isSaving"
      @close="closeModal()"
      @submit="submitForm"
      @restore-form="restoreForm"
      @image-change="handleImageChange"
      @clear-image="clearImage"
    />
  </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue'
import SidebarStaff from '../components/layout/SidebarStaff.vue'
import Navbar from '../components/layout/Navbar.vue'
import BorrowingSummaryCards from '../components/management/BorrowingSummaryCards.vue'
import BorrowingToolbar from '../components/management/BorrowingToolbar.vue'
import BorrowingTable from '../components/management/BorrowingTable.vue'
import BorrowingModal from '../components/management/BorrowingModal.vue'
import { apiRequest } from '../lib/api'
import {
  buildBorrowingPayload,
  createEmptyBorrowingForm,
  validateBorrowingForm,
} from '../utils/borrowingForm'
import {
  buildBorrowingPayloadFromItem,
  formatCompactCurrency,
  getBorrowingQuickActions,
  resolveBorrowingActionState,
} from '../utils/borrowingManagement'
import {
  cloneStaffBorrowing,
  createBorrowingCode,
  formatDateIndonesia,
  formatRupiah,
  staffBorrowingStatusOptions,
  staffReportReferenceDate,
} from '../data/staffBorrowing'

const items = ref([])
const search = ref('')
const statusFilter = ref('Semua')
const editingId = ref(null)
const editingKode = ref('')
const isModalOpen = ref(false)
const editingSnapshot = ref(null)
const isSaving = ref(false)
const gambarFile = ref(null)
const gambarRemoved = ref(false)
const feedback = ref({
  type: 'info',
  text: '',
})

const form = reactive(createEmptyBorrowingForm(staffReportReferenceDate))

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

const getQuickActions = (item) => getBorrowingQuickActions(item.status)

onMounted(async () => {
  await loadItems()
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

function setFeedback(type, text) {
  feedback.value = { type, text }
}

async function loadItems() {
  try {
    const data = await apiRequest('/api/borrowings')
    items.value = Array.isArray(data) ? cloneStaffBorrowing(data) : []
  } catch (error) {
    items.value = []
    setFeedback('error', error?.message || 'Gagal memuat data peminjaman.')
  }
}

function openCreateModal() {
  resetFormState()
  isModalOpen.value = true
  setFeedback('info', 'Lengkapi form untuk menambahkan transaksi peminjaman baru.')
}

function startEdit(item) {
  editingId.value = item.id
  editingKode.value = item.kode
  editingSnapshot.value = cloneStaffBorrowing(item)
  Object.assign(form, createEmptyBorrowingForm(staffReportReferenceDate), cloneStaffBorrowing(item))
  gambarFile.value = null
  gambarRemoved.value = false
  isModalOpen.value = true
  setFeedback('info', `Transaksi ${item.kode} siap diedit.`)
}

function restoreForm() {
  if (editingId.value && editingSnapshot.value) {
    Object.assign(form, createEmptyBorrowingForm(staffReportReferenceDate), cloneStaffBorrowing(editingSnapshot.value))
    gambarFile.value = null
    gambarRemoved.value = false
    setFeedback('info', 'Perubahan form dikembalikan ke data awal transaksi.')
    return
  }

  Object.assign(form, createEmptyBorrowingForm(staffReportReferenceDate))
  gambarFile.value = null
  gambarRemoved.value = false
  setFeedback('info', 'Form transaksi baru sudah dikosongkan.')
}

function resetFormState() {
  Object.assign(form, createEmptyBorrowingForm(staffReportReferenceDate))
  editingId.value = null
  editingKode.value = ''
  editingSnapshot.value = null
  gambarFile.value = null
  gambarRemoved.value = false
}

function closeModal({ keepFeedback = false } = {}) {
  isModalOpen.value = false
  resetFormState()

  if (!keepFeedback) {
    setFeedback('info', 'Data peminjaman staff siap dikelola. Gunakan tombol aksi cepat untuk memproses transaksi.')
  }
}

function validateForm() {
  const validation = validateBorrowingForm(form)

  if (!validation.valid) {
    setFeedback('error', `${validation.missingLabel} wajib diisi sebelum transaksi disimpan.`)
    return false
  }

  return true
}

function buildPayload() {
  return buildBorrowingPayload(form)
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
  gambarFile.value = file
  gambarRemoved.value = false
}

function clearImage() {
  form.gambar = ''
  gambarFile.value = null
  gambarRemoved.value = true
}

async function submitForm() {
  if (!validateForm()) {
    return
  }

  const payload = buildPayload()

  if (gambarRemoved.value) {
    payload.gambar = null
  }

  try {
    isSaving.value = true

    if (editingId.value) {
      const body = gambarFile.value ? toFormData({ kode: editingKode.value, ...payload }, gambarFile.value) : { kode: editingKode.value, ...payload }
      const updated = await apiRequest(`/api/borrowings/${editingId.value}`, { method: 'PUT', body })

      items.value = items.value.map((item) => (item.id === editingId.value ? updated : item))
      closeModal({ keepFeedback: true })
      setFeedback('success', 'Transaksi peminjaman berhasil diperbarui.')
      return
    }

    const createBody = { kode: createBorrowingCode(items.value, payload.tanggalPinjam), ...payload }
    const body = gambarFile.value ? toFormData(createBody, gambarFile.value) : createBody
    const created = await apiRequest('/api/borrowings', { method: 'POST', body })

    items.value = [created, ...items.value]
    closeModal({ keepFeedback: true })
    setFeedback('success', 'Transaksi peminjaman baru berhasil ditambahkan.')
  } catch (error) {
    setFeedback('error', error?.message || 'Gagal menyimpan transaksi.')
  } finally {
    isSaving.value = false
  }
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
    formData.append('gambar', file)
  }

  return formData
}

async function applyQuickAction(item, action) {
  const nextState = resolveBorrowingActionState(action, staffReportReferenceDate)

  if (!nextState) {
    return
  }

  const nextItem = {
    ...item,
    ...nextState,
    tanggalKembaliAktual: nextState.tanggalKembaliAktual ?? item.tanggalKembaliAktual,
  }

  try {
    isSaving.value = true
    const updated = await apiRequest(`/api/borrowings/${item.id}`, {
      method: 'PUT',
      body: buildBorrowingPayloadFromItem(nextItem),
    })

    items.value = items.value.map((row) => (row.id === item.id ? updated : row))
    setFeedback('success', `Transaksi ${item.kode} berhasil diperbarui ke status ${nextState.status}.`)
  } catch (error) {
    setFeedback('error', error?.message || 'Gagal memperbarui transaksi.')
  } finally {
    isSaving.value = false
  }
}

async function removeItem(item) {
  if (typeof window !== 'undefined') {
    const confirmed = window.confirm(`Hapus transaksi ${item.kode}?`)

    if (!confirmed) {
      return
    }
  }

  try {
    isSaving.value = true
    await apiRequest(`/api/borrowings/${item.id}`, { method: 'DELETE' })

    items.value = items.value.filter((row) => row.id !== item.id)

    if (editingId.value === item.id) {
      closeModal({ keepFeedback: true })
    }

    setFeedback('success', `Transaksi ${item.kode} berhasil dihapus.`)
  } catch (error) {
    setFeedback('error', error?.message || 'Gagal menghapus transaksi.')
  } finally {
    isSaving.value = false
  }
}

function handleWindowKeydown(event) {
  if (event.key === 'Escape' && isModalOpen.value) {
    closeModal()
  }
}
</script>
