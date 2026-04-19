<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <SidebarAdmin />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-6">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Statistik Sistem</h1>
          <p class="text-slate-700 dark:text-slate-300">Ringkasan data sistem peminjaman alat</p>
        </div>

        <!-- Key Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="bg-blue-100 dark:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-600 dark:text-slate-400 text-sm">Total Pengguna</p>
                <p class="text-3xl font-bold text-cyan-600 dark:text-cyan-400 mt-2">24</p>
              </div>
              <svg class="w-12 h-12 text-cyan-500/30 dark:text-cyan-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM15 20H9m6 0h.01M9 20H3v-2a6 6 0 0112 0v2z"></path>
              </svg>
            </div>
          </div>

          <div class="bg-green-100 dark:bg-green-900/40 border border-green-300 dark:border-green-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-600 dark:text-slate-400 text-sm">Total Alat</p>
                <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">48</p>
              </div>
              <svg class="w-12 h-12 text-green-500/30 dark:text-green-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </div>
          </div>

          <div class="bg-blue-100 dark:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-600 dark:text-slate-400 text-sm">Total Peminjaman</p>
                <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-2">156</p>
              </div>
              <svg class="w-12 h-12 text-purple-500/30 dark:text-purple-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
              </svg>
            </div>
          </div>

          <div class="bg-red-100 dark:bg-red-900/40 border border-red-300 dark:border-red-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-400 text-sm">Alat Tidak Tersedia</p>
                <p class="text-3xl font-bold text-orange-400 mt-2">12</p>
              </div>
              <svg class="w-12 h-12 text-orange-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
              </svg>
            </div>
          </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <!-- User Distribution -->
          <div class="bg-slate-900/90 border border-white/10 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <h2 class="text-xl font-bold text-white mb-6">Distribusi Pengguna</h2>
            <div class="space-y-4">
              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-slate-300">Peminjam</span>
                  <span class="text-cyan-400 font-semibold">16</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-2">
                  <div class="bg-cyan-500 h-2 rounded-full" style="width: 67%"></div>
                </div>
              </div>
              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-slate-300">Staff</span>
                  <span class="text-green-400 font-semibold">5</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-2">
                  <div class="bg-green-500 h-2 rounded-full" style="width: 21%"></div>
                </div>
              </div>
              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-slate-300">Owner</span>
                  <span class="text-purple-400 font-semibold">2</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-2">
                  <div class="bg-purple-500 h-2 rounded-full" style="width: 8%"></div>
                </div>
              </div>
              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-slate-300">Admin</span>
                  <span class="text-orange-400 font-semibold">1</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-2">
                  <div class="bg-orange-500 h-2 rounded-full" style="width: 4%"></div>
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
                  <span class="text-cyan-400 font-semibold">28</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-2">
                  <div class="bg-cyan-500 h-2 rounded-full" style="width: 18%"></div>
                </div>
              </div>
              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-slate-300">Selesai</span>
                  <span class="text-green-400 font-semibold">120</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-2">
                  <div class="bg-green-500 h-2 rounded-full" style="width: 77%"></div>
                </div>
              </div>
              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-slate-300">Terlambat</span>
                  <span class="text-orange-400 font-semibold">8</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-2">
                  <div class="bg-orange-500 h-2 rounded-full" style="width: 5%"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-slate-900/90 border border-white/10 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
          <h2 class="text-xl font-bold text-white mb-6">Aktivitas Terbaru</h2>
          <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-slate-800/50 rounded-xl hover:bg-slate-800 transition">
              <div class="flex items-center gap-3">
                <div class="w-3 h-3 bg-cyan-400 rounded-full"></div>
                <span class="text-slate-300">Peminjaman baru dari Budi Santoso</span>
              </div>
              <span class="text-xs text-slate-400">1 jam lalu</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-slate-800/50 rounded-xl hover:bg-slate-800 transition">
              <div class="flex items-center gap-3">
                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                <span class="text-slate-300">Pengembalian alat dari Rina Wijaya</span>
              </div>
              <span class="text-xs text-slate-400">3 jam lalu</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-slate-800/50 rounded-xl hover:bg-slate-800 transition">
              <div class="flex items-center gap-3">
                <div class="w-3 h-3 bg-orange-400 rounded-full"></div>
                <span class="text-slate-300">Alat rusak dilaporkan oleh Ahmad</span>
              </div>
              <span class="text-xs text-slate-400">5 jam lalu</span>
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
  name: 'StatistikAdmin',
  components: {
    SidebarAdmin,
    Navbar
  }
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
