<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <SidebarPeminjam />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-6">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Statistik Peminjaman Saya</h1>
          <p class="text-slate-700 dark:text-slate-300">Ringkasan aktivitas peminjaman Anda</p>
        </div>

        <!-- Key Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="bg-blue-100 dark:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-600 dark:text-slate-400 text-sm">Peminjaman Aktif</p>
                <p class="text-3xl font-bold text-cyan-600 dark:text-cyan-400 mt-2">3</p>
              </div>
              <svg class="w-12 h-12 text-cyan-500/30 dark:text-cyan-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </div>
          </div>

          <div class="bg-green-100 dark:bg-green-900/40 border border-green-300 dark:border-green-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-600 dark:text-slate-400 text-sm">Selesai</p>
                <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">12</p>
              </div>
              <svg class="w-12 h-12 text-green-500/30 dark:text-green-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
          </div>

          <div class="bg-red-100 dark:bg-red-900/40 border border-red-300 dark:border-red-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-600 dark:text-slate-400 text-sm">Total Denda</p>
                <p class="text-3xl font-bold text-orange-600 dark:text-orange-400 mt-2">Rp 150K</p>
              </div>
              <svg class="w-12 h-12 text-orange-500/30 dark:text-orange-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
          </div>

          <div class="bg-yellow-100 dark:bg-yellow-900/40 border border-yellow-300 dark:border-yellow-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-600 dark:text-slate-400 text-sm">Keterlambatan</p>
                <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">1</p>
              </div>
              <svg class="w-12 h-12 text-red-500/30 dark:text-red-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
          </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <!-- Activity Chart -->
          <div class="bg-slate-900/90 border border-white/10 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <h2 class="text-xl font-bold text-white mb-6">Aktivitas Terbaru</h2>
            <div class="space-y-4">
              <div class="flex items-center justify-between p-3 bg-slate-800/50 rounded-xl">
                <div class="flex items-center gap-3">
                  <div class="w-2 h-2 bg-cyan-400 rounded-full"></div>
                  <span class="text-slate-300">Peminjaman Alat Bor</span>
                </div>
                <span class="text-xs text-slate-400">2 hari lalu</span>
              </div>
              <div class="flex items-center justify-between p-3 bg-slate-800/50 rounded-xl">
                <div class="flex items-center gap-3">
                  <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                  <span class="text-slate-300">Pengembalian Alat Gergaji</span>
                </div>
                <span class="text-xs text-slate-400">5 hari lalu</span>
              </div>
              <div class="flex items-center justify-between p-3 bg-slate-800/50 rounded-xl">
                <div class="flex items-center gap-3">
                  <div class="w-2 h-2 bg-orange-400 rounded-full"></div>
                  <span class="text-slate-300">Denda Keterlambatan</span>
                </div>
                <span class="text-xs text-slate-400">1 minggu lalu</span>
              </div>
            </div>
          </div>

          <!-- Status Distribution -->
          <div class="bg-slate-900/90 border border-white/10 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <h2 class="text-xl font-bold text-white mb-6">Distribusi Status</h2>
            <div class="space-y-4">
              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-slate-300">Aktif</span>
                  <span class="text-cyan-400 font-semibold">3</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-2">
                  <div class="bg-cyan-500 h-2 rounded-full" style="width: 20%"></div>
                </div>
              </div>
              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-slate-300">Selesai</span>
                  <span class="text-green-400 font-semibold">12</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-2">
                  <div class="bg-green-500 h-2 rounded-full" style="width: 80%"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Borrowing History -->
        <div class="bg-slate-900/90 border border-white/10 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
          <h2 class="text-xl font-bold text-white mb-6">Riwayat Peminjaman</h2>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="text-left text-slate-400 text-sm border-b border-slate-800">
                  <th class="pb-3 font-semibold">Alat</th>
                  <th class="pb-3 font-semibold">Tanggal Pinjam</th>
                  <th class="pb-3 font-semibold">Tanggal Kembali</th>
                  <th class="pb-3 font-semibold">Status</th>
                  <th class="pb-3 font-semibold">Denda</th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-b border-slate-800/50 hover:bg-slate-800/20 transition">
                  <td class="py-3 text-white">Alat Bor</td>
                  <td class="py-3 text-slate-300">17 Apr 2026</td>
                  <td class="py-3 text-slate-300">20 Apr 2026</td>
                  <td class="py-3"><span class="px-3 py-1 bg-cyan-500/20 text-cyan-300 rounded-full text-sm">Aktif</span></td>
                  <td class="py-3 text-slate-300">Rp 0</td>
                </tr>
                <tr class="border-b border-slate-800/50 hover:bg-slate-800/20 transition">
                  <td class="py-3 text-white">Alat Gergaji</td>
                  <td class="py-3 text-slate-300">15 Apr 2026</td>
                  <td class="py-3 text-slate-300">18 Apr 2026</td>
                  <td class="py-3"><span class="px-3 py-1 bg-green-500/20 text-green-300 rounded-full text-sm">Selesai</span></td>
                  <td class="py-3 text-slate-300">Rp 0</td>
                </tr>
                <tr class="border-b border-slate-800/50 hover:bg-slate-800/20 transition">
                  <td class="py-3 text-white">Obeng Set</td>
                  <td class="py-3 text-slate-300">10 Apr 2026</td>
                  <td class="py-3 text-slate-300">13 Apr 2026</td>
                  <td class="py-3"><span class="px-3 py-1 bg-red-500/20 text-red-300 rounded-full text-sm">Terlambat</span></td>
                  <td class="py-3 text-red-400">Rp 75K</td>
                </tr>
              </tbody>
            </table>
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
  name: 'StatistikPeminjam',
  components: {
    SidebarPeminjam,
    Navbar
  }
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
