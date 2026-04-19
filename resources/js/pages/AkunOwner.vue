<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <SidebarOwner />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-6">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Profil Owner</h1>
          <p class="text-slate-700 dark:text-slate-300">Kelola informasi akun Anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Profile Card -->
          <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-white/10 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
              <div class="text-center">
                <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center border border-slate-300 dark:border-white/10">
                  <svg class="w-12 h-12 text-cyan-600 dark:text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Owner User</h2>
                <p class="text-slate-600 dark:text-slate-400 text-sm mt-1">Pemilik</p>
                <button class="mt-4 w-full bg-cyan-500 hover:bg-cyan-400 text-slate-950 py-2 px-4 rounded-full font-semibold transition">
                  Ubah Foto
                </button>
              </div>
            </div>
          </div>

          <!-- Info Form -->
          <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-white/10 rounded-3xl p-6 shadow-2xl shadow-slate-950/40">
              <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Informasi Akun</h2>
              <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nama Lengkap</label>
                    <input type="text" value="Owner User" class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 focus:border-cyan-500 focus:outline-none" />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Username</label>
                    <input type="text" value="owner_user" class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 focus:border-cyan-500 focus:outline-none" />
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Email</label>
                  <input type="email" value="owner@peminjaman-alat.com" class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 focus:border-cyan-500 focus:outline-none" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nomor Telepon</label>
                    <input type="tel" placeholder="+62 ..." class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-500 focus:border-cyan-500 focus:outline-none" />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Alamat</label>
                    <input type="text" placeholder="Alamat lengkap" class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-500 focus:border-cyan-500 focus:outline-none" />
                  </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Kota</label>
                    <input type="text" placeholder="Kota" class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-500 focus:border-cyan-500 focus:outline-none" />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Kode Pos</label>
                    <input type="text" placeholder="Kode Pos" class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-500 focus:border-cyan-500 focus:outline-none" />
                  </div>
                </div>

                <div class="flex gap-3 pt-4">
                  <button type="button" class="flex-1 bg-cyan-500 hover:bg-cyan-400 text-slate-950 py-3 px-4 rounded-full font-semibold transition">
                    Simpan Perubahan
                  </button>
                  <button type="button" class="flex-1 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 py-3 px-4 rounded-full font-semibold transition">
                    Batal
                  </button>
                </div>
              </form>
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
  name: 'AkunOwner',
  components: {
    SidebarOwner,
    Navbar
  }
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
