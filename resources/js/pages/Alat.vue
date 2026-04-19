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
          <div class="bg-white dark:bg-slate-900/90 border border-slate-300 dark:border-white/10 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-6">Filter Alat</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm mb-2 text-slate-700 dark:text-slate-300">Kategori</label>
                <select class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 text-slate-900 dark:text-slate-100 focus:border-cyan-500 focus:ring-cyan-500/20 focus:outline-none">
                  <option>Semua Kategori</option>
                  <option>Elektronik</option>
                  <option>Mesin</option>
                  <option>Alat Tangan</option>
                  <option>Pengecatan</option>
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
            <div class="bg-white dark:bg-slate-900/90 border border-slate-300 dark:border-white/10 rounded-3xl overflow-hidden shadow-2xl shadow-slate-950/40">
              <div class="h-44 bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-500">Gambar Alat</div>
              <div class="p-5">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Bor Listrik</h3>
                <p class="mt-2 text-slate-600 dark:text-slate-300">Alat untuk membuat lubang dengan mudah.</p>
                <div class="mt-4 flex items-center justify-between text-slate-600 dark:text-slate-300">
                  <span class="font-semibold text-emerald-600 dark:text-emerald-300">Tersedia</span>
                  <span>Rp 50,000/hari</span>
                </div>
                <button class="mt-5 w-full rounded-full bg-cyan-500 px-4 py-3 text-sm font-semibold text-white dark:text-slate-950 hover:bg-cyan-600 dark:hover:bg-cyan-400 transition">Pinjam Sekarang</button>
              </div>
            </div>

            <div class="bg-white dark:bg-slate-900/90 border border-slate-300 dark:border-white/10 rounded-3xl overflow-hidden shadow-2xl shadow-slate-950/40">
              <div class="h-44 bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-500">Gambar Alat</div>
              <div class="p-5">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Gergaji Mesin</h3>
                <p class="mt-2 text-slate-600 dark:text-slate-300">Untuk memotong kayu dengan presisi.</p>
                <div class="mt-4 flex items-center justify-between text-slate-600 dark:text-slate-300">
                  <span class="font-semibold text-amber-600 dark:text-amber-300">Dipinjam</span>
                  <span>Rp 75,000/hari</span>
                </div>
                <button class="mt-5 w-full rounded-full bg-slate-300 dark:bg-slate-700 px-4 py-3 text-sm font-semibold text-slate-900 dark:text-white cursor-not-allowed">Tidak Tersedia</button>
              </div>
            </div>

            <div class="bg-white dark:bg-slate-900/90 border border-slate-300 dark:border-white/10 rounded-3xl overflow-hidden shadow-2xl shadow-slate-950/40">
              <div class="h-44 bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-500">Gambar Alat</div>
              <div class="p-5">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Obeng Set</h3>
                <p class="mt-2 text-slate-600 dark:text-slate-300">Set obeng berbagai ukuran untuk perbaikan detail.</p>
                <div class="mt-4 flex items-center justify-between text-slate-600 dark:text-slate-300">
                  <span class="font-semibold text-emerald-600 dark:text-emerald-300">Tersedia</span>
                  <span>Rp 30,000/hari</span>
                </div>
                <button class="mt-5 w-full rounded-full bg-cyan-500 px-4 py-3 text-sm font-semibold text-white dark:text-slate-950 hover:bg-cyan-600 dark:hover:bg-cyan-400 transition">Pinjam Sekarang</button>
              </div>
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
