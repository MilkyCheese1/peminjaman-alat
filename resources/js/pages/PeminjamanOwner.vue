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

          <div class="overflow-x-auto">
            <table class="min-w-full">
              <thead>
                <tr class="border-b border-slate-200 dark:border-slate-800">
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Kode</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Peminjam</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Alat</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Divisi</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Status</th>
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
                    {{ item.tanggalPinjam }} → {{ item.tanggalKembaliRencana }}
                  </td>
                  <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-200">
                    <span :class="['inline-flex rounded-full px-3 py-1 text-xs font-semibold', statusToneClass(item.status)]">
                      {{ item.status }}
                    </span>
                  </td>
                  <td class="px-4 py-4 text-right text-sm text-slate-700 dark:text-slate-200">{{ formatRupiah(item.biaya) }}</td>
                </tr>

                <tr v-if="!filteredItems.length">
                  <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-600 dark:text-slate-300">
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

function formatRupiah(value) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
  }).format(Number(value || 0))
}
</script>

