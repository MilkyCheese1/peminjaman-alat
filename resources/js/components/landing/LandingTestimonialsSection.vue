<template>
  <section id="testimoni" class="py-24 bg-slate-50/95 dark:bg-slate-950/95">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-14 text-center">
        <p class="text-sm uppercase tracking-[0.3em] text-cyan-700/80 dark:text-cyan-300/80">Ulasan Pengguna</p>
        <h2 class="mt-3 text-4xl font-semibold tracking-tight text-slate-900 dark:text-white">Dipercaya oleh pekerja, pelajar, dan kontraktor</h2>
      </div>

      <div class="relative overflow-hidden">
        <div v-if="testimonialsLoading" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <div v-for="index in 3" :key="index" class="rounded-[2rem] border border-slate-200/70 dark:border-white/10 bg-white/70 dark:bg-slate-900/70 p-7 shadow-xl shadow-slate-950/10">
            <div class="animate-pulse space-y-4">
              <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-3xl bg-slate-200 dark:bg-slate-700"></div>
                <div class="space-y-2">
                  <div class="h-4 w-32 rounded bg-slate-200 dark:bg-slate-700"></div>
                  <div class="h-3 w-20 rounded bg-slate-200 dark:bg-slate-700"></div>
                </div>
              </div>
              <div class="h-4 w-28 rounded bg-slate-200 dark:bg-slate-700"></div>
              <div class="space-y-2">
                <div class="h-4 rounded bg-slate-200 dark:bg-slate-700"></div>
                <div class="h-4 rounded bg-slate-200 dark:bg-slate-700"></div>
                <div class="h-4 w-4/5 rounded bg-slate-200 dark:bg-slate-700"></div>
              </div>
            </div>
          </div>
        </div>

        <div
          v-else
          class="flex transition-transform duration-500 ease-in-out"
          :style="{ transform: `translateX(-${currentTestimonialSlide * (100 / testimonialVisibleSlides)}%)` }"
        >
          <div
            v-for="(item, index) in [...testimonials, ...testimonials]"
            :key="`${item.name}-${index}`"
            class="flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-3"
          >
            <article class="bento-card h-full rounded-[2rem] border border-cyan-200/70 dark:border-cyan-500/20 bg-gradient-to-br from-cyan-50 via-white to-cyan-100/80 dark:from-cyan-500/10 dark:via-slate-900/80 dark:to-cyan-500/5 p-7 shadow-2xl shadow-slate-950/20">
              <div class="flex h-full flex-col">
                <div class="mb-5 flex items-center gap-3">
                  <div class="flex h-12 w-12 items-center justify-center rounded-3xl" :class="item.avatarClass">{{ item.initial }}</div>
                  <div>
                    <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ item.name }}</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ item.role }}</p>
                  </div>
                </div>
                <div class="mb-4 flex gap-1">
                  <span
                    v-for="star in 5"
                    :key="star"
                    class="text-lg"
                    :class="star <= item.stars ? 'text-yellow-400' : 'text-slate-300 dark:text-slate-600'"
                  >
                    &#9733;
                  </span>
                </div>
                <p class="flex-1 text-slate-600 dark:text-slate-400 leading-7">
                  "{{ item.message }}"
                </p>
              </div>
            </article>
          </div>
        </div>

        <button
          type="button"
          class="absolute left-4 top-1/2 -translate-y-1/2 rounded-full bg-slate-950/80 p-3 text-white shadow-lg shadow-slate-950/20 backdrop-blur-sm transition hover:bg-slate-800 dark:bg-slate-100/10 dark:hover:bg-slate-100/20"
          aria-label="Testimoni sebelumnya"
          @click="$emit('prev')"
        >
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <button
          type="button"
          class="absolute right-4 top-1/2 -translate-y-1/2 rounded-full bg-slate-950/80 p-3 text-white shadow-lg shadow-slate-950/20 backdrop-blur-sm transition hover:bg-slate-800 dark:bg-slate-100/10 dark:hover:bg-slate-100/20"
          aria-label="Testimoni berikutnya"
          @click="$emit('next')"
        >
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </div>

      <div class="mt-6 flex justify-center gap-2">
        <button
          v-for="(item, index) in testimonials"
          :key="item.id ?? item.name"
          type="button"
          class="h-3 w-3 rounded-full transition"
          :class="testimonials.length && currentTestimonialSlide % testimonials.length === index ? 'bg-cyan-500' : 'bg-slate-300 dark:bg-slate-700'"
          :aria-label="`Lihat testimoni ${item.name}`"
          @click="$emit('go-to', index)"
        />
      </div>

      <div class="mt-16 rounded-[2rem] border border-slate-200/70 bg-slate-100/90 dark:bg-slate-900/80 p-10 shadow-2xl shadow-slate-950/10">
        <h3 class="text-center text-2xl font-semibold text-slate-900 dark:text-white">Berikan Penilaian Anda</h3>
        <p class="mt-2 text-center text-slate-600 dark:text-slate-400">Bagikan pengalaman Anda menggunakan TrustEquip.id</p>

        <form @submit.prevent="$emit('submit')" class="mt-8 space-y-6 max-w-2xl mx-auto">
          <div class="grid gap-6 md:grid-cols-2">
            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Nama Lengkap</label>
              <input
                v-model="rating.name"
                type="text"
                required
                placeholder="Masukkan nama Anda"
                class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white/90 dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400 transition focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-500/20"
              >
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Email</label>
              <input
                v-model="rating.email"
                type="email"
                required
                placeholder="nama@email.com"
                class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white/90 dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400 transition focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-500/20"
              >
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">Penilaian Bintang</label>
            <div class="flex gap-3">
              <button
                v-for="star in 5"
                :key="star"
                @click.prevent="rating.stars = star"
                type="button"
                class="text-4xl transition hover:scale-110"
                :class="star <= rating.stars ? 'text-yellow-400' : 'text-slate-600'"
              >
                â˜…
              </button>
            </div>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
              <span class="font-semibold text-slate-700 dark:text-slate-300">{{ rating.stars }}</span> dari 5 bintang
            </p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Testimoni / Review</label>
            <textarea
              v-model="rating.message"
              required
              rows="5"
              placeholder="Bagikan pengalaman Anda menggunakan TrustEquip.id..."
              class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white/90 dark:bg-slate-950 px-4 py-3 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400 transition focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 resize-none"
            ></textarea>
          </div>

          <button
            type="submit"
            class="w-full rounded-2xl bg-cyan-500 px-6 py-3 font-semibold text-slate-950 shadow-lg shadow-cyan-500/20 transition hover:bg-cyan-400"
          >
            Kirim Penilaian
          </button>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
defineProps({
  testimonialsLoading: {
    type: Boolean,
    default: true,
  },
  testimonials: {
    type: Array,
    default: () => [],
  },
  currentTestimonialSlide: {
    type: Number,
    default: 0,
  },
  testimonialVisibleSlides: {
    type: Number,
    default: 1,
  },
  rating: {
    type: Object,
    default: () => ({
      name: '',
      email: '',
      stars: 0,
      message: '',
    }),
  },
})

defineEmits(['prev', 'next', 'go-to', 'submit'])
</script>

<style scoped>
.bento-card {
  transition: transform 0.3s ease, border-color 0.3s ease, background-color 0.3s ease;
}

.bento-card:hover {
  transform: translateY(-6px);
  border-color: rgba(56, 189, 248, 0.35);
  background-color: rgba(240, 249, 255, 0.98);
}

:global(.dark) .bento-card:hover,
:global([data-theme='dark']) .bento-card:hover {
  background-color: rgba(15, 23, 42, 0.98);
}
</style>
