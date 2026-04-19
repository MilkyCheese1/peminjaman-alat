<template>
  <div class="min-h-screen bg-slate-950 flex">
    <SidebarPeminjam />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-6">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-white">Peminjaman Saya</h1>
          <p class="text-slate-300">Lihat riwayat dan status peminjaman alat Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
          <div class="bg-slate-900/90 border border-white/10 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <h2 class="text-xl font-semibold text-white mb-3">Ringkasan Peminjaman</h2>
            <div class="space-y-4 text-slate-300">
              <div class="rounded-3xl bg-slate-800 p-4">
                <p class="text-sm">Peminjaman Aktif</p>
                <p class="mt-2 text-3xl font-semibold text-white">2</p>
              </div>
              <div class="rounded-3xl bg-slate-800 p-4">
                <p class="text-sm">Total Denda</p>
                <p class="mt-2 text-3xl font-semibold text-cyan-300">Rp 0</p>
              </div>
            </div>
          </div>
          <div class="bg-slate-900/90 border border-white/10 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
            <h2 class="text-xl font-semibold text-white mb-3">Status Terbaru</h2>
            <ul class="space-y-4 text-slate-300">
              <li class="rounded-3xl bg-slate-800 p-4 border border-slate-700">
                <p class="font-semibold text-white">Bor Listrik</p>
                <p class="text-sm text-slate-400">Dipinjam sampai 2026-04-18</p>
                <span class="inline-flex rounded-full bg-emerald-500/15 px-3 py-1 text-sm text-emerald-300 mt-3">Sedang Aktif</span>
              </li>
              <li class="rounded-3xl bg-slate-800 p-4 border border-slate-700">
                <p class="font-semibold text-white">Gergaji Mesin</p>
                <p class="text-sm text-slate-400">Pengembalian pada 2026-04-12</p>
                <span class="inline-flex rounded-full bg-cyan-500/15 px-3 py-1 text-sm text-cyan-200 mt-3">Tertunda</span>
              </li>
            </ul>
          </div>
        </div>

        <section>
          <div class="mb-6">
            <h2 class="text-2xl font-bold text-white">Riwayat Peminjaman</h2>
            <p class="text-slate-400">Daftar peminjaman alat yang pernah Anda lakukan.</p>
          </div>
          <div class="bg-slate-900/90 border border-white/10 rounded-3xl shadow-2xl shadow-slate-950/40 overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full min-w-[720px]">
                <thead class="bg-slate-800 text-slate-300">
                  <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.16em]">Alat</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.16em]">Tanggal Pinjam</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.16em]">Tanggal Kembali</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.16em]">Status</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-[0.16em]">Total</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-slate-200">
                  <tr>
                    <td class="px-6 py-4">Bor Listrik</td>
                    <td class="px-6 py-4">2026-04-15</td>
                    <td class="px-6 py-4">2026-04-18</td>
                    <td class="px-6 py-4">
                      <span class="rounded-full bg-emerald-500/15 px-3 py-1 text-sm text-emerald-300">Selesai</span>
                    </td>
                    <td class="px-6 py-4">Rp 150,000</td>
                  </tr>
                  <tr>
                    <td class="px-6 py-4">Gergaji Mesin</td>
                    <td class="px-6 py-4">2026-04-10</td>
                    <td class="px-6 py-4">2026-04-12</td>
                    <td class="px-6 py-4">
                      <span class="rounded-full bg-cyan-500/15 px-3 py-1 text-sm text-cyan-200">Selesai</span>
                    </td>
                    <td class="px-6 py-4">Rp 150,000</td>
                  </tr>
                  <tr>
                    <td class="px-6 py-4">Cat Semprot</td>
                    <td class="px-6 py-4">2026-04-08</td>
                    <td class="px-6 py-4">2026-04-10</td>
                    <td class="px-6 py-4">
                      <span class="rounded-full bg-amber-500/15 px-3 py-1 text-sm text-amber-200">Pending</span>
                    </td>
                    <td class="px-6 py-4">Rp 80,000</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>
</template>

<script>
import SidebarPeminjam from '../components/layout/SidebarPeminjam.vue';
import Navbar from '../components/layout/Navbar.vue';

export default {
  name: 'PeminjamanSaya',
  components: {
    SidebarPeminjam,
    Navbar,
  },
};
</script>

<style scoped>
/* Custom styles if needed */
</style>
