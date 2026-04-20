<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <SidebarStaff />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-6">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Statistik Staff</h1>
          <p class="text-slate-700 dark:text-slate-300">Ringkasan proses peminjaman dan pengembalian</p>
        </div>

        <!-- Key Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="bg-blue-100 dark:bg-blue-900/40 border border-blue-300 dark:border-blue-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-600 dark:text-slate-400 text-sm">Pending Request</p>
                <p class="text-3xl font-bold text-cyan-600 dark:text-cyan-400 mt-2">0</p>
              </div>
              <svg class="w-12 h-12 text-cyan-500/30 dark:text-cyan-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
          </div>

          <div class="bg-green-100 dark:bg-green-900/40 border border-green-300 dark:border-green-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-600 dark:text-slate-400 text-sm">Disetujui Hari Ini</p>
                <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">0</p>
              </div>
              <svg class="w-12 h-12 text-green-500/30 dark:text-green-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
            </div>
          </div>

          <div class="bg-yellow-100 dark:bg-yellow-900/40 border border-yellow-300 dark:border-yellow-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-600 dark:text-slate-400 text-sm">Return Pending</p>
                <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-2">0</p>
              </div>
              <svg class="w-12 h-12 text-purple-500/30 dark:text-purple-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </div>
          </div>

          <div class="bg-red-100 dark:bg-red-900/40 border border-red-300 dark:border-red-700/50 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-slate-400 text-sm">Ditolak</p>
                <p class="text-3xl font-bold text-red-400 mt-2">0</p>
              </div>
              <svg class="w-12 h-12 text-red-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </div>
          </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
          <!-- Request Status -->
          <div class="bg-slate-900/90 border border-white/10 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <h2 class="text-xl font-bold text-white mb-6">Status Request</h2>
            <div class="space-y-4">
              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-slate-300">Pending</span>
                  <span class="text-cyan-400 font-semibold">0</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-2">
                  <div class="bg-cyan-500 h-2 rounded-full" style="width: 0%"></div>
                </div>
              </div>
              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-slate-300">Disetujui</span>
                  <span class="text-green-400 font-semibold">0</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-2">
                  <div class="bg-green-500 h-2 rounded-full" style="width: 0%"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Return Status -->
          <div class="bg-slate-900/90 border border-white/10 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <h2 class="text-xl font-bold text-white mb-6">Status Pengembalian</h2>
            <div class="space-y-4">
              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-slate-300">Pending</span>
                  <span class="text-cyan-400 font-semibold">0</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-2">
                  <div class="bg-cyan-500 h-2 rounded-full" style="width: 0%"></div>
                </div>
              </div>
              <div>
                <div class="flex justify-between mb-2">
                  <span class="text-slate-300">Terverifikasi</span>
                  <span class="text-green-400 font-semibold">0</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-2">
                  <div class="bg-green-500 h-2 rounded-full" style="width: 0%"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-slate-900/90 border border-white/10 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
          <h2 class="text-xl font-bold text-white mb-6">Permintaan Pending</h2>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="text-left text-slate-400 text-sm border-b border-slate-800">
                  <th class="pb-3 font-semibold">Peminjam</th>
                  <th class="pb-3 font-semibold">Alat</th>
                  <th class="pb-3 font-semibold">Tanggal Request</th>
                  <th class="pb-3 font-semibold">Durasi</th>
                  <th class="pb-3 font-semibold">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="5" class="py-10 text-center text-sm text-slate-400">
                    Belum ada data permintaan.
                  </td>
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
import SidebarStaff from '../components/layout/SidebarStaff.vue'
import Navbar from '../components/layout/Navbar.vue'

export default {
  name: 'StatistikStaff',
  components: {
    SidebarStaff,
    Navbar
  }
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
