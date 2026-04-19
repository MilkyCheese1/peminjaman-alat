<template>
  <div class="min-h-screen bg-slate-950 flex">
    <!-- Sidebar -->
    <SidebarOwner />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <!-- Navbar -->
      <Navbar />

      <!-- Dashboard Content -->
      <main class="flex-1 p-6">
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-white">Dashboard Owner</h1>
          <p class="text-slate-300">Selamat datang di dashboard owner</p>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="bg-slate-900/90 p-6 rounded-3xl shadow-2xl shadow-slate-950/40 border-l-4 border-cyan-500">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-slate-400">Total Peminjaman</p>
                <p class="text-2xl font-semibold text-white">1,234</p>
              </div>
            </div>
          </div>
          <div class="bg-slate-900/90 p-6 rounded-3xl shadow-2xl shadow-slate-950/40 border-l-4 border-cyan-500">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-slate-400">Alat Tersedia</p>
                <p class="text-2xl font-semibold text-white">567</p>
              </div>
            </div>
          </div>
          <div class="bg-slate-900/90 p-6 rounded-3xl shadow-2xl shadow-slate-950/40 border-l-4 border-cyan-500">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-slate-400">Pendapatan Bulan Ini</p>
                <p class="text-2xl font-semibold text-white">Rp 2,500,000</p>
              </div>
            </div>
          </div>
          <div class="bg-slate-900/90 p-6 rounded-3xl shadow-2xl shadow-slate-950/40 border-l-4 border-cyan-500">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-slate-400">Total Pengguna</p>
                <p class="text-2xl font-semibold text-white">890</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Daftar Alat -->
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-white mb-4">Daftar Alat</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-slate-900/90 border border-white/10 rounded-3xl shadow-2xl shadow-slate-950/40 overflow-hidden">
              <div class="h-48 bg-slate-800 flex items-center justify-center">
                <span class="text-slate-400">Gambar Alat</span>
              </div>
              <div class="p-4">
                <h3 class="font-semibold text-white">Bor Listrik</h3>
                <p class="text-slate-300 text-sm mb-2">Alat untuk membuat lubang</p>
                <div class="flex justify-between items-center">
                  <span class="text-emerald-300 font-semibold">Tersedia</span>
                  <span class="text-slate-400">Rp 50,000/hari</span>
                </div>
              </div>
            </div>
            <div class="bg-slate-900/90 border border-white/10 rounded-3xl shadow-2xl shadow-slate-950/40 overflow-hidden">
              <div class="h-48 bg-slate-800 flex items-center justify-center">
                <span class="text-slate-400">Gambar Alat</span>
              </div>
              <div class="p-4">
                <h3 class="font-semibold text-white">Gergaji Mesin</h3>
                <p class="text-slate-300 text-sm mb-2">Alat untuk memotong kayu</p>
                <div class="flex justify-between items-center">
                  <span class="text-cyan-300 font-semibold">Dipinjam</span>
                  <span class="text-slate-400">Rp 75,000/hari</span>
                </div>
              </div>
            </div>
            <div class="bg-slate-900/90 border border-white/10 rounded-3xl shadow-2xl shadow-slate-950/40 overflow-hidden">
              <div class="h-48 bg-slate-800 flex items-center justify-center">
                <span class="text-slate-400">Gambar Alat</span>
              </div>
              <div class="p-4">
                <h3 class="font-semibold text-white">Cat Semprot</h3>
                <p class="text-slate-300 text-sm mb-2">Alat untuk mengecat</p>
                <div class="flex justify-between items-center">
                  <span class="text-emerald-300 font-semibold">Tersedia</span>
                  <span class="text-slate-400">Rp 40,000/hari</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Laporan Pendapatan -->
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-white mb-4">Laporan Pendapatan</h2>
          <div class="bg-slate-900/90 rounded-3xl shadow-2xl shadow-slate-950/40 p-6 border border-white/10">
            <div class="h-64 bg-slate-800 rounded flex items-center justify-center">
              <span class="text-slate-400">Chart Pendapatan - Placeholder</span>
            </div>
          </div>
        </div>

        <!-- Log Aktivitas -->
        <div>
          <h2 class="text-2xl font-bold text-white mb-4">Log Aktivitas</h2>
          <div class="bg-slate-900/90 rounded-3xl shadow-2xl shadow-slate-950/40 overflow-hidden border border-white/10">
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-slate-800">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Aktivitas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Pengguna</th>
                  </tr>
                </thead>
                <tbody class="bg-slate-950 divide-y divide-slate-800">
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">2026-04-18 10:30</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">Peminjaman alat bor listrik</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">Ahmad</td>
                  </tr>
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">2026-04-18 09:15</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">Pengembalian gergaji mesin</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">Budi</td>
                  </tr>
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">2026-04-18 08:45</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">Registrasi pengguna baru</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">Citra</td>
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