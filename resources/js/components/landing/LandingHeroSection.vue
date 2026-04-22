<template>
  <nav class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/90 dark:bg-slate-900/90 dark:border-slate-700/50 backdrop-blur-xl shadow-xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex h-20 items-center gap-6">
        <div class="flex shrink-0 items-center gap-3">
          <BrandLogo brand-name="TrustEquip.id" subtitle="Sistem Peminjaman Peralatan" size="sm" />
        </div>

        <div class="ml-auto flex flex-1 items-center justify-end gap-3 sm:gap-6">
          <button
            type="button"
            :aria-label="isDarkMode ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'"
            :title="isDarkMode ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'"
            @click="$emit('toggle-dark-mode')"
            class="w-8 h-8 bg-cyan-400/15 dark:bg-slate-800 border border-cyan-300/25 dark:border-slate-600 rounded-full flex items-center justify-center hover:bg-cyan-400/25 dark:hover:bg-slate-700 transition-colors"
          >
            <svg v-if="!isDarkMode" class="w-4 h-4 text-cyan-500 dark:text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <svg v-else class="w-4 h-4 text-cyan-600 dark:text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
          </button>

          <button
            type="button"
            class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white/70 text-slate-700 transition hover:bg-white dark:border-slate-700 dark:bg-slate-900/70 dark:text-slate-200 dark:hover:bg-slate-900"
            :aria-label="mobileMenuOpen ? 'Tutup menu' : 'Buka menu'"
            @click="$emit('toggle-mobile-menu')"
          >
            <svg v-if="!mobileMenuOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>

          <div class="hidden md:flex items-center justify-end gap-8 text-sm font-medium text-slate-600 dark:text-slate-300">
            <a href="#home" class="transition hover:text-cyan-600 dark:hover:text-cyan-300">Home</a>
            <a href="#fitur" class="transition hover:text-cyan-600 dark:hover:text-cyan-300">Fitur</a>
            <a href="#alur" class="transition hover:text-cyan-600 dark:hover:text-cyan-300">Alur</a>
            <a href="#alat" class="transition hover:text-cyan-600 dark:hover:text-cyan-300">Alat</a>
            <a href="#testimoni" class="transition hover:text-cyan-600 dark:hover:text-cyan-300">Testimoni</a>
            <a href="#hubungi" class="transition hover:text-cyan-600 dark:hover:text-cyan-300">Kontak</a>
          </div>

          <router-link to="/login" class="inline-flex items-center justify-center rounded-full bg-cyan-500 px-5 py-2 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400">Login</router-link>
        </div>
      </div>
    </div>

    <div v-if="mobileMenuOpen" class="md:hidden border-t border-slate-200/70 dark:border-slate-700/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="grid gap-2 text-sm font-semibold text-slate-700 dark:text-slate-200">
          <a href="#home" class="rounded-2xl px-4 py-3 transition hover:bg-slate-100 dark:hover:bg-slate-800/60" @click="$emit('toggle-mobile-menu')">Home</a>
          <a href="#fitur" class="rounded-2xl px-4 py-3 transition hover:bg-slate-100 dark:hover:bg-slate-800/60" @click="$emit('toggle-mobile-menu')">Fitur</a>
          <a href="#alur" class="rounded-2xl px-4 py-3 transition hover:bg-slate-100 dark:hover:bg-slate-800/60" @click="$emit('toggle-mobile-menu')">Alur</a>
          <a href="#alat" class="rounded-2xl px-4 py-3 transition hover:bg-slate-100 dark:hover:bg-slate-800/60" @click="$emit('toggle-mobile-menu')">Alat</a>
          <a href="#testimoni" class="rounded-2xl px-4 py-3 transition hover:bg-slate-100 dark:hover:bg-slate-800/60" @click="$emit('toggle-mobile-menu')">Testimoni</a>
          <a href="#hubungi" class="rounded-2xl px-4 py-3 transition hover:bg-slate-100 dark:hover:bg-slate-800/60" @click="$emit('toggle-mobile-menu')">Kontak</a>
        </div>
      </div>
    </div>
  </nav>

  <section id="home" class="relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(56,189,248,0.16),_transparent_24%)] pointer-events-none"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_right,_rgba(16,185,129,0.12),_transparent_28%)] pointer-events-none"></div>
    <div class="absolute -left-12 top-24 h-44 w-44 rounded-full bg-cyan-400/15 blur-3xl" :style="shape1Style"></div>
    <div class="absolute right-0 top-1/3 h-64 w-64 rounded-full bg-emerald-400/10 blur-3xl" :style="shape2Style"></div>
    <div class="absolute left-1/4 bottom-0 h-32 w-32 rounded-full bg-sky-500/10 blur-3xl" :style="shape3Style"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
      <div class="grid gap-10 lg:grid-cols-[1.3fr_0.9fr] items-center">
        <div class="space-y-6">
          <p class="inline-flex rounded-full bg-cyan-500/15 px-4 py-1 text-sm font-semibold uppercase tracking-[0.24em] text-cyan-700 dark:text-cyan-300">TrustEquip.id</p>
          <h1 class="text-4xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-5xl lg:text-6xl">TrustEquip.id</h1>
          <p class="max-w-2xl text-lg text-slate-600 dark:text-slate-300 sm:text-xl">Keamanan dan kenyamanan bagi semua pengguna</p>
          <div class="flex flex-wrap gap-4">
            <router-link to="/login" class="inline-flex items-center justify-center rounded-full bg-cyan-500 px-6 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-cyan-500/20 transition hover:bg-cyan-400">Masuk Sistem</router-link>
            <a href="#alat" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-slate-100/90 dark:bg-slate-800/80 px-6 py-3 text-sm font-semibold text-slate-900 dark:text-slate-200 transition hover:border-cyan-300 hover:text-cyan-700 dark:hover:text-cyan-200">Lihat Katalog</a>
          </div>
        </div>

        <div class="relative">
          <div class="rounded-[2rem] border border-cyan-200/70 bg-cyan-50/90 dark:bg-cyan-500/10 p-6 shadow-2xl shadow-slate-950/40 ring-1 ring-cyan-200/70 dark:ring-cyan-500/20">
            <div class="grid gap-4 sm:grid-cols-2">
              <div class="rounded-3xl bg-slate-100/90 dark:bg-slate-950/90 p-5 shadow-inner shadow-slate-900/10">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">Ketersediaan</p>
                <h2 class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">
                  {{ statsLoading ? 'Memuat...' : `${availabilityPercent}% Aktif` }}
                </h2>
              </div>
              <div class="rounded-3xl bg-cyan-500/10 p-5 ring-1 ring-cyan-300/20">
                <p class="text-xs uppercase tracking-[0.3em] text-cyan-700 dark:text-cyan-200">Kepercayaan</p>
                <h2 class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">
                  {{ statsLoading ? 'Memuat...' : trustScoreLabel }}
                </h2>
              </div>
              <div class="sm:col-span-2 rounded-3xl bg-cyan-50/90 dark:bg-cyan-500/10 p-5 border border-cyan-200/70 dark:border-cyan-500/20">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">Peminjaman</p>
                    <h3 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">
                      {{ statsLoading ? 'Memuat...' : totalBorrowingsLabel }}
                    </h3>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import BrandLogo from '../BrandLogo.vue'

defineProps({
  isDarkMode: {
    type: Boolean,
    default: false,
  },
  mobileMenuOpen: {
    type: Boolean,
    default: false,
  },
  statsLoading: {
    type: Boolean,
    default: true,
  },
  availabilityPercent: {
    type: Number,
    default: 0,
  },
  trustScoreLabel: {
    type: String,
    default: '0/10',
  },
  totalBorrowingsLabel: {
    type: String,
    default: '0',
  },
  shape1Style: {
    type: Object,
    default: () => ({}),
  },
  shape2Style: {
    type: Object,
    default: () => ({}),
  },
  shape3Style: {
    type: Object,
    default: () => ({}),
  },
})

defineEmits(['toggle-dark-mode', 'toggle-mobile-menu'])
</script>
