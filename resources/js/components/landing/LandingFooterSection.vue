<template>
  <footer class="relative border-t border-slate-200/70 dark:border-white/10 bg-slate-50 dark:bg-slate-950 pt-16 pb-8">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_right,_rgba(56,189,248,0.08),_transparent_40%)] pointer-events-none"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid gap-12 md:grid-cols-4 mb-12">
        <div class="md:col-span-1">
          <div class="flex items-center gap-3 mb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-cyan-400/15 border border-cyan-300/25 text-cyan-700 dark:text-cyan-300 shadow-lg shadow-cyan-500/10">
              <span class="text-lg font-black">T</span>
            </div>
            <div>
              <h3 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">TrustEquip.id</h3>
              <p class="text-xs text-slate-500 dark:text-slate-400">Sistem Peminjaman Peralatan</p>
            </div>
          </div>
          <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Sistem peminjaman peralatan untuk meningkatkan produktivitas organisasi.</p>
          <div class="flex gap-3">
            <a href="#" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-cyan-500/10 text-cyan-700 dark:text-cyan-300 ring-1 ring-cyan-300/20 transition hover:bg-cyan-500/20">
              <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3a9 9 0 01-9 9m0 0a9 9 0 01-9-9m9 9v8m0-8h8m-8 0H3m9 0a9 9 0 019 9"/></svg>
            </a>
            <a href="#" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-cyan-500/10 text-cyan-700 dark:text-cyan-300 ring-1 ring-cyan-300/20 transition hover:bg-cyan-500/20">
              <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20v-7.21H5.5V9.25h2.79V7.61c0-2.74 1.67-4.24 4.12-4.24 1.17 0 2.18.09 2.47.13v2.86h-1.7c-1.33 0-1.59.63-1.59 1.56V9.25h3.19l-4.16 6.19h-3.19V20z"/></svg>
            </a>
            <a href="#" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-cyan-500/10 text-cyan-700 dark:text-cyan-300 ring-1 ring-cyan-300/20 transition hover:bg-cyan-500/20">
              <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2s9 5 20 5a9.5 9.5 0 00-9-5.5c4.75 2.25 7-7 7-7"/></svg>
            </a>
          </div>
        </div>

        <div>
          <p class="mb-4 text-sm font-semibold uppercase tracking-[0.3em] text-cyan-700/80 dark:text-cyan-300/80">Alat</p>
          <div class="space-y-3">
            <a href="#fitur" class="block text-sm text-slate-500 dark:text-slate-400 transition hover:text-cyan-600 dark:hover:text-cyan-300">Fitur Lengkap</a>
            <a href="#alat" class="block text-sm text-slate-500 dark:text-slate-400 transition hover:text-cyan-600 dark:hover:text-cyan-300">Katalog Alat</a>
            <a href="#" class="block text-sm text-slate-500 dark:text-slate-400 transition hover:text-cyan-600 dark:hover:text-cyan-300">Harga Sewa</a>
            <a href="#" class="block text-sm text-slate-500 dark:text-slate-400 transition hover:text-cyan-600 dark:hover:text-cyan-300">Promo Terbaru</a>
          </div>
        </div>

        <div>
          <p class="mb-4 text-sm font-semibold uppercase tracking-[0.3em] text-cyan-700/80 dark:text-cyan-300/80">Perusahaan</p>
          <div class="space-y-3">
            <a href="#" class="block text-sm text-slate-500 dark:text-slate-400 transition hover:text-cyan-600 dark:hover:text-cyan-300">Tentang Kami</a>
            <a href="#" class="block text-sm text-slate-500 dark:text-slate-400 transition hover:text-cyan-600 dark:hover:text-cyan-300">Blog</a>
            <a href="#" class="block text-sm text-slate-500 dark:text-slate-400 transition hover:text-cyan-600 dark:hover:text-cyan-300">Kebijakan Privasi</a>
            <a href="#" class="block text-sm text-slate-500 dark:text-slate-400 transition hover:text-cyan-600 dark:hover:text-cyan-300">Syarat & Ketentuan</a>
          </div>
        </div>

        <div>
          <p class="mb-4 text-sm font-semibold uppercase tracking-[0.3em] text-cyan-700/80 dark:text-cyan-300/80">Kontak</p>
          <div class="space-y-3">
            <div class="text-sm text-slate-500 dark:text-slate-400">
              <p class="text-slate-700 dark:text-slate-300 font-medium mb-1">Email</p>
              <a href="mailto:support@trustequip.id" class="transition hover:text-cyan-600 dark:hover:text-cyan-300">support@trustequip.id</a>
            </div>
            <div class="text-sm text-slate-500 dark:text-slate-400">
              <p class="text-slate-700 dark:text-slate-300 font-medium mb-1">Telepon</p>
              <a href="tel:+62812345678" class="transition hover:text-cyan-600 dark:hover:text-cyan-300">+62 812-345-678</a>
            </div>
            <div class="text-sm text-slate-500 dark:text-slate-400">
              <p class="text-slate-700 dark:text-slate-300 font-medium mb-1">Alamat</p>
              <p>Jakarta, Indonesia</p>
            </div>
          </div>
        </div>
      </div>

      <div class="border-t border-slate-200/70 dark:border-white/10 pt-8">
        <div class="grid gap-6 sm:grid-cols-2 items-center">
          <p class="text-sm text-slate-500 dark:text-slate-400">&copy; 2026 TrustEquip.id. Semua hak dilindungi.</p>
          <div class="flex justify-start sm:justify-end gap-6">
            <a href="#" class="text-sm text-slate-500 dark:text-slate-400 transition hover:text-cyan-600 dark:hover:text-cyan-300">Privasi</a>
            <a href="#" class="text-sm text-slate-500 dark:text-slate-400 transition hover:text-cyan-600 dark:hover:text-cyan-300">Syarat</a>
            <a href="#" class="text-sm text-slate-500 dark:text-slate-400 transition hover:text-cyan-600 dark:hover:text-cyan-300">Cookies</a>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>
