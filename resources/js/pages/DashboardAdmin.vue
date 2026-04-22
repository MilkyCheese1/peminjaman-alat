<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <!-- Sidebar -->
    <SidebarAdmin />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <!-- Navbar -->
      <Navbar />

      <!-- Dashboard Content -->
      <main class="flex-1 p-6">
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Dashboard Admin</h1>
          <p class="text-slate-700 dark:text-slate-300">Kelola data sistem peminjaman alat</p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <router-link to="/management-alat" class="app-card app-card--cyan p-4 hover:bg-cyan-100 dark:hover:bg-cyan-500/15 transition duration-300 block">
            <svg class="w-8 h-8 mb-2 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Management Alat
          </router-link>
          <router-link to="/management-kategori" class="app-card app-card--cyan p-4 hover:bg-cyan-100 dark:hover:bg-cyan-500/15 transition duration-300 block">
            <svg class="w-8 h-8 mb-2 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            Management Kategori
          </router-link>
          <router-link to="/management-user" class="app-card app-card--cyan p-4 hover:bg-cyan-100 dark:hover:bg-cyan-500/15 transition duration-300 block">
            <svg class="w-8 h-8 mb-2 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Management User
          </router-link>
          <router-link to="/laporan-admin" class="app-card app-card--cyan p-4 hover:bg-cyan-100 dark:hover:bg-cyan-500/15 transition duration-300 block">
            <svg class="w-8 h-8 mb-2 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Laporan
          </router-link>
        </div>

        <!-- Management Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Alat Management -->
          <div class="app-card app-card--cyan overflow-hidden">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800">
              <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Manajemen Alat</h2>
            </div>
            <div class="p-6">
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800">
                      <th class="text-left py-2 px-4 font-semibold text-slate-700 dark:text-slate-300">Nama Alat</th>
                      <th class="text-left py-2 px-4 font-semibold text-slate-700 dark:text-slate-300">Kategori</th>
                      <th class="text-left py-2 px-4 font-semibold text-slate-700 dark:text-slate-300">Status</th>
                      <th class="text-left py-2 px-4 font-semibold text-slate-700 dark:text-slate-300">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="4" class="py-10 px-4 text-center text-sm text-slate-600 dark:text-slate-400">
                        Belum ada data alat.
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- User Management -->
          <div class="app-card app-card--cyan overflow-hidden">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800">
              <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Manajemen Pengguna</h2>
            </div>
            <div class="p-6">
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800">
                      <th class="text-left py-2 px-4 font-semibold text-slate-700 dark:text-slate-300">Nama</th>
                      <th class="text-left py-2 px-4 font-semibold text-slate-700 dark:text-slate-300">Role</th>
                      <th class="text-left py-2 px-4 font-semibold text-slate-700 dark:text-slate-300">Status</th>
                      <th class="text-left py-2 px-4 font-semibold text-slate-700 dark:text-slate-300">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="4" class="py-10 px-4 text-center text-sm text-slate-600 dark:text-slate-400">
                        Belum ada data pengguna.
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Reports Section -->
        <div class="mt-8">
          <div class="mb-4 flex items-center justify-between gap-3">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Laporan Sistem</h2>
            <router-link
              to="/laporan-admin"
              class="inline-flex items-center justify-center rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800"
            >
              Buka Laporan
            </router-link>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="app-card app-card--cyan p-6">
              <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Peminjaman Bulanan</h3>
              <div class="h-48 bg-slate-100 dark:bg-slate-800 rounded flex items-center justify-center">
                <span class="text-slate-500 dark:text-slate-400">Chart Peminjaman - Placeholder</span>
              </div>
            </div>
            <div class="app-card app-card--cyan p-6">
              <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Pendapatan Bulanan</h3>
              <div class="h-48 bg-slate-100 dark:bg-slate-800 rounded flex items-center justify-center">
                <span class="text-slate-500 dark:text-slate-400">Chart Pendapatan - Placeholder</span>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import SidebarAdmin from '../components/layout/SidebarAdmin.vue'
import Navbar from '../components/layout/Navbar.vue'

export default {
  name: 'DashboardAdmin',
  components: {
    SidebarAdmin,
    Navbar
  }
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
