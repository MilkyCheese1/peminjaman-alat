<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <SidebarPeminjam />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-6">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Profil Peminjam</h1>
          <p class="text-slate-700 dark:text-slate-300">Kelola informasi akun Anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Profile Card -->
          <div class="lg:col-span-1">
            <div class="space-y-6">
              <div class="app-card app-card--cyan p-6">
                <div class="text-center">
                  <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center border border-slate-300 dark:border-white/10">
                    <svg class="w-12 h-12 text-cyan-600 dark:text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                  </div>
                  <h2 class="text-xl font-bold text-slate-900 dark:text-white">Peminjam User</h2>
                  <p class="text-slate-600 dark:text-slate-400 text-sm mt-1">Peminjam</p>
                  <button class="mt-4 w-full bg-cyan-500 hover:bg-cyan-400 text-slate-950 py-2 px-4 rounded-full font-semibold transition">
                    Ubah Foto
                  </button>

                  <div class="mt-6 border-t border-slate-200 pt-5 dark:border-white/10 text-left">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Menu Akun</p>
                    <div class="mt-3 grid grid-cols-1 gap-2">
                      <a href="#data-diri" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800">
                        Data Diri
                      </a>
                      <a href="#kontak-darurat" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800">
                        Kontak Darurat
                      </a>
                      <a href="#keamanan-akun" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800">
                        Keamanan Akun
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <div id="keamanan-akun" class="app-card app-card--rose p-6 scroll-mt-28">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Keamanan Akun</h3>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Ubah password akun untuk meningkatkan keamanan.</p>
                <div class="mt-5 space-y-4">
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Password Saat Ini</label>
                    <input type="password" placeholder="••••••••" class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-500 focus:border-cyan-500 focus:outline-none" />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Password Baru</label>
                    <input type="password" placeholder="Minimal 8 karakter" class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-500 focus:border-cyan-500 focus:outline-none" />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Konfirmasi Password Baru</label>
                    <input type="password" placeholder="Ulangi password baru" class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-500 focus:border-cyan-500 focus:outline-none" />
                  </div>
                  <button type="button" class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-950 py-3 px-4 rounded-full font-semibold transition">
                    Simpan Password
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Info Form -->
          <div class="lg:col-span-2">
            <div class="app-card app-card--cyan p-6">
              <h2 id="data-diri" class="text-xl font-bold text-slate-900 dark:text-white mb-6 scroll-mt-28">Data Diri</h2>
              <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nama Lengkap</label>
                    <input type="text" value="Peminjam User" class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 focus:border-cyan-500 focus:outline-none" />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Username</label>
                    <input type="text" value="peminjam_user" class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 focus:border-cyan-500 focus:outline-none" />
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Email</label>
                  <input type="email" value="peminjam@peminjaman-alat.com" class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 focus:border-cyan-500 focus:outline-none" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">NIK</label>
                    <input type="text" placeholder="Nomor identitas" class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-500 focus:border-cyan-500 focus:outline-none" />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Tanggal Lahir</label>
                    <input type="date" class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 focus:border-cyan-500 focus:outline-none" />
                  </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Jenis Kelamin</label>
                    <select class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 focus:border-cyan-500 focus:outline-none">
                      <option value="">Pilih</option>
                      <option>Laki-laki</option>
                      <option>Perempuan</option>
                      <option>Lainnya</option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Instansi / Divisi</label>
                    <input type="text" placeholder="Contoh: Operasional" class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-500 focus:border-cyan-500 focus:outline-none" />
                  </div>
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Provinsi</label>
                    <input type="text" placeholder="Provinsi" class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-500 focus:border-cyan-500 focus:outline-none" />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Negara</label>
                    <input type="text" placeholder="Indonesia" class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-500 focus:border-cyan-500 focus:outline-none" />
                  </div>
                </div>

                <div id="kontak-darurat" class="app-card app-card--amber p-5 scroll-mt-28">
                  <h3 class="text-sm font-bold text-slate-900 dark:text-white">Kontak Darurat</h3>
                  <p class="mt-1 text-xs text-slate-600 dark:text-slate-400">Opsional, untuk keperluan internal.</p>
                  <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nama Kontak</label>
                      <input type="text" placeholder="Contoh: Budi" class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-500 focus:border-cyan-500 focus:outline-none" />
                    </div>
                    <div>
                      <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nomor Kontak</label>
                      <input type="tel" placeholder="+62 ..." class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-500 focus:border-cyan-500 focus:outline-none" />
                    </div>
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
import SidebarPeminjam from '../components/layout/SidebarPeminjam.vue'
import Navbar from '../components/layout/Navbar.vue'

export default {
  name: 'AkunPeminjam',
  components: {
    SidebarPeminjam,
    Navbar
  }
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
