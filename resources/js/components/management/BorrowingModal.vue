<template>
  <div
    v-if="isOpen"
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4"
    @click.self="$emit('close')"
  >
    <section class="flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-[2rem] border border-cyan-200 bg-white shadow-2xl shadow-slate-950/40 dark:border-cyan-500/20 dark:bg-slate-900">
      <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
        <div>
          <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-600 dark:text-cyan-300">
            {{ editingId ? 'Mode Edit' : 'Mode Tambah' }}
          </p>
          <h2 class="mt-2 text-xl font-bold text-slate-900 dark:text-white">
            {{ editingId ? 'Edit Peminjaman' : 'Tambah Peminjaman' }}
          </h2>
          <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
            Lengkapi data transaksi. Kode peminjaman akan dibuat otomatis untuk transaksi baru.
          </p>
        </div>

        <button
          type="button"
          class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
          @click="$emit('close')"
        >
          Tutup
        </button>
      </div>

      <form class="min-h-0 flex-1 flex flex-col" @submit.prevent="$emit('submit')">
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
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Status Saat Dipinjam</label>
              <select
                :value="form.status"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @change="form.status = $event.target.value"
              >
                <option v-for="status in staffBorrowingStatusOptions" :key="status" :value="status">
                  {{ status }}
                </option>
              </select>
              <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                Status ini menandai fase transaksi ketika barang sudah diserahkan ke peminjam.
              </p>
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
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Bukti Pengambilan</label>
              <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <input
                  type="file"
                  accept="image/*"
                  class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 sm:flex-1"
                  @change="$emit('image-change', $event)"
                />
                <button
                  v-if="form.gambar"
                  type="button"
                  class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
                  @click="$emit('clear-image')"
                >
                  Hapus Bukti
                </button>
              </div>

              <div
                v-if="form.gambar"
                class="mt-3 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-950"
              >
                <img :src="form.gambar" alt="Preview bukti pengambilan" class="h-44 w-full rounded-xl object-cover" />
              </div>

              <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Opsional. Bukti ini akan muncul di laporan admin dan owner.</p>
            </div>
          </div>
        </div>

        <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-5 sm:flex-row sm:justify-end dark:border-slate-800">
          <button
            type="button"
            class="rounded-full border border-slate-300 px-5 py-3 font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
            @click="$emit('restore-form')"
          >
            Reset Form
          </button>
          <button
            type="button"
            class="rounded-full border border-slate-300 px-5 py-3 font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
            @click="$emit('close')"
          >
            Batal
          </button>
          <button
            type="submit"
            class="rounded-full bg-cyan-500 px-5 py-3 font-semibold text-slate-950 transition hover:bg-cyan-400"
            :disabled="isSaving"
          >
            {{ editingId ? 'Simpan Perubahan' : 'Tambah Peminjaman' }}
          </button>
        </div>
      </form>
    </section>
  </div>
</template>

<script setup>
defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
  editingId: {
    type: [String, Number],
    default: null,
  },
  form: {
    type: Object,
    required: true,
  },
  staffBorrowingStatusOptions: {
    type: Array,
    default: () => [],
  },
  isSaving: {
    type: Boolean,
    default: false,
  },
})

defineEmits(['close', 'submit', 'restore-form', 'image-change', 'clear-image'])
</script>
