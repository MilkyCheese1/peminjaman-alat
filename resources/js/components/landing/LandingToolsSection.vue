<template>
  <section id="alat" class="py-24 bg-slate-50 dark:bg-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-14 text-center">
        <p class="text-sm uppercase tracking-[0.3em] text-cyan-700/80 dark:text-cyan-300/80">Sistem Peminjaman</p>
        <h2 class="mt-3 text-4xl font-semibold tracking-tight text-slate-900 dark:text-white">Peralatan untuk Karyawan</h2>
        <p class="mt-4 max-w-2xl mx-auto text-base text-slate-600 dark:text-slate-400">Koleksi lengkap peralatan yang tersedia untuk peminjaman oleh organisasi.</p>
      </div>

      <div class="relative overflow-hidden">
        <div v-if="toolCards.length" class="flex transition-transform duration-500 ease-in-out" :style="{ transform: `translateX(-${currentSlide * (100 / visibleSlides)}%)` }">
          <div v-for="(item, index) in toolCarouselItems" :key="`${item.id}-${index}`" class="flex-shrink-0 w-full md:w-1/2 lg:w-1/3 xl:w-1/4 px-3">
            <div class="group relative rounded-[2rem] bg-cyan-50/90 dark:bg-cyan-500/10 p-6 ring-1 ring-cyan-200/70 dark:ring-cyan-500/20 shadow-2xl shadow-slate-950/20 overflow-hidden hover:ring-cyan-300/30 transition-all duration-300">
              <div class="relative aspect-[4/3] mb-4 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800">
                <img :src="item.gambar" :alt="item.nama" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-transparent"></div>
                <div class="absolute left-3 top-3 flex flex-wrap gap-2">
                  <span class="inline-flex items-center gap-1 rounded-full bg-cyan-500/90 px-3 py-1 text-xs font-medium text-white">
                    {{ item.kategori }}
                  </span>
                  <span class="inline-flex items-center gap-1 rounded-full bg-slate-950/75 px-3 py-1 text-xs font-medium text-white backdrop-blur">
                    {{ item.statusLabel }}
                  </span>
                </div>
                <div class="absolute bottom-3 right-3">
                  <span class="inline-flex items-center gap-1 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-900 shadow-sm backdrop-blur dark:bg-slate-950/80 dark:text-white">
                    {{ item.stok }} stok
                  </span>
                </div>
              </div>

              <div class="space-y-4">
                <div class="space-y-1">
                  <h3 class="text-xl font-semibold text-slate-900 dark:text-white group-hover:text-cyan-700 dark:group-hover:text-cyan-300 transition-colors">{{ item.nama }}</h3>
                  <p class="text-xs uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">{{ item.kategori }}</p>
                </div>
                <p class="text-sm leading-6 text-slate-600 dark:text-slate-300 line-clamp-3">{{ item.deskripsi }}</p>
                <div class="space-y-3 pt-1">
                  <span class="block text-xs font-medium text-slate-500 dark:text-slate-400">Deskripsi alat dari database</span>
                  <button class="w-full rounded-full bg-cyan-500 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-400 transition-colors">
                    Ajukan Peminjaman
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-10 text-center text-slate-600 dark:border-slate-700 dark:bg-slate-900/60 dark:text-slate-300">
          <p class="text-lg font-semibold text-slate-900 dark:text-white">Belum ada data alat</p>
          <p class="mt-2 text-sm leading-6">Isi tabel alat terlebih dahulu agar kartu peralatan tampil di landing page.</p>
        </div>

        <button v-if="toolCards.length" type="button" @click="$emit('prev')" class="absolute left-4 top-1/2 -translate-y-1/2 rounded-full bg-slate-800/80 p-3 text-white hover:bg-slate-700 transition-colors backdrop-blur-sm">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
        </button>
        <button v-if="toolCards.length" type="button" @click="$emit('next')" class="absolute right-4 top-1/2 -translate-y-1/2 rounded-full bg-slate-800/80 p-3 text-white hover:bg-slate-700 transition-colors backdrop-blur-sm">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
        </button>

        <div v-if="toolCards.length" class="flex justify-center mt-8 space-x-2">
          <button
            v-for="(item, index) in toolCards"
            :key="item.id ?? index"
            type="button"
            @click="$emit('go-to', index)"
            class="w-3 h-3 rounded-full transition-colors"
            :class="toolCards.length && currentSlide % toolCards.length === index ? 'bg-cyan-500' : 'bg-slate-600 hover:bg-slate-500'"
          ></button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
defineProps({
  toolCards: {
    type: Array,
    default: () => [],
  },
  toolCarouselItems: {
    type: Array,
    default: () => [],
  },
  currentSlide: {
    type: Number,
    default: 0,
  },
  visibleSlides: {
    type: Number,
    default: 1,
  },
})

defineEmits(['prev', 'next', 'go-to'])
</script>
