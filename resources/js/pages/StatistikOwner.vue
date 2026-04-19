<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <SidebarOwner />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-6">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Statistik Owner</h1>
          <p class="text-slate-700 dark:text-slate-300">Ringkasan data alat dan peminjaman Anda</p>
        </div>

        <!-- Key Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="bg-blue-100 dark:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-600 dark:text-slate-400 text-sm">Total Alat</p>
                <p class="text-3xl font-bold text-cyan-600 dark:text-cyan-400 mt-2">12</p>
              </div>
              <svg class="w-12 h-12 text-cyan-500/30 dark:text-cyan-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </div>
          </div>

          <div class="bg-green-100 dark:bg-green-900/40 border border-green-300 dark:border-green-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-600 dark:text-slate-400 text-sm">Alat Tersedia</p>
                <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">8</p>
              </div>
              <svg class="w-12 h-12 text-green-500/30 dark:text-green-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
          </div>

          <div class="bg-blue-100 dark:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-600 dark:text-slate-400 text-sm">Total Peminjaman</p>
                <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-2">45</p>
              </div>
              <svg class="w-12 h-12 text-purple-500/30 dark:text-purple-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
              </svg>
            </div>
          </div>

          <div class="bg-red-100 dark:bg-red-900/40 border border-red-300 dark:border-red-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-400 text-sm">Alat Tidak Tersedia</p>
                <p class="text-3xl font-bold text-orange-400 mt-2">4</p>
              </div>
              <svg class="w-12 h-12 text-orange-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
              </svg>
            </div>
          </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <!-- Equipment Status -->
          <div class="bg-slate-900/90 border border-white/10 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <h2 class="text-xl font-bold text-white mb-6">Status Alat</h2>
            <div class="space-y-4">
              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-slate-300">Tersedia</span>
                  <span class="text-green-400 font-semibold">8</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-2">
                  <div class="bg-green-500 h-2 rounded-full" style="width: 67%"></div>
                </div>
              </div>
              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-slate-300">Dipinjam</span>
                  <span class="text-cyan-400 font-semibold">4</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-2">
                  <div class="bg-cyan-500 h-2 rounded-full" style="width: 33%"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Borrowing Status -->
          <div class="bg-slate-900/90 border border-white/10 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <h2 class="text-xl font-bold text-white mb-6">Status Peminjaman</h2>
            <div class="space-y-4">
              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-slate-300">Aktif</span>
                  <span class="text-cyan-400 font-semibold">4</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-2">
                  <div class="bg-cyan-500 h-2 rounded-full" style="width: 9%"></div>
                </div>
              </div>
              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-slate-300">Selesai</span>
                  <span class="text-green-400 font-semibold">40</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-2">
                  <div class="bg-green-500 h-2 rounded-full" style="width: 89%"></div>
                </div>
              </div>
              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-slate-300">Pending</span>
                  <span class="text-orange-400 font-semibold">1</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-2">
                  <div class="bg-orange-500 h-2 rounded-full" style="width: 2%"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Equipment List -->
        <div class="bg-slate-900/90 border border-white/10 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
          <h2 class="text-xl font-bold text-white mb-6">Daftar Alat</h2>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="text-left text-slate-400 text-sm border-b border-slate-800">
                  <th class="pb-3 font-semibold">Nama Alat</th>
                  <th class="pb-3 font-semibold">Kategori</th>
                  <th class="pb-3 font-semibold">Total Peminjaman</th>
                  <th class="pb-3 font-semibold">Status</th>
                  <th class="pb-3 font-semibold">Kondisi</th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-b border-slate-800/50 hover:bg-slate-800/20 transition">
                  <td class="py-3 text-white">Alat Bor</td>
                  <td class="py-3 text-slate-300">Perkakas</td>
                  <td class="py-3 text-slate-300">12</td>
                  <td class="py-3"><span class="px-3 py-1 bg-cyan-500/20 text-cyan-300 rounded-full text-sm">Dipinjam</span></td>
                  <td class="py-3"><span class="px-3 py-1 bg-green-500/20 text-green-300 rounded-full text-sm">Baik</span></td>
                </tr>
                <tr class="border-b border-slate-800/50 hover:bg-slate-800/20 transition">
                  <td class="py-3 text-white">Alat Gergaji</td>
                  <td class="py-3 text-slate-300">Perkakas</td>
                  <td class="py-3 text-slate-300">8</td>
                  <td class="py-3"><span class="px-3 py-1 bg-green-500/20 text-green-300 rounded-full text-sm">Tersedia</span></td>
                  <td class="py-3"><span class="px-3 py-1 bg-green-500/20 text-green-300 rounded-full text-sm">Baik</span></td>
                </tr>
                <tr class="border-b border-slate-800/50 hover:bg-slate-800/20 transition">
                  <td class="py-3 text-white">Obeng Set</td>
                  <td class="py-3 text-slate-300">Perkakas</td>
                  <td class="py-3 text-slate-300">15</td>
                  <td class="py-3"><span class="px-3 py-1 bg-green-500/20 text-green-300 rounded-full text-sm">Tersedia</span></td>
                  <td class="py-3"><span class="px-3 py-1 bg-orange-500/20 text-orange-300 rounded-full text-sm">Kurang</span></td>
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
import SidebarOwner from '../components/layout/SidebarOwner.vue'
import Navbar from '../components/layout/Navbar.vue'

export default {
  name: 'StatistikOwner',
  components: {
    SidebarOwner,
    Navbar
  }
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
