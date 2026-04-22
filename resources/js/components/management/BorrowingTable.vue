<template>
  <div class="space-y-4 lg:hidden">
    <article
      v-for="item in items"
      :key="item.id"
      class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900"
    >
      <div class="flex items-start justify-between gap-4">
        <div>
          <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ item.kode }}</p>
          <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ item.petugas }}</p>
        </div>
        <span
          :class="[
            'inline-flex items-center gap-2 text-xs font-semibold tracking-wide',
            resolveBorrowingStatusBadge(item.status).toneClass,
          ]"
        >
          <span :class="resolveBorrowingStatusBadge(item.status).iconClass" aria-hidden="true"></span>
          {{ resolveBorrowingStatusBadge(item.status).label }}
        </span>
      </div>

      <div class="mt-4 grid grid-cols-1 gap-3 text-sm text-slate-700 dark:text-slate-200">
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Peminjam</p>
          <p class="mt-1 font-semibold">{{ item.namaPeminjam }}</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">{{ item.divisi }}</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">{{ item.keperluan }}</p>
        </div>
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Alat</p>
          <p class="mt-1 font-semibold">{{ item.namaAlat }}</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">{{ item.kategori }}</p>
        </div>
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Tanggal</p>
          <p class="mt-1">Pinjam: {{ formatDateIndonesia(item.tanggalPinjam) }}</p>
          <p>Rencana: {{ formatDateIndonesia(item.tanggalKembaliRencana) }}</p>
          <p>Aktual: {{ formatDateIndonesia(item.tanggalKembaliAktual) }}</p>
        </div>
        <div class="flex items-center justify-between gap-3 pt-2">
          <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Denda</p>
            <p class="mt-1 text-base font-bold text-slate-900 dark:text-white">{{ formatRupiah(item.biaya) }}</p>
          </div>
          <div class="flex flex-wrap justify-end gap-2">
            <button
              v-for="action in getQuickActions(item)"
              :key="action.key"
              type="button"
              :class="['rounded-full border px-3 py-2 text-xs font-semibold transition', action.className]"
              @click="$emit('quick-action', item, action.key)"
            >
              {{ action.label }}
            </button>
            <button
              type="button"
              class="rounded-full border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
              @click="$emit('edit', item)"
            >
              Edit
            </button>
          </div>
        </div>
      </div>
    </article>
  </div>

  <div class="hidden overflow-x-auto lg:block">
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
          v-for="item in items"
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
            <span
              :class="[
                'inline-flex items-center gap-2 text-xs font-semibold tracking-wide',
                resolveBorrowingStatusBadge(item.status).toneClass,
              ]"
            >
              <span :class="resolveBorrowingStatusBadge(item.status).iconClass" aria-hidden="true"></span>
              {{ resolveBorrowingStatusBadge(item.status).label }}
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
                @click="$emit('quick-action', item, action.key)"
              >
                {{ action.label }}
              </button>
              <button
                type="button"
                class="rounded-full border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
                @click="$emit('edit', item)"
              >
                Edit
              </button>
              <button
                type="button"
                class="rounded-full border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-700 transition hover:bg-rose-50 dark:border-rose-500/20 dark:text-rose-200 dark:hover:bg-rose-500/10"
                @click="$emit('remove', item)"
              >
                Hapus
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <div
      v-if="!items.length"
      class="rounded-3xl border border-dashed border-slate-300 px-6 py-12 text-center dark:border-slate-700"
    >
      <h3 class="text-lg font-bold text-slate-900 dark:text-white">Transaksi tidak ditemukan</h3>
      <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
        Coba ubah pencarian atau filter status, atau tambahkan transaksi baru dari tombol di atas.
      </p>
    </div>
  </div>
</template>

<script setup>
import { resolveBorrowingStatusBadge } from '../../utils/borrowingStatusBadge'

defineProps({
  items: {
    type: Array,
    default: () => [],
  },
  formatDateIndonesia: {
    type: Function,
    required: true,
  },
  formatRupiah: {
    type: Function,
    required: true,
  },
  getQuickActions: {
    type: Function,
    required: true,
  },
})

defineEmits(['quick-action', 'edit', 'remove'])
</script>
