<template>
  <div class="min-h-screen bg-slate-950 flex">
    <!-- Sidebar -->
    <SidebarAdmin />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <!-- Navbar -->
      <Navbar />

      <!-- Dashboard Content -->
      <main class="flex-1 p-6">
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-white">Dashboard Admin</h1>
          <p class="text-slate-300">Kelola data sistem peminjaman alat</p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <button class="bg-slate-900/90 text-white p-4 rounded-3xl shadow-2xl shadow-slate-950/40 border border-white/10 hover:bg-slate-800 transition duration-300">
            <svg class="w-8 h-8 mb-2 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Alat
          </button>
          <button class="bg-slate-900/90 text-white p-4 rounded-3xl shadow-2xl shadow-slate-950/40 border border-white/10 hover:bg-slate-800 transition duration-300">
            <svg class="w-8 h-8 mb-2 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            Kelola Kategori
          </button>
          <button class="bg-slate-900/90 text-white p-4 rounded-3xl shadow-2xl shadow-slate-950/40 border border-white/10 hover:bg-slate-800 transition duration-300">
            <svg class="w-8 h-8 mb-2 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Kelola Pengguna
          </button>
          <button class="bg-slate-900/90 text-white p-4 rounded-3xl shadow-2xl shadow-slate-950/40 border border-white/10 hover:bg-slate-800 transition duration-300">
            <svg class="w-8 h-8 mb-2 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Laporan
          </button>
        </div>

        <!-- Management Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Alat Management -->
          <div class="bg-slate-900/90 rounded-3xl shadow-2xl shadow-slate-950/40 overflow-hidden border border-white/10">
            <div class="p-6 border-b border-slate-800">
              <h2 class="text-xl font-semibold text-white">Manajemen Alat</h2>
            </div>
            <div class="p-6">
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead>
                    <tr class="border-b border-slate-800">
                      <th class="text-left py-2 px-4 font-semibold text-slate-300">Nama Alat</th>
                      <th class="text-left py-2 px-4 font-semibold text-slate-300">Kategori</th>
                      <th class="text-left py-2 px-4 font-semibold text-slate-300">Status</th>
                      <th class="text-left py-2 px-4 font-semibold text-slate-300">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="border-b border-slate-800">
                      <td class="py-2 px-4 text-slate-200">Bor Listrik</td>
                      <td class="py-2 px-4 text-slate-200">Elektronik</td>
                      <td class="py-2 px-4"><span class="bg-emerald-100 text-emerald-800 px-2 py-1 rounded-full text-xs">Tersedia</span></td>
                      <td class="py-2 px-4">
                        <button class="text-cyan-300 hover:text-cyan-100 mr-2">Edit</button>
                        <button class="text-rose-300 hover:text-rose-100">Hapus</button>
                      </td>
                    </tr>
                    <tr class="border-b border-slate-800">
                      <td class="py-2 px-4 text-slate-200">Gergaji Mesin</td>
                      <td class="py-2 px-4 text-slate-200">Mesin</td>
                      <td class="py-2 px-4"><span class="bg-cyan-100 text-cyan-800 px-2 py-1 rounded-full text-xs">Dipinjam</span></td>
                      <td class="py-2 px-4">
                        <button class="text-cyan-300 hover:text-cyan-100 mr-2">Edit</button>
                        <button class="text-rose-300 hover:text-rose-100">Hapus</button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- User Management -->
          <div class="bg-slate-900/90 rounded-3xl shadow-2xl shadow-slate-950/40 overflow-hidden border border-white/10">
            <div class="p-6 border-b border-slate-800">
              <h2 class="text-xl font-semibold text-white">Manajemen Pengguna</h2>
            </div>
            <div class="p-6">
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead>
                    <tr class="border-b border-slate-800">
                      <th class="text-left py-2 px-4 font-semibold text-slate-300">Nama</th>
                      <th class="text-left py-2 px-4 font-semibold text-slate-300">Role</th>
                      <th class="text-left py-2 px-4 font-semibold text-slate-300">Status</th>
                      <th class="text-left py-2 px-4 font-semibold text-slate-300">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="border-b border-slate-800">
                      <td class="py-2 px-4 text-slate-200">Ahmad Staff</td>
                      <td class="py-2 px-4 text-slate-200">Staff</td>
                      <td class="py-2 px-4"><span class="bg-emerald-100 text-emerald-800 px-2 py-1 rounded-full text-xs">Aktif</span></td>
                      <td class="py-2 px-4">
                        <button class="text-cyan-300 hover:text-cyan-100 mr-2">Edit</button>
                        <button class="text-rose-300 hover:text-rose-100">Hapus</button>
                      </td>
                    </tr>
                    <tr class="border-b border-slate-800">
                      <td class="py-2 px-4 text-slate-200">Budi Owner</td>
                      <td class="py-2 px-4 text-slate-200">Owner</td>
                      <td class="py-2 px-4"><span class="bg-emerald-100 text-emerald-800 px-2 py-1 rounded-full text-xs">Aktif</span></td>
                      <td class="py-2 px-4">
                        <button class="text-cyan-300 hover:text-cyan-100 mr-2">Edit</button>
                        <button class="text-rose-300 hover:text-rose-100">Hapus</button>
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
          <h2 class="text-2xl font-bold text-white mb-4">Laporan Sistem</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-slate-900/90 rounded-3xl shadow-2xl shadow-slate-950/40 p-6 border border-white/10">
              <h3 class="text-lg font-semibold text-white mb-4">Peminjaman Bulanan</h3>
              <div class="h-48 bg-slate-800 rounded flex items-center justify-center">
                <span class="text-slate-400">Chart Peminjaman - Placeholder</span>
              </div>
            </div>
            <div class="bg-slate-900/90 rounded-3xl shadow-2xl shadow-slate-950/40 p-6 border border-white/10">
              <h3 class="text-lg font-semibold text-white mb-4">Pendapatan Bulanan</h3>
              <div class="h-48 bg-slate-800 rounded flex items-center justify-center">
                <span class="text-slate-400">Chart Pendapatan - Placeholder</span>
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