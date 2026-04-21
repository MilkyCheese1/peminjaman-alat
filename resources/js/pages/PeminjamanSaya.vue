<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col lg:flex-row">
    <SidebarPeminjam />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-4 sm:p-6">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Peminjaman Saya</h1>
          <p class="text-slate-700 dark:text-slate-300">Lihat riwayat dan status peminjaman alat Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
          <div class="app-card app-card--cyan p-6">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Ringkasan Peminjaman</h2>
            <div class="space-y-4 text-slate-700 dark:text-slate-300">
              <div class="rounded-3xl bg-slate-200 dark:bg-slate-800 p-4">
                <p class="text-sm">Peminjaman Aktif</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">0</p>
              </div>
              <div class="rounded-3xl bg-slate-200 dark:bg-slate-800 p-4">
                <p class="text-sm">Total Denda</p>
                <p class="mt-2 text-3xl font-semibold text-cyan-600 dark:text-cyan-300">Rp 0</p>
              </div>
            </div>
          </div>
          <div class="app-card app-card--cyan p-6">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Status Terbaru</h2>
            <div class="rounded-3xl bg-slate-200 dark:bg-slate-800 p-4 text-slate-700 dark:text-slate-300">
              Belum ada data peminjaman.
            </div>
          </div>
        </div>

        <section>
          <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Riwayat Peminjaman</h2>
            <p class="text-slate-600 dark:text-slate-400">Daftar peminjaman alat yang pernah Anda lakukan.</p>
          </div>
          <div class="app-card app-card--cyan overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full min-w-[720px]">
                <thead class="bg-slate-100 dark:bg-slate-800">
                  <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.16em] text-slate-700 dark:text-slate-300">Alat</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.16em] text-slate-700 dark:text-slate-300">Tanggal Pinjam</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.16em] text-slate-700 dark:text-slate-300">Tanggal Kembali</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.16em] text-slate-700 dark:text-slate-300">Status</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.16em] text-slate-700 dark:text-slate-300">Total</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-slate-700 dark:text-slate-200">
                  <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-600 dark:text-slate-400">
                      Belum ada data peminjaman.
                    </td>
                  </tr>
                </tbody>
              </table>
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
  name: 'PeminjamanSaya',
  components: {
    SidebarPeminjam,
    Navbar,
  },
};
</script>

<style scoped>
/* Custom styles if needed */
</style>
