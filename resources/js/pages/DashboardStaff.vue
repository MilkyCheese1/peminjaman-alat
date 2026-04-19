<template>
  <div class="min-h-screen bg-slate-950 flex">
    <!-- Sidebar -->
    <SidebarStaff />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <!-- Navbar -->
      <Navbar />

      <!-- Dashboard Content -->
      <main class="flex-1 p-6">
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-white">Dashboard Staff</h1>
          <p class="text-slate-300">Kelola proses peminjaman dan pengembalian alat</p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-slate-900/90 p-6 rounded-3xl shadow-2xl shadow-slate-950/40 border border-white/10">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-slate-400">Peminjaman Hari Ini</p>
                <p class="text-2xl font-semibold text-white">12</p>
              </div>
            </div>
          </div>
          <div class="bg-slate-900/90 p-6 rounded-3xl shadow-2xl shadow-slate-950/40 border border-white/10">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-slate-400">Pengembalian Hari Ini</p>
                <p class="text-2xl font-semibold text-white">8</p>
              </div>
            </div>
          </div>
          <div class="bg-slate-900/90 p-6 rounded-3xl shadow-2xl shadow-slate-950/40 border border-white/10">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-slate-400">Pending Approval</p>
                <p class="text-2xl font-semibold text-white">5</p>
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
                <div class="flex justify-between items-center mb-2">
                  <span class="text-emerald-300 font-semibold">Tersedia</span>
                  <span class="text-slate-400">Rp 50,000/hari</span>
                </div>
                <button class="w-full bg-cyan-500 text-slate-950 py-2 px-4 rounded hover:bg-cyan-400 transition duration-300">
                  Proses Peminjaman
                </button>
              </div>
            </div>
            <div class="bg-slate-900/90 border border-white/10 rounded-3xl shadow-2xl shadow-slate-950/40 overflow-hidden">
              <div class="h-48 bg-slate-800 flex items-center justify-center">
                <span class="text-slate-400">Gambar Alat</span>
              </div>
              <div class="p-4">
                <h3 class="font-semibold text-white">Gergaji Mesin</h3>
                <p class="text-slate-300 text-sm mb-2">Alat untuk memotong kayu</p>
                <div class="flex justify-between items-center mb-2">
                  <span class="text-cyan-300 font-semibold">Dipinjam</span>
                  <span class="text-slate-400">Rp 75,000/hari</span>
                </div>
                <button class="w-full bg-cyan-500 text-slate-950 py-2 px-4 rounded hover:bg-cyan-400 transition duration-300">
                  Proses Pengembalian
                </button>
              </div>
            </div>
            <div class="bg-slate-900/90 border border-white/10 rounded-3xl shadow-2xl shadow-slate-950/40 overflow-hidden">
              <div class="h-48 bg-slate-800 flex items-center justify-center">
                <span class="text-slate-400">Gambar Alat</span>
              </div>
              <div class="p-4">
                <h3 class="font-semibold text-white">Cat Semprot</h3>
                <p class="text-slate-300 text-sm mb-2">Alat untuk mengecat</p>
                <div class="flex justify-between items-center mb-2">
                  <span class="text-amber-300 font-semibold">Pending</span>
                  <span class="text-slate-400">Rp 40,000/hari</span>
                </div>
                <button class="w-full bg-amber-500 text-slate-950 py-2 px-4 rounded hover:bg-amber-400 transition duration-300">
                  Approve Peminjaman
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Proses Peminjaman -->
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-white mb-4">Proses Peminjaman Pending</h2>
          <div class="bg-slate-900/90 rounded-3xl shadow-2xl shadow-slate-950/40 overflow-hidden border border-white/10">
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-slate-800">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Pengguna</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Aksi</th>
                  </tr>
                </thead>
                <tbody class="bg-slate-950 divide-y divide-slate-800">
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">Ahmad</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">Bor Listrik</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">2026-04-18</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                        Pending
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <button class="text-cyan-300 hover:text-cyan-100 mr-3">Approve</button>
                      <button class="text-rose-300 hover:text-rose-100">Reject</button>
                    </td>
                  </tr>
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">Budi</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">Gergaji Mesin</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">2026-04-19</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                        Pending
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <button class="text-cyan-300 hover:text-cyan-100 mr-3">Approve</button>
                      <button class="text-rose-300 hover:text-rose-100">Reject</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Proses Pengembalian -->
        <div>
          <h2 class="text-2xl font-bold text-white mb-4">Proses Pengembalian Hari Ini</h2>
          <div class="bg-slate-900/90 rounded-3xl shadow-2xl shadow-slate-950/40 overflow-hidden border border-white/10">
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-slate-800">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Pengguna</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Tanggal Kembali</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Aksi</th>
                  </tr>
                </thead>
                <tbody class="bg-slate-950 divide-y divide-slate-800">
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">Citra</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">Cat Semprot</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">2026-04-15</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">2026-04-18</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <button class="text-cyan-300 hover:text-cyan-100">Konfirmasi Pengembalian</button>
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
import SidebarStaff from '../components/layout/SidebarStaff.vue'
import Navbar from '../components/layout/Navbar.vue'

export default {
  name: 'DashboardStaff',
  components: {
    SidebarStaff,
    Navbar
  }
}
</script>

<style scoped>
/* Additional styles if needed */
</style>