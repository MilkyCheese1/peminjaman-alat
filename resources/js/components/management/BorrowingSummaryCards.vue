<template>
  <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
    <article class="bg-blue-100 dark:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
      <p class="text-sm font-medium opacity-80">Total Peminjaman</p>
      <p class="mt-3 text-3xl font-bold">{{ totalItems }}</p>
      <p class="mt-2 text-sm opacity-80">Semua transaksi peminjaman yang tersimpan di database.</p>
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
      <p class="mt-3 text-3xl font-bold">{{ totalBiayaLabel }}</p>
      <p class="mt-2 text-sm opacity-80">Akumulasi nominal seluruh transaksi.</p>
    </article>
  </div>
</template>

<script setup>
defineProps({
  totalItems: {
    type: Number,
    default: 0,
  },
  pendingCount: {
    type: Number,
    default: 0,
  },
  activeCount: {
    type: Number,
    default: 0,
  },
  totalBiayaLabel: {
    type: String,
    default: 'Rp 0',
  },
})
</script>
