<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <!-- Sidebar -->
    <SidebarPeminjam />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <!-- Navbar -->
      <Navbar />

      <!-- Dashboard Content -->
      <main class="flex-1 p-6">
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Dashboard Peminjam</h1>
          <p class="text-slate-700 dark:text-slate-300">Jelajahi dan kelola peminjaman alat Anda</p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
          <router-link to="/alat" class="app-card app-card--cyan p-6 transition hover:bg-cyan-100 dark:hover:bg-cyan-500/15 block">
            <h2 class="text-xl font-bold mb-2">Jelajahi Alat</h2>
            <p class="mb-4 text-slate-700 dark:text-slate-300">Temukan alat yang Anda butuhkan</p>
            <span class="inline-flex items-center rounded-full bg-cyan-500 px-4 py-2 text-sm font-semibold text-white dark:text-slate-950">Buka Daftar Alat</span>
          </router-link>
          <router-link to="/peminjaman-saya" class="app-card app-card--cyan p-6 transition hover:bg-cyan-100 dark:hover:bg-cyan-500/15 block">
            <h2 class="text-xl font-bold mb-2">Peminjaman Saya</h2>
            <p class="mb-4 text-slate-700 dark:text-slate-300">Lihat status peminjaman Anda</p>
            <span class="inline-flex items-center rounded-full bg-cyan-500 px-4 py-2 text-sm font-semibold text-white dark:text-slate-950">Lihat Riwayat</span>
          </router-link>
        </div>

        <!-- Jelajahi Alat -->
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Jelajahi Alat Tersedia</h2>
          <div class="app-card app-card--cyan p-6 text-slate-700 dark:text-slate-300">
            Belum ada data alat untuk ditampilkan.
          </div>
        </div>

        <!-- Peminjaman Saya -->
        <div>
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Peminjaman Saya</h2>
          <div class="app-card app-card--cyan overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-slate-100 dark:bg-slate-800">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 dark:text-slate-400 uppercase tracking-wider">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 dark:text-slate-400 uppercase tracking-wider">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 dark:text-slate-400 uppercase tracking-wider">Tanggal Kembali</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 dark:text-slate-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 dark:text-slate-400 uppercase tracking-wider">Total Denda</th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-950 divide-y divide-slate-200 dark:divide-slate-800">
                  <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-600 dark:text-slate-400">
                      Belum ada data peminjaman.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import SidebarPeminjam from '../components/layout/SidebarPeminjam.vue'
import Navbar from '../components/layout/Navbar.vue'

export default {
  name: 'DashboardPeminjam',
  components: {
    SidebarPeminjam,
    Navbar
  }
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
