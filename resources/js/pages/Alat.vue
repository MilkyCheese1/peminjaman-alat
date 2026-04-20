<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <SidebarPeminjam />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-6">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Jelajahi Alat</h1>
          <p class="text-slate-700 dark:text-slate-300">Temukan alat yang tersedia untuk peminjaman.</p>
        </div>

        <div class="mb-8">
          <div class="flex gap-4 mb-6">
            <input type="text" placeholder="Cari alat..." class="flex-1 rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-500 focus:border-cyan-500 focus:ring-cyan-500/20 focus:outline-none" />
          </div>
          <div class="app-card app-card--cyan p-6">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-6">Filter Alat</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm mb-2 text-slate-700 dark:text-slate-300">Kategori</label>
                <select class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 text-slate-900 dark:text-slate-100 focus:border-cyan-500 focus:ring-cyan-500/20 focus:outline-none">
                  <option>Semua Kategori</option>
                </select>
              </div>
              <div>
                <label class="block text-sm mb-2 text-slate-700 dark:text-slate-300">Status</label>
                <select class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 text-slate-900 dark:text-slate-100 focus:border-cyan-500 focus:ring-cyan-500/20 focus:outline-none">
                  <option>Semua Status</option>
                  <option>Tersedia</option>
                  <option>Dipinjam</option>
                  <option>Maintenance</option>
                </select>
              </div>
            </div>
            <div class="mt-6 flex gap-3">
              <button class="flex-1 inline-flex items-center justify-center rounded-full bg-cyan-500 px-5 py-3 text-sm font-semibold text-white dark:text-slate-950 transition hover:bg-cyan-600 dark:hover:bg-cyan-400">Terapkan Filter</button>
              <button class="flex-1 inline-flex items-center justify-center rounded-full bg-slate-300 dark:bg-slate-800 px-5 py-3 text-sm font-semibold text-slate-900 dark:text-slate-200 transition hover:bg-slate-400 dark:hover:bg-slate-700">Reset</button>
            </div>
          </div>
        </div>

        <section>
          <div class="flex items-center justify-between mb-6">
            <div>
              <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Daftar Alat Tersedia</h2>
              <p class="text-slate-600 dark:text-slate-400">Pilih alat yang ingin Anda pinjam dan lihat detailnya.</p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="app-card app-card--cyan p-6 text-slate-700 dark:text-slate-300">
              Belum ada data alat untuk ditampilkan.
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>
</template>

<script>
import SidebarPeminjam from '../components/layout/SidebarPeminjam.vue';
import Navbar from '../components/layout/Navbar.vue';

export default {
  name: 'Alat',
  components: {
    SidebarPeminjam,
    Navbar,
  },
};
</script>

<style scoped>
/* Custom styles if needed */
</style>
