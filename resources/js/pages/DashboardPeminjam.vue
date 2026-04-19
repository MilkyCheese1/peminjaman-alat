<template>
  <div class="min-h-screen bg-slate-950 flex">
    <!-- Sidebar -->
    <SidebarPeminjam />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <!-- Navbar -->
      <Navbar />

      <!-- Dashboard Content -->
      <main class="flex-1 p-6">
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-white">Dashboard Peminjam</h1>
          <p class="text-slate-300">Jelajahi dan kelola peminjaman alat Anda</p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
          <router-link to="/alat" class="block bg-slate-900 border border-white/10 text-white p-6 rounded-3xl shadow-2xl shadow-slate-950/40 transition hover:bg-slate-800">
            <h2 class="text-xl font-bold mb-2">Jelajahi Alat</h2>
            <p class="mb-4 text-slate-300">Temukan alat yang Anda butuhkan</p>
            <span class="inline-flex items-center rounded-full bg-cyan-500 px-4 py-2 text-sm font-semibold text-slate-950">Buka Daftar Alat</span>
          </router-link>
          <router-link to="/peminjaman-saya" class="block bg-slate-900 border border-white/10 text-white p-6 rounded-3xl shadow-2xl shadow-slate-950/40 transition hover:bg-slate-800">
            <h2 class="text-xl font-bold mb-2">Peminjaman Saya</h2>
            <p class="mb-4 text-slate-300">Lihat status peminjaman Anda</p>
            <span class="inline-flex items-center rounded-full bg-cyan-500 px-4 py-2 text-sm font-semibold text-slate-950">Lihat Riwayat</span>
          </router-link>
        </div>

        <!-- Jelajahi Alat -->
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-white mb-4">Jelajahi Alat Tersedia</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-slate-900 border border-white/10 rounded-3xl shadow-2xl shadow-slate-950/40 overflow-hidden hover:shadow-slate-900 transition duration-300">
              <div class="h-48 bg-slate-800 flex items-center justify-center">
                <span class="text-slate-400">Gambar Alat</span>
              </div>
              <div class="p-4">
                <h3 class="font-semibold text-white">Bor Listrik</h3>
                <p class="text-slate-300 text-sm mb-2">Alat untuk membuat lubang</p>
                <div class="flex justify-between items-center mb-3">
                  <span class="text-emerald-300 font-semibold">Tersedia</span>
                  <span class="text-slate-400 font-semibold">Rp 50,000/hari</span>
                </div>
                <button class="w-full bg-cyan-500 text-slate-950 py-2 px-4 rounded hover:bg-cyan-400 transition duration-300">
                  Pinjam Sekarang
                </button>
              </div>
            </div>
            <div class="bg-slate-900 border border-white/10 rounded-3xl shadow-2xl shadow-slate-950/40 overflow-hidden hover:shadow-slate-900 transition duration-300">
              <div class="h-48 bg-slate-800 flex items-center justify-center">
                <span class="text-slate-400">Gambar Alat</span>
              </div>
              <div class="p-4">
                <h3 class="font-semibold text-white">Gergaji Mesin</h3>
                <p class="text-slate-300 text-sm mb-2">Alat untuk memotong kayu</p>
                <div class="flex justify-between items-center mb-3">
                  <span class="text-emerald-300 font-semibold">Tersedia</span>
                  <span class="text-slate-400 font-semibold">Rp 75,000/hari</span>
                </div>
                <button class="w-full bg-cyan-500 text-slate-950 py-2 px-4 rounded hover:bg-cyan-400 transition duration-300">
                  Pinjam Sekarang
                </button>
              </div>
            </div>
            <div class="bg-slate-900 border border-white/10 rounded-3xl shadow-2xl shadow-slate-950/40 overflow-hidden hover:shadow-slate-900 transition duration-300">
              <div class="h-48 bg-slate-800 flex items-center justify-center">
                <span class="text-slate-400">Gambar Alat</span>
              </div>
              <div class="p-4">
                <h3 class="font-semibold text-white">Cat Semprot</h3>
                <p class="text-slate-300 text-sm mb-2">Alat untuk mengecat</p>
                <div class="flex justify-between items-center mb-3">
                  <span class="text-cyan-300 font-semibold">Dipinjam</span>
                  <span class="text-slate-400 font-semibold">Rp 40,000/hari</span>
                </div>
                <button class="w-full bg-slate-700 text-white py-2 px-4 rounded cursor-not-allowed">
                  Tidak Tersedia
                </button>
              </div>
            </div>
            <div class="bg-slate-900 border border-white/10 rounded-3xl shadow-2xl shadow-slate-950/40 overflow-hidden hover:shadow-slate-900 transition duration-300">
              <div class="h-48 bg-slate-800 flex items-center justify-center">
                <span class="text-slate-400">Gambar Alat</span>
              </div>
              <div class="p-4">
                <h3 class="font-semibold text-white">Palu</h3>
                <p class="text-slate-300 text-sm mb-2">Alat untuk memukul</p>
                <div class="flex justify-between items-center mb-3">
                  <span class="text-emerald-300 font-semibold">Tersedia</span>
                  <span class="text-slate-400 font-semibold">Rp 25,000/hari</span>
                </div>
                <button class="w-full bg-cyan-500 text-slate-950 py-2 px-4 rounded hover:bg-cyan-400 transition duration-300">
                  Pinjam Sekarang
                </button>
              </div>
            </div>
            <div class="bg-slate-900 border border-white/10 rounded-3xl shadow-2xl shadow-slate-950/40 overflow-hidden hover:shadow-slate-900 transition duration-300">
              <div class="h-48 bg-slate-800 flex items-center justify-center">
                <span class="text-slate-400">Gambar Alat</span>
              </div>
              <div class="p-4">
                <h3 class="font-semibold text-white">Obeng Set</h3>
                <p class="text-slate-300 text-sm mb-2">Set obeng berbagai ukuran</p>
                <div class="flex justify-between items-center mb-3">
                  <span class="text-emerald-300 font-semibold">Tersedia</span>
                  <span class="text-slate-400 font-semibold">Rp 30,000/hari</span>
                </div>
                <button class="w-full bg-cyan-500 text-slate-950 py-2 px-4 rounded hover:bg-cyan-400 transition duration-300">
                  Pinjam Sekarang
                </button>
              </div>
            </div>
            <div class="bg-slate-900 border border-white/10 rounded-3xl shadow-2xl shadow-slate-950/40 overflow-hidden hover:shadow-slate-900 transition duration-300">
              <div class="h-48 bg-slate-800 flex items-center justify-center">
                <span class="text-slate-400">Gambar Alat</span>
              </div>
              <div class="p-4">
                <h3 class="font-semibold text-white">Tangga Lipat</h3>
                <p class="text-slate-300 text-sm mb-2">Tangga untuk pekerjaan tinggi</p>
                <div class="flex justify-between items-center mb-3">
                  <span class="text-amber-300 font-semibold">Maintenance</span>
                  <span class="text-slate-400 font-semibold">Rp 35,000/hari</span>
                </div>
                <button class="w-full bg-slate-700 text-white py-2 px-4 rounded cursor-not-allowed">
                  Dalam Perbaikan
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Peminjaman Saya -->
        <div>
          <h2 class="text-2xl font-bold text-white mb-4">Peminjaman Saya</h2>
          <div class="bg-slate-900 border border-white/10 rounded-3xl shadow-2xl shadow-slate-950/40 overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-slate-800">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Tanggal Kembali</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Total Biaya</th>
                  </tr>
                </thead>
                <tbody class="bg-slate-950 divide-y divide-slate-800">
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">Bor Listrik</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">2026-04-15</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">2026-04-18</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                        Dikembalikan
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">Rp 150,000</td>
                  </tr>
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">Gergaji Mesin</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">2026-04-10</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">2026-04-12</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-cyan-100 text-cyan-800">
                        Dipinjam
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">Rp 150,000</td>
                  </tr>
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">Cat Semprot</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">2026-04-08</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">2026-04-10</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                        Pending Return
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">Rp 80,000</td>
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