<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <SidebarOwner />

    <div class="flex-1 flex flex-col">
      <Navbar />

      <main class="flex-1 p-6">
        <div class="mb-8 flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
          <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Peminjaman</h1>
            <p class="mt-2 text-slate-700 dark:text-slate-300">Pantau seluruh transaksi peminjaman yang tercatat.</p>
          </div>
        </div>

        <section class="app-card app-card--cyan p-6">
          <div class="mb-5 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
              <h2 class="text-xl font-bold text-slate-900 dark:text-white">Daftar Transaksi</h2>
              <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Total {{ items.length }} transaksi, tampil {{ filteredItems.length }} transaksi.
              </p>
            </div>

            <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
              <input
                :value="search"
                type="search"
                placeholder="Cari kode, peminjam, alat, divisi..."
                class="w-full rounded-full border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 lg:w-80 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                @input="search = $event.target.value"
              />
            </div>
          </div>

          <div class="space-y-4 lg:hidden">
            <article
              v-for="item in filteredItems"
              :key="item.id"
              class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900"
            >
              <div class="flex items-start justify-between gap-4">
                <div>
                  <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ item.kode }}</p>
                  <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ item.namaPeminjam }}</p>
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

              <div class="mt-4 space-y-2 text-sm text-slate-700 dark:text-slate-200">
                <p><span class="font-semibold">Alat:</span> {{ item.namaAlat }}</p>
                <p><span class="font-semibold">Divisi:</span> {{ item.divisi }}</p>
                <p><span class="font-semibold">Tanggal:</span> {{ item.tanggalPinjam }} - {{ item.tanggalKembaliRencana }}</p>
                <p><span class="font-semibold">Kembali:</span> {{ resolveReturnStatusBadge(item).label }}</p>
                <p><span class="font-semibold">Biaya:</span> {{ formatRupiah(item.biaya) }}</p>
              </div>

              <div class="mt-4 flex flex-col gap-2">
                <div class="flex flex-col gap-2">
                  <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Ambil</div>
                  <img
                    v-if="item.buktiPengambilan || item.gambar"
                    :src="item.buktiPengambilan || item.gambar"
                    alt="Bukti pengambilan"
                    class="h-32 w-full rounded-2xl border border-slate-200 object-cover dark:border-slate-700"
                  />
                  <span v-else class="text-xs text-slate-500 dark:text-slate-400">-</span>
                </div>
                <div class="flex flex-col gap-2">
                  <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Kembali</div>
                  <img
                    v-if="item.buktiPengembalian"
                    :src="item.buktiPengembalian"
                    alt="Bukti pengembalian"
                    class="h-32 w-full rounded-2xl border border-slate-200 object-cover dark:border-slate-700"
                  />
                  <span v-else class="text-xs text-slate-500 dark:text-slate-400">-</span>
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
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Divisi</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Status</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Bukti</th>
                  <th class="px-4 py-3 text-right text-sm font-semibold text-slate-700 dark:text-slate-300">Biaya</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="item in filteredItems"
                  :key="item.id"
                  class="border-b border-slate-200 transition hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800/40"
                >
                  <td class="px-4 py-4 text-sm font-semibold text-slate-900 dark:text-slate-100">{{ item.kode }}</td>
                  <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-200">{{ item.namaPeminjam }}</td>
                  <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-200">{{ item.namaAlat }}</td>
                  <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-200">{{ item.divisi }}</td>
                  <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-200">
                    {{ item.tanggalPinjam }} - {{ item.tanggalKembaliRencana }}
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
                    <div class="mt-2">
                      <span
                        :class="[
                          'inline-flex items-center gap-2 text-xs font-semibold tracking-wide',
                          resolveReturnStatusBadge(item).toneClass,
                        ]"
                      >
                        <span :class="resolveReturnStatusBadge(item).iconClass" aria-hidden="true"></span>
                        {{ resolveReturnStatusBadge(item).label }}
                      </span>
                    </div>
                  </td>
                  <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-200">
                    <div class="flex flex-col gap-2">
                      <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Ambil</div>
                      <img
                        v-if="item.buktiPengambilan || item.gambar"
                        :src="item.buktiPengambilan || item.gambar"
                        alt="Bukti pengambilan"
                        class="h-16 w-24 rounded-xl border border-slate-200 object-cover dark:border-slate-700"
                      />
                      <span v-else class="text-xs text-slate-500 dark:text-slate-400">-</span>

                      <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Kembali</div>
                      <img
                        v-if="item.buktiPengembalian"
                        :src="item.buktiPengembalian"
                        alt="Bukti pengembalian"
                        class="h-16 w-24 rounded-xl border border-slate-200 object-cover dark:border-slate-700"
                      />
                      <span v-else class="text-xs text-slate-500 dark:text-slate-400">-</span>
                    </div>
                  </td>
                  <td class="px-4 py-4 text-right text-sm text-slate-700 dark:text-slate-200">{{ formatRupiah(item.biaya) }}</td>
                </tr>

                <tr v-if="!filteredItems.length">
                  <td colspan="8" class="px-4 py-8 text-center text-sm text-slate-600 dark:text-slate-300">
                    Tidak ada transaksi yang cocok.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import SidebarOwner from '../components/layout/SidebarOwner.vue'
import Navbar from '../components/layout/Navbar.vue'
import { apiRequest } from '../lib/api'
import { resolveBorrowingStatusBadge } from '../utils/borrowingStatusBadge'
import { resolveReturnStatusBadge } from '../utils/returnStatusBadge'

const items = ref([])
const search = ref('')

const filteredItems = computed(() => {
  const keyword = search.value.trim().toLowerCase()

  if (!keyword) {
    return items.value
  }

  return items.value.filter((item) =>
    [item.kode, item.namaPeminjam, item.namaAlat, item.divisi, item.kategori, item.petugas].some((value) =>
      String(value ?? '').toLowerCase().includes(keyword),
    ),
  )
})

onMounted(async () => {
  try {
    const data = await apiRequest('/api/borrowings')
    items.value = Array.isArray(data) ? data : []
  } catch (error) {
    items.value = []
  }
})

function formatRupiah(value) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
  }).format(Number(value || 0))
}

</script>
