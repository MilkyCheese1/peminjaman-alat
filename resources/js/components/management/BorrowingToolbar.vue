<template>
  <div class="mb-5 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
    <div>
      <h2 class="text-xl font-bold text-slate-900 dark:text-white">Daftar Peminjaman</h2>
      <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
        Total {{ totalItems }} transaksi, tampil {{ filteredItemsCount }} transaksi.
      </p>
    </div>

    <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
      <input
        :value="search"
        type="search"
        placeholder="Cari peminjam, alat, divisi, atau kode..."
        class="w-full rounded-full border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 lg:w-72 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
        @input="$emit('update-search', $event.target.value)"
      />

      <select
        :value="statusFilter"
        class="rounded-full border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
        @change="$emit('update-status-filter', $event.target.value)"
      >
        <option value="Semua">Semua Status</option>
        <option v-for="status in statusOptions" :key="status" :value="status">
          {{ status }}
        </option>
      </select>

      <button
        type="button"
        class="rounded-full bg-cyan-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400"
        @click="$emit('add')"
      >
        - Tambah -
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  search: {
    type: String,
    default: '',
  },
  statusFilter: {
    type: String,
    default: 'Semua',
  },
  statusOptions: {
    type: Array,
    default: () => [],
  },
  totalItems: {
    type: Number,
    default: 0,
  },
  filteredItemsCount: {
    type: Number,
    default: 0,
  },
})

defineEmits(['update-search', 'update-status-filter', 'add'])
</script>
