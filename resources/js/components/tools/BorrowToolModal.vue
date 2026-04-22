<template>
  <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <button type="button" class="absolute inset-0 bg-slate-950/70" aria-label="Tutup" @click="emitClose" />

    <div
      class="modal-scroll relative w-full max-w-5xl max-h-[90vh] overflow-y-auto overflow-x-hidden rounded-[2.25rem] border border-cyan-200 bg-white shadow-2xl shadow-slate-950/60 dark:border-cyan-500/20 dark:bg-slate-950"
      role="dialog"
      aria-modal="true"
    >
      <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
        <div class="min-w-0">
          <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">
            {{ isDetailMode ? 'Detail Alat' : 'Pengajuan Peminjaman' }}
          </p>
          <h2 class="mt-1 truncate text-xl font-extrabold text-slate-900 dark:text-white">
            {{ tool?.namaAlat || 'Detail Alat' }}
          </h2>
        </div>
        <button
          type="button"
          class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-700 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
          aria-label="Tutup"
          @click="emitClose"
        >
          ✕
        </button>
      </div>

      <div class="grid grid-cols-1 gap-0 lg:grid-cols-2">
        <section class="border-b border-slate-200 px-6 py-6 dark:border-slate-800 lg:border-b-0 lg:border-r">
          <div class="mb-5 overflow-hidden rounded-3xl bg-gradient-to-b from-sky-200 via-sky-100 to-white dark:from-sky-900/40 dark:via-slate-950 dark:to-slate-900">
            <div class="relative w-full pt-[100%]">
              <img
                v-if="tool?.gambar"
                :src="tool.gambar"
                :alt="`Gambar ${tool.namaAlat}`"
                class="absolute inset-0 h-full w-full object-cover"
              />
              <div
                v-else
                class="absolute inset-0 flex items-center justify-center text-sm font-semibold text-slate-600 dark:text-slate-300"
              >
                Tidak ada gambar
              </div>
            </div>
          </div>

          <div class="space-y-4">
            <div>
              <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Deskripsi</p>
              <p class="mt-2 text-sm text-slate-700 dark:text-slate-200">
                {{ tool?.deskripsi || 'Belum ada deskripsi untuk alat ini.' }}
              </p>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div class="rounded-3xl bg-slate-100 px-5 py-4 dark:bg-slate-800/60">
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Stok</p>
                <p class="mt-2 text-lg font-extrabold text-slate-900 dark:text-white">{{ Number(tool?.stok || 0) }} unit</p>
              </div>
              <div class="rounded-3xl bg-slate-100 px-5 py-4 dark:bg-slate-800/60">
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tersedia</p>
                <p class="mt-2 text-lg font-extrabold" :class="availabilityClass">
                  {{ isAvailable ? 'Ya' : 'Tidak' }}
                </p>
              </div>
              <div class="col-span-2 rounded-3xl bg-slate-100 px-5 py-4 dark:bg-slate-800/60">
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Aturan Denda</p>
                <div class="mt-2 space-y-2 text-sm text-slate-700 dark:text-slate-200">
                  <p>Kerusakan, kehilangan, dan keterlambatan akan dihitung otomatis oleh sistem saat pengembalian.</p>
                  <p class="text-xs text-slate-500 dark:text-slate-400">
                    Staff akan memeriksa kondisi barang dan menetapkan laporan pengembalian setelah alat kembali.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section v-if="isDetailMode" class="px-6 py-6">
          <div class="space-y-5">
            <div class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-900/40">
              <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Info Singkat</p>
              <div class="mt-3 grid grid-cols-1 gap-3 text-sm text-slate-700 dark:text-slate-200">
                <div class="flex items-center justify-between gap-4">
                  <span class="text-slate-500 dark:text-slate-400">Kategori</span>
                  <span class="font-semibold">{{ tool?.kategori || '-' }}</span>
                </div>
                <div class="flex items-center justify-between gap-4">
                  <span class="text-slate-500 dark:text-slate-400">Kondisi</span>
                  <span class="font-semibold">{{ tool?.kondisi || '-' }}</span>
                </div>
                <div class="flex items-center justify-between gap-4">
                  <span class="text-slate-500 dark:text-slate-400">Status</span>
                  <span class="font-semibold" :class="availabilityClass">{{ tool?.status || '-' }}</span>
                </div>
                <div class="flex items-center justify-between gap-4">
                  <span class="text-slate-500 dark:text-slate-400">Stok</span>
                  <span class="font-semibold">{{ Number(tool?.stok || 0) }} unit</span>
                </div>
                <div class="flex items-center justify-between gap-4">
                  <span class="text-slate-500 dark:text-slate-400">Lokasi</span>
                  <span class="font-semibold">{{ tool?.lokasi || '-' }}</span>
                </div>
              </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-900/40">
              <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Catatan</p>
              <p class="mt-2 text-sm text-slate-700 dark:text-slate-200">
                Gunakan tombol di bawah untuk menutup pop up ini. Jika alat ingin dipinjam, kembali ke tombol Pinjam pada kartu alat.
              </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
              <button
                type="button"
                class="inline-flex items-center justify-center rounded-full bg-cyan-500 px-6 py-3 text-sm font-extrabold text-white transition hover:bg-cyan-600 disabled:cursor-not-allowed disabled:opacity-60 dark:text-slate-950 dark:hover:bg-cyan-400"
                :disabled="!isAvailable"
                @click="$emit('borrow', tool)"
              >
                Pinjam Sekarang
              </button>
              <button
                type="button"
                class="inline-flex items-center justify-center rounded-full bg-slate-200 px-6 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-300 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                @click="emitClose"
              >
                Tutup
              </button>
            </div>
          </div>
        </section>

        <section v-else class="px-6 py-6">
          <form class="space-y-5" @submit.prevent="submit">
            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Alasan peminjaman</label>
              <textarea
                v-model="form.alasan"
                rows="4"
                class="w-full resize-none rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                placeholder="Contoh: untuk kebutuhan pengujian alat di lab..."
              />
              <p v-if="errors.alasan" class="mt-2 text-xs font-semibold text-rose-600">{{ errors.alasan }}</p>
            </div>

            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Jumlah hari peminjaman</label>
              <input
                v-model.number="form.hari"
                type="number"
                min="1"
                step="1"
                class="w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                placeholder="Contoh: 3"
              />
              <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                Peminjaman dimulai setelah staff menyetujui pengajuan.
              </p>
              <p v-if="errors.hari" class="mt-2 text-xs font-semibold text-rose-600">{{ errors.hari }}</p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 dark:border-slate-800 dark:bg-slate-900/40">
              <p class="text-sm font-bold text-slate-900 dark:text-white">Kebijakan Peminjaman</p>
              <ul class="mt-3 list-disc space-y-2 pl-5 text-sm text-slate-700 dark:text-slate-200">
                <li>Alat wajib dikembalikan sesuai jadwal yang disepakati.</li>
                <li>Keterlambatan akan dikenakan denda sesuai ketentuan.</li>
                <li>Kerusakan atau kehilangan menjadi tanggung jawab peminjam.</li>
              </ul>
              <label class="mt-4 flex items-start gap-3 text-sm text-slate-700 dark:text-slate-200">
                <input v-model="form.policy" type="checkbox" class="mt-1 h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500 dark:border-slate-700" />
                <span>Saya sudah membaca dan menyetujui kebijakan peminjaman di atas.</span>
              </label>
              <p v-if="errors.policy" class="mt-2 text-xs font-semibold text-rose-600">{{ errors.policy }}</p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
              <button
                type="button"
                class="inline-flex items-center justify-center rounded-full bg-slate-200 px-6 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-300 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                @click="emitClose"
              >
                Batal
              </button>
              <button
                type="submit"
                class="inline-flex items-center justify-center rounded-full bg-cyan-500 px-6 py-3 text-sm font-extrabold text-white transition hover:bg-cyan-600 disabled:cursor-not-allowed disabled:opacity-60 dark:text-slate-950 dark:hover:bg-cyan-400"
                :disabled="!isAvailable"
              >
                Ajukan
              </button>
            </div>

            <p v-if="!isAvailable" class="text-xs font-semibold text-amber-600">
              Alat sedang tidak tersedia atau stok habis.
            </p>
          </form>
        </section>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, reactive, watch } from 'vue'

const props = defineProps({
  open: {
    type: Boolean,
    default: false,
  },
  tool: {
    type: Object,
    default: null,
  },
  mode: {
    type: String,
    default: 'borrow',
    validator: (value) => ['borrow', 'detail'].includes(value),
  },
})

const emit = defineEmits(['close', 'submit', 'borrow'])

const form = reactive({
  alasan: '',
  hari: 1,
  policy: false,
})

const errors = reactive({
  alasan: '',
  hari: '',
  policy: '',
})

const isAvailable = computed(() => props.tool?.status === 'Tersedia' && Number(props.tool?.stok || 0) > 0)

const availabilityClass = computed(() => (isAvailable.value ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'))
const isDetailMode = computed(() => props.mode === 'detail')

watch(
  () => [props.open, props.tool?.id],
  () => {
    if (!props.open) {
      return
    }

    form.alasan = ''
    form.hari = 1
    form.policy = false
    errors.alasan = ''
    errors.hari = ''
    errors.policy = ''
  },
)

function emitClose() {
  emit('close')
}

function validate() {
  errors.alasan = ''
  errors.hari = ''
  errors.policy = ''

  const alasan = String(form.alasan || '').trim()
  const hari = Number(form.hari || 0)

  if (!alasan) {
    errors.alasan = 'Alasan peminjaman wajib diisi.'
  } else if (alasan.length < 10) {
    errors.alasan = 'Alasan peminjaman minimal 10 karakter.'
  }

  if (!Number.isFinite(hari) || hari < 1) {
    errors.hari = 'Jumlah hari minimal 1.'
  } else if (hari > 30) {
    errors.hari = 'Jumlah hari maksimal 30.'
  }

  if (!form.policy) {
    errors.policy = 'Anda harus menyetujui kebijakan peminjaman.'
  }

  return (
    !errors.alasan &&
    !errors.hari &&
    !errors.policy
  )
}

function submit() {
  if (isDetailMode.value) {
    emit('borrow', props.tool)
    return
  }

  if (!isAvailable.value) {
    return
  }

  if (!validate()) {
    return
  }

  emit('submit', {
    toolId: props.tool?.id,
    alasan: String(form.alasan || '').trim(),
    hari: Number(form.hari || 0),
    policy: Boolean(form.policy),
  })
}
 
</script>

<style scoped>
.modal-scroll {
  scrollbar-width: none;
  -ms-overflow-style: none;
}

.modal-scroll::-webkit-scrollbar {
  display: none;
}
</style>
