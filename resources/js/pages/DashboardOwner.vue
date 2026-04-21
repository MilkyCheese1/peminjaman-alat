<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col lg:flex-row">
    <!-- Sidebar -->
    <SidebarOwner />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <!-- Navbar -->
      <Navbar />

      <!-- Dashboard Content -->
      <main class="flex-1 p-4 sm:p-6">
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Dashboard Owner</h1>
          <p class="text-slate-700 dark:text-slate-300">Selamat datang di dashboard owner</p>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
          <div class="app-card app-card--cyan p-6 border-l-4 border-cyan-500">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Peminjaman</p>
                <p class="text-2xl font-semibold text-slate-900 dark:text-white">0</p>
              </div>
            </div>
          </div>
          <div class="app-card app-card--cyan p-6 border-l-4 border-cyan-500">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Alat Tersedia</p>
                <p class="text-2xl font-semibold text-slate-900 dark:text-white">0</p>
              </div>
            </div>
          </div>
          <div class="app-card app-card--cyan p-6 border-l-4 border-cyan-500">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Pendapatan Bulan Ini</p>
                <p class="text-2xl font-semibold text-slate-900 dark:text-white">Rp 0</p>
              </div>
            </div>
          </div>
          <div class="app-card app-card--cyan p-6 border-l-4 border-cyan-500">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Pengguna</p>
                <p class="text-2xl font-semibold text-slate-900 dark:text-white">0</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Daftar Alat -->
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Daftar Alat</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="app-card app-card--cyan p-6 text-slate-700 dark:text-slate-300">
              Belum ada data alat untuk ditampilkan.
            </div>
          </div>
        </div>

        <!-- Laporan Pendapatan -->
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Laporan Pendapatan</h2>
          <div class="app-card app-card--cyan p-6">
            <div class="h-64 bg-slate-100 dark:bg-slate-800 rounded flex items-center justify-center">
              <span class="text-slate-500 dark:text-slate-400">Chart Pendapatan - Placeholder</span>
            </div>
          </div>
        </div>

        <!-- Log Aktivitas -->
        <div>
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Log Aktivitas</h2>
          <div class="app-card app-card--cyan overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-slate-100 dark:bg-slate-800">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 dark:text-slate-400 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 dark:text-slate-400 uppercase tracking-wider">Aktivitas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 dark:text-slate-400 uppercase tracking-wider">Pengguna</th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-950 divide-y divide-slate-200 dark:divide-slate-800">
                  <tr>
                    <td colspan="3" class="px-6 py-10 text-center text-sm text-slate-600 dark:text-slate-400">
                      Belum ada aktivitas.
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
import SidebarOwner from '../components/layout/SidebarOwner.vue'
import Navbar from '../components/layout/Navbar.vue'

export default {
  name: 'DashboardOwner',
  components: {
    SidebarOwner,
    Navbar
  }
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
