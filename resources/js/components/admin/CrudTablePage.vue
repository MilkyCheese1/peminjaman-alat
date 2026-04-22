<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex min-w-0">
    <SidebarAdmin />

    <div class="flex-1 flex min-w-0 flex-col">
      <Navbar />

      <main class="flex-1 min-w-0 p-4 sm:p-6">
        <div class="mb-8 flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
          <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ title }}</h1>
            <p class="mt-2 text-slate-700 dark:text-slate-300">{{ subtitle }}</p>
          </div>

          <div class="rounded-2xl border border-cyan-200 bg-cyan-50 px-4 py-3 text-sm text-cyan-900 dark:border-cyan-500/20 dark:bg-cyan-500/10 dark:text-cyan-100">
            {{ dataSourceNote }}
          </div>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
          <article
            v-for="card in displayedSummaryCards"
            :key="card.label"
            :class="['rounded-3xl border p-6 shadow-2xl shadow-slate-950/40', cardToneClass(card.tone)]"
          >
            <p class="text-sm font-medium opacity-80">{{ card.label }}</p>
            <p class="mt-3 text-3xl font-bold">{{ card.value }}</p>
            <p v-if="card.caption" class="mt-2 text-sm opacity-80">{{ card.caption }}</p>
          </article>
        </div>

        <section class="app-card app-card--cyan p-6">
          <div :class="['mb-5 rounded-2xl border px-4 py-3 text-sm', feedbackClass]">
            {{ feedback.text }}
          </div>

          <div class="mb-5 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div class="min-w-0">
              <h2 class="text-xl font-bold text-slate-900 dark:text-white">Tabel {{ title }}</h2>
              <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Total {{ items.length }} data, tampil {{ filteredItems.length }} data.
              </p>
            </div>

            <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
              <input
                :value="search"
                type="search"
                :placeholder="`Cari ${entityLabel.toLowerCase()}...`"
                class="w-full rounded-full border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 lg:w-64 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @input="search = $event.target.value"
              />

              <button
                type="button"
                class="rounded-full bg-cyan-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400"
                @click="openCreateModal"
              >
                - Tambah -
              </button>
            </div>
          </div>

          <div class="space-y-4 lg:hidden">
            <article
              v-for="item in filteredItems"
              :key="item.id"
              class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900"
            >
              <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                  <p class="text-sm font-semibold text-slate-900 dark:text-white break-words">
                    {{ getCellDisplay(columns[0], item) }}
                  </p>
                  <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                    {{ columns[0]?.label || 'Data' }}
                  </p>
                </div>

                <div class="flex flex-wrap justify-end gap-2">
                  <button
                    type="button"
                    class="rounded-full border border-cyan-200 px-3 py-2 text-xs font-semibold text-cyan-700 transition hover:bg-cyan-50 dark:border-cyan-500/20 dark:text-cyan-200 dark:hover:bg-cyan-500/10"
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
              </div>

              <dl class="mt-4 grid grid-cols-1 gap-3 text-sm text-slate-700 dark:text-slate-200">
                <div
                  v-for="column in columns.slice(1)"
                  :key="column.key"
                  class="rounded-2xl bg-slate-100/80 px-4 py-3 dark:bg-slate-800/50"
                >
                  <dt class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                    {{ column.label }}
                  </dt>
                  <dd class="mt-1 min-w-0 break-words">
                    <span
                      v-if="column.type === 'badge'"
                      :class="['inline-flex rounded-full px-3 py-1 text-xs font-semibold', badgeToneClass(resolveBadgeTone(column, item))]"
                    >
                      {{ getCellDisplay(column, item) }}
                    </span>
                    <span v-else :class="cellTextClass(column)">{{ getCellDisplay(column, item) }}</span>
                  </dd>
                </div>
              </dl>
            </article>

            <div
              v-if="!filteredItems.length"
              class="rounded-3xl border border-dashed border-slate-300 px-6 py-12 text-center dark:border-slate-700"
            >
              <h3 class="text-lg font-bold text-slate-900 dark:text-white">Data tidak ditemukan</h3>
              <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                Coba ubah kata kunci pencarian atau klik tombol tambah untuk membuka pop-up form.
              </p>
            </div>
          </div>

          <div class="hidden overflow-x-auto lg:block">
            <table class="min-w-full">
              <thead>
                <tr class="border-b border-slate-200 dark:border-slate-800">
                  <th
                    v-for="column in columns"
                    :key="column.key"
                    :class="[
                      'px-4 py-3 text-sm font-semibold text-slate-700 dark:text-slate-300',
                      column.align === 'right' ? 'text-right' : 'text-left',
                    ]"
                  >
                    {{ column.label }}
                  </th>
                  <th class="px-4 py-3 text-right text-sm font-semibold text-slate-700 dark:text-slate-300">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="item in filteredItems"
                  :key="item.id"
                  class="border-b border-slate-200 transition hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800/40"
                >
                  <td
                    v-for="column in columns"
                    :key="column.key"
                    :class="[
                      'px-4 py-4 text-sm text-slate-700 dark:text-slate-200',
                      column.align === 'right' ? 'text-right' : 'text-left',
                    ]"
                  >
                    <span
                      v-if="column.type === 'badge'"
                      :class="['inline-flex rounded-full px-3 py-1 text-xs font-semibold', badgeToneClass(resolveBadgeTone(column, item))]"
                    >
                      {{ getCellDisplay(column, item) }}
                    </span>
                    <span v-else :class="cellTextClass(column)">{{ getCellDisplay(column, item) }}</span>
                  </td>
                  <td class="px-4 py-4 text-right">
                    <div class="flex justify-end gap-2">
                      <button
                        type="button"
                        class="rounded-full border border-cyan-200 px-3 py-2 text-xs font-semibold text-cyan-700 transition hover:bg-cyan-50 dark:border-cyan-500/20 dark:text-cyan-200 dark:hover:bg-cyan-500/10"
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
              <h3 class="text-lg font-bold text-slate-900 dark:text-white">Data tidak ditemukan</h3>
              <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                Coba ubah kata kunci pencarian atau klik tombol tambah untuk membuka pop-up form.
              </p>
            </div>
          </div>
        </section>
      </main>
    </div>

    <div
      v-if="isModalOpen"
      class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 p-4"
      @click.self="closeModal()"
    >
      <section class="flex max-h-[90vh] w-full max-w-2xl flex-col overflow-hidden rounded-[2rem] border border-cyan-200 bg-white shadow-2xl shadow-slate-950/40 dark:border-cyan-500/20 dark:bg-slate-900">
        <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
          <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-600 dark:text-cyan-300">
              {{ editingId ? 'Mode Edit' : 'Mode Tambah' }}
            </p>
            <h2 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">{{ formTitle }}</h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
              Lengkapi data lalu simpan. Tekan <span class="font-semibold">Esc</span> untuk menutup pop-up.
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
          <div class="min-h-0 flex-1 overflow-y-auto space-y-5 px-6 py-6">
            <div :class="['rounded-2xl border px-4 py-3 text-sm', feedbackClass]">
              {{ feedback.text }}
            </div>

            <div v-for="field in normalizedFields" :key="field.key">
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                {{ field.label }}
              </label>

              <div v-if="field.type === 'image'" class="space-y-3">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                  <input
                    type="file"
                    accept="image/*"
                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 sm:flex-1"
                    @change="handleImageChange(field, $event)"
                  />
                  <button
                    v-if="form[field.key]"
                    type="button"
                    class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
                    @click="clearImageField(field)"
                  >
                    Hapus Gambar
                  </button>
                </div>

                <div
                  v-if="form[field.key]"
                  class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-950"
                >
                  <div class="flex justify-center">
                    <img :src="form[field.key]" alt="Preview gambar" class="h-40 w-40 rounded-xl object-cover" />
                  </div>
                </div>
              </div>

              <textarea
                v-else-if="field.type === 'textarea'"
                :value="form[field.key]"
                :placeholder="field.placeholder"
                :required="isFieldRequired(field)"
                :rows="field.rows"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @input="updateField(field, $event.target.value)"
              />

              <select
                v-else-if="field.type === 'select'"
                :value="form[field.key]"
                :required="isFieldRequired(field)"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @change="updateField(field, $event.target.value)"
              >
                <option value="">Pilih {{ field.label.toLowerCase() }}</option>
                <option
                  v-for="option in normalizeOptions(field.options)"
                  :key="option.value"
                  :value="option.value"
                >
                  {{ option.label }}
                </option>
              </select>

              <input
                v-else
                :type="field.type"
                :value="form[field.key]"
                :placeholder="field.placeholder"
                :required="isFieldRequired(field)"
                :min="field.min"
                :max="field.max"
                :step="field.step"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @input="updateField(field, $event.target.value)"
              />

              <p v-if="field.help" class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ field.help }}</p>
            </div>
          </div>

          <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-5 sm:flex-row sm:justify-end dark:border-slate-800">
            <button
              type="button"
              class="rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
              @click="restoreModalForm"
            >
              Reset Form
            </button>
            <button
              type="button"
              class="rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
              @click="closeModal()"
            >
              Batal
            </button>
            <button
              type="submit"
              class="rounded-full bg-cyan-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400"
            >
              {{ editingId ? `Simpan ${titleCase(entityLabel)}` : `Tambah ${titleCase(entityLabel)}` }}
            </button>
          </div>
        </form>
      </section>
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue'
import SidebarAdmin from '../layout/SidebarAdmin.vue'
import Navbar from '../layout/Navbar.vue'
import { apiRequest } from '../../lib/api'

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  subtitle: {
    type: String,
    default: '',
  },
  entityLabel: {
    type: String,
    required: true,
  },
  storageKey: {
    type: String,
    default: '',
  },
  api: {
    type: Object,
    default: null,
  },
  fields: {
    type: Array,
    required: true,
  },
  columns: {
    type: Array,
    required: true,
  },
  initialItems: {
    type: Array,
    default: () => [],
  },
  primaryField: {
    type: String,
    default: '',
  },
  searchKeys: {
    type: Array,
    default: () => [],
  },
  summaryCards: {
    type: Array,
    default: () => [],
  },
})

const items = ref([])
const search = ref('')
const editingId = ref(null)
const isModalOpen = ref(false)
const editingSnapshot = ref(null)
const isLoading = ref(false)
const feedback = ref({
  type: 'info',
  text: '',
})
const form = reactive({})
const imageFiles = reactive({})
const removedImages = reactive({})

const apiEndpoint = computed(() => String(props.api?.endpoint || ''))
const isApiMode = computed(() => apiEndpoint.value.length > 0)

const normalizedFields = computed(() =>
  props.fields.map((field) => ({
    type: 'text',
    placeholder: '',
    options: [],
    rows: 4,
    step: field.type === 'number' ? 1 : undefined,
    ...field,
  })),
)

const dataSourceNote = computed(() => {
  if (isApiMode.value) {
    return `Data halaman ini diambil dari database (API: ${apiEndpoint.value}).`
  }

  return 'Data halaman ini disimpan di browser dengan localStorage, tanpa database.'
})

const displayedSummaryCards = computed(() => {
  if (props.summaryCards.length) {
    return props.summaryCards.map((card) => ({
      ...card,
      value: typeof card.value === 'function' ? card.value(items.value) : card.value,
    }))
  }

  return [
    {
      label: `Total ${titleCase(props.entityLabel)}`,
      value: items.value.length,
      tone: 'cyan',
      caption: 'Jumlah seluruh data yang tersimpan.',
    },
    {
      label: 'Hasil Pencarian',
      value: filteredItems.value.length,
      tone: 'slate',
      caption: 'Data yang cocok dengan kata kunci saat ini.',
    },
    {
      label: 'Mode Form',
      value: isModalOpen.value ? (editingId.value ? 'Edit' : 'Tambah') : 'Tertutup',
      tone: 'amber',
      caption: 'Status pop-up form yang dipakai untuk tambah dan edit data.',
    },
  ]
})

const searchableKeys = computed(() => {
  if (props.searchKeys.length) {
    return props.searchKeys
  }

  return normalizedFields.value.map((field) => field.key)
})

const filteredItems = computed(() => {
  const keyword = search.value.trim().toLowerCase()

  if (!keyword) {
    return items.value
  }

  return items.value.filter((item) =>
    searchableKeys.value.some((key) => String(item[key] ?? '').toLowerCase().includes(keyword)),
  )
})

const formTitle = computed(() => `${editingId.value ? 'Edit' : 'Tambah'} ${props.entityLabel}`)

const feedbackClass = computed(() => {
  const map = {
    info: 'border-cyan-200 bg-cyan-50 text-cyan-900 dark:border-cyan-500/20 dark:bg-cyan-500/10 dark:text-cyan-100',
    success: 'border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-100',
    error: 'border-rose-200 bg-rose-50 text-rose-900 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-100',
  }

  return map[feedback.value.type] ?? map.info
})

Object.assign(form, createEmptyForm())
resetImageState()

onMounted(async () => {
  await loadItems()

  const readyText = isApiMode.value
    ? `${titleCase(props.entityLabel)} siap dikelola dari database. Klik tombol tambah untuk membuka form.`
    : `${titleCase(props.entityLabel)} siap dikelola. Klik tombol tambah untuk membuka form.`

  setFeedback('info', readyText)

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
  return normalizedFields.value.reduce((accumulator, field) => {
    accumulator[field.key] = field.default ?? ''
    return accumulator
  }, {})
}

function clone(value) {
  return JSON.parse(JSON.stringify(value))
}

function titleCase(text) {
  if (!text) {
    return ''
  }

  return text.charAt(0).toUpperCase() + text.slice(1)
}

function setFeedback(type, text) {
  feedback.value = { type, text }
}

function persistItems() {
  if (isApiMode.value) {
    return
  }

  if (typeof window === 'undefined') {
    return
  }

  window.localStorage.setItem(props.storageKey, JSON.stringify(items.value))
}

async function loadItems() {
  isLoading.value = true

  try {
    if (isApiMode.value) {
      const data = await apiRequest(apiEndpoint.value)
      items.value = Array.isArray(data) ? clone(data) : []
      return
    }

    if (typeof window === 'undefined') {
      items.value = clone(props.initialItems)
      return
    }

    const storedItems = window.localStorage.getItem(props.storageKey)

    if (storedItems) {
      try {
        const parsedItems = JSON.parse(storedItems)

        if (Array.isArray(parsedItems)) {
          items.value = clone(parsedItems)
          return
        }
      } catch (error) {
      }

      window.localStorage.removeItem(props.storageKey)
    }

    items.value = clone(props.initialItems)
    persistItems()
  } catch (error) {
    setFeedback('error', error?.message || 'Gagal memuat data.')
    items.value = []
  } finally {
    isLoading.value = false
  }
}

function updateField(field, rawValue) {
  form[field.key] = field.type === 'number' && rawValue !== '' ? Number(rawValue) : rawValue
}

function clearImageField(field) {
  form[field.key] = ''
  imageFiles[field.key] = null
  removedImages[field.key] = true
}

function readFileAsDataUrl(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader()

    reader.onload = () => resolve(String(reader.result || ''))
    reader.onerror = () => reject(reader.error)

    reader.readAsDataURL(file)
  })
}

async function normalizeImageToSquareDataUrl(file, { size = 512, quality = 0.86 } = {}) {
  if (typeof window === 'undefined') {
    return ''
  }

  try {
    const bitmap = await createImageBitmap(file)
    const side = Math.min(bitmap.width, bitmap.height)
    const sourceX = Math.floor((bitmap.width - side) / 2)
    const sourceY = Math.floor((bitmap.height - side) / 2)

    const canvas = document.createElement('canvas')
    canvas.width = size
    canvas.height = size

    const context = canvas.getContext('2d')
    if (!context) {
      throw new Error('Canvas context unavailable')
    }
    context.drawImage(bitmap, sourceX, sourceY, side, side, 0, 0, size, size)

    if (typeof bitmap.close === 'function') {
      bitmap.close()
    }

    return canvas.toDataURL('image/jpeg', quality)
  } catch (error) {
    // Fallback to raw data URL if bitmap/canvas fails.
    return await readFileAsDataUrl(file)
  }
}

async function handleImageChange(field, event) {
  const file = event?.target?.files?.[0]

  if (!file) {
    return
  }

  if (typeof window === 'undefined') {
    return
  }

  form[field.key] = await normalizeImageToSquareDataUrl(file)
  imageFiles[field.key] = file
  removedImages[field.key] = false

  if (event?.target) {
    event.target.value = ''
  }
}

function buildPayload() {
  return normalizedFields.value.reduce((accumulator, field) => {
    const value = form[field.key]

    if (field.type === 'image' && isApiMode.value) {
      if (removedImages[field.key]) {
        accumulator[field.key] = null
      }

      return accumulator
    }

    if (typeof value === 'string') {
      const trimmed = value.trim()

      if (field.omitIfEmpty && trimmed === '') {
        return accumulator
      }

      accumulator[field.key] = trimmed
      return accumulator
    }

    accumulator[field.key] = value
    return accumulator
  }, {})
}

function normalizeOptions(options = []) {
  return options.map((option) =>
    typeof option === 'object'
      ? option
      : {
          label: option,
          value: option,
        },
  )
}

function validatePayload(payload) {
  const missingField = normalizedFields.value.find((field) => {
    if (!isFieldRequired(field)) {
      return false
    }

    const value = payload[field.key]

    if (field.type === 'number') {
      return value === '' || value === null || Number.isNaN(value)
    }

    return String(value ?? '').trim() === ''
  })

  if (missingField) {
    setFeedback('error', `${missingField.label} wajib diisi sebelum data disimpan.`)
    return false
  }

  return true
}

function isFieldRequired(field) {
  if (editingId.value) {
    if (field.requiredOnEdit !== undefined) {
      return Boolean(field.requiredOnEdit)
    }

    return Boolean(field.required)
  }

  if (field.requiredOnCreate !== undefined) {
    return Boolean(field.requiredOnCreate)
  }

  return Boolean(field.required)
}

function resetFormState() {
  Object.assign(form, createEmptyForm())
  resetImageState()
  editingId.value = null
  editingSnapshot.value = null
}

function resetImageState() {
  for (const field of normalizedFields.value) {
    if (field.type !== 'image') {
      continue
    }

    imageFiles[field.key] = null
    removedImages[field.key] = false
  }
}

function closeModal({ keepFeedback = false } = {}) {
  isModalOpen.value = false
  resetFormState()

  if (!keepFeedback) {
    setFeedback('info', `${titleCase(props.entityLabel)} siap dikelola. Klik tombol tambah untuk membuka form.`)
  }
}

function createId() {
  return `${props.storageKey}-${Date.now()}-${Math.random().toString(16).slice(2, 8)}`
}

function openCreateModal() {
  resetFormState()
  isModalOpen.value = true
  setFeedback('info', `Lengkapi form untuk menambahkan ${props.entityLabel.toLowerCase()} baru.`)
}

function restoreModalForm() {
  if (editingId.value && editingSnapshot.value) {
    Object.assign(form, createEmptyForm(), clone(editingSnapshot.value))
    setFeedback('info', `${titleCase(props.entityLabel)} dikembalikan ke data awal sebelum diedit.`)
    return
  }

  Object.assign(form, createEmptyForm())
  setFeedback('info', `Form tambah ${props.entityLabel.toLowerCase()} sudah dikosongkan.`)
}

function submitForm() {
  const payload = buildPayload()

  if (!validatePayload(payload)) {
    return
  }

  if (isApiMode.value) {
    submitToApi(payload)
    return
  }

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
    setFeedback('success', `${titleCase(props.entityLabel)} berhasil diperbarui.`)
    return
  }

  items.value = [
    {
      id: createId(),
      ...payload,
    },
    ...items.value,
  ]

  persistItems()
  closeModal({ keepFeedback: true })
  setFeedback('success', `${titleCase(props.entityLabel)} baru berhasil ditambahkan.`)
}

function startEdit(item) {
  editingId.value = item.id
  editingSnapshot.value = clone(item)
  Object.assign(form, createEmptyForm(), clone(item))
  isModalOpen.value = true
  setFeedback('info', `${titleCase(props.entityLabel)} siap diedit. Ubah data lalu klik simpan.`)
}

function removeItem(item) {
  const itemLabel = item[props.primaryField || normalizedFields.value[0]?.key] ?? props.entityLabel

  if (typeof window !== 'undefined') {
    const confirmed = window.confirm(`Hapus data ${itemLabel}?`)

    if (!confirmed) {
      return
    }
  }

  if (isApiMode.value) {
    removeFromApi(item)
    return
  }

  items.value = items.value.filter((row) => row.id !== item.id)
  persistItems()

  if (editingId.value === item.id) {
    closeModal({ keepFeedback: true })
  }

  setFeedback('success', `${titleCase(props.entityLabel)} berhasil dihapus.`)
}

async function submitToApi(payload) {
  try {
    isLoading.value = true

    const hasFile = Object.values(imageFiles).some(Boolean)
    const body = hasFile ? toFormData(payload) : payload

    if (editingId.value) {
      const updated = await apiRequest(`${apiEndpoint.value}/${editingId.value}`, {
        method: 'PUT',
        body,
      })

      items.value = items.value.map((item) => (item.id === editingId.value ? updated : item))
      closeModal({ keepFeedback: true })
      setFeedback('success', `${titleCase(props.entityLabel)} berhasil diperbarui.`)
      return
    }

    const created = await apiRequest(apiEndpoint.value, {
      method: 'POST',
      body,
    })

    items.value = [created, ...items.value]
    closeModal({ keepFeedback: true })
    setFeedback('success', `${titleCase(props.entityLabel)} baru berhasil ditambahkan.`)
  } catch (error) {
    setFeedback('error', error?.message || 'Gagal menyimpan data.')
  } finally {
    isLoading.value = false
  }
}

function toFormData(payload) {
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

  for (const [key, file] of Object.entries(imageFiles)) {
    if (!file) {
      continue
    }

    formData.append(key, file)
  }

  return formData
}

async function removeFromApi(item) {
  try {
    isLoading.value = true
    await apiRequest(`${apiEndpoint.value}/${item.id}`, { method: 'DELETE' })

    items.value = items.value.filter((row) => row.id !== item.id)

    if (editingId.value === item.id) {
      closeModal({ keepFeedback: true })
    }

    setFeedback('success', `${titleCase(props.entityLabel)} berhasil dihapus.`)
  } catch (error) {
    setFeedback('error', error?.message || 'Gagal menghapus data.')
  } finally {
    isLoading.value = false
  }
}

function getCellDisplay(column, item) {
  const value = item[column.key]

  if (typeof column.format === 'function') {
    const formatted = column.format(value, item)
    return column.truncate ? truncateText(formatted, column.truncateLength) : formatted
  }

  if (value === null || value === undefined || value === '') {
    return column.empty ?? '-'
  }

  return column.truncate ? truncateText(value, column.truncateLength) : value
}

function cellTextClass(column) {
  return column.truncate ? 'inline-block max-w-full truncate align-bottom' : ''
}

function truncateText(value, maxLength = 60) {
  const text = String(value ?? '').trim()

  if (!text) {
    return '-'
  }

  if (text.length <= maxLength) {
    return text
  }

  return `${text.slice(0, Math.max(0, maxLength - 3)).trimEnd()}...`
}

function resolveBadgeTone(column, item) {
  const rawValue = item[column.key]

  if (typeof column.badgeTone === 'function') {
    return column.badgeTone(rawValue, item)
  }

  if (column.badges && rawValue in column.badges) {
    return column.badges[rawValue]
  }

  return 'slate'
}

function handleWindowKeydown(event) {
  if (event.key === 'Escape' && isModalOpen.value) {
    closeModal()
  }
}

function cardToneClass(tone) {
  const classes = {
    cyan: 'bg-blue-100 dark:bg-blue-900/40 border-blue-300 dark:border-blue-700/50 text-slate-900 dark:text-slate-100',
    emerald: 'bg-green-100 dark:bg-green-900/40 border-green-300 dark:border-green-700/50 text-slate-900 dark:text-slate-100',
    amber: 'bg-yellow-100 dark:bg-yellow-900/40 border-yellow-300 dark:border-yellow-700/50 text-slate-900 dark:text-slate-100',
    rose: 'bg-red-100 dark:bg-red-900/40 border-red-300 dark:border-red-700/50 text-slate-900 dark:text-slate-100',
    violet: 'bg-purple-100 dark:bg-purple-900/40 border-purple-300 dark:border-purple-700/50 text-slate-900 dark:text-slate-100',
    slate: 'bg-cyan-50 dark:bg-cyan-500/10 border-cyan-200 dark:border-cyan-500/20 text-cyan-950 dark:text-cyan-100',
  }

  return classes[tone] ?? classes.slate
}

function badgeToneClass(tone) {
  const classes = {
    cyan: 'bg-cyan-100 text-cyan-800 dark:bg-cyan-500/15 dark:text-cyan-200',
    emerald: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-200',
    amber: 'bg-amber-100 text-amber-800 dark:bg-amber-500/15 dark:text-amber-200',
    rose: 'bg-rose-100 text-rose-800 dark:bg-rose-500/15 dark:text-rose-200',
    slate: 'bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-100',
    violet: 'bg-violet-100 text-violet-800 dark:bg-violet-500/15 dark:text-violet-200',
  }

  return classes[tone] ?? classes.slate
}
</script>
