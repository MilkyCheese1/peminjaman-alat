<template>
  <div class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    <nav class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/90 dark:bg-slate-900/90 dark:border-slate-700/50 backdrop-blur-xl shadow-xl">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center gap-6">
          <div class="flex shrink-0 items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-cyan-400/15 border border-cyan-300/25 text-cyan-700 dark:text-cyan-300 shadow-lg shadow-cyan-500/10">
              <span class="text-xl font-black">T</span>
            </div>
            <div>
              <h1 class="text-xl font-semibold tracking-tight">Trustequip</h1>
              <p class="text-xs uppercase tracking-[0.28em] text-slate-400">Sistem Peminjaman Alat</p>
            </div>
          </div>

          <div class="ml-auto flex flex-1 items-center justify-end gap-3 sm:gap-6">
            <button
              type="button"
              :aria-label="isDarkMode ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'"
              :title="isDarkMode ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'"
              @click="toggleDarkMode"
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
              @click="mobileMenuOpen = !mobileMenuOpen"
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
            <a href="#home" class="rounded-2xl px-4 py-3 transition hover:bg-slate-100 dark:hover:bg-slate-800/60" @click="mobileMenuOpen = false">Home</a>
            <a href="#fitur" class="rounded-2xl px-4 py-3 transition hover:bg-slate-100 dark:hover:bg-slate-800/60" @click="mobileMenuOpen = false">Fitur</a>
            <a href="#alat" class="rounded-2xl px-4 py-3 transition hover:bg-slate-100 dark:hover:bg-slate-800/60" @click="mobileMenuOpen = false">Alat</a>
            <a href="#testimoni" class="rounded-2xl px-4 py-3 transition hover:bg-slate-100 dark:hover:bg-slate-800/60" @click="mobileMenuOpen = false">Testimoni</a>
            <a href="#hubungi" class="rounded-2xl px-4 py-3 transition hover:bg-slate-100 dark:hover:bg-slate-800/60" @click="mobileMenuOpen = false">Kontak</a>
          </div>
        </div>
      </div>
    </nav>

    <main>
      <section id="home" class="relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(56,189,248,0.16),_transparent_24%)] pointer-events-none"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_right,_rgba(16,185,129,0.12),_transparent_28%)] pointer-events-none"></div>
        <div class="absolute -left-12 top-24 h-44 w-44 rounded-full bg-cyan-400/15 blur-3xl" :style="shape1Style"></div>
        <div class="absolute right-0 top-1/3 h-64 w-64 rounded-full bg-emerald-400/10 blur-3xl" :style="shape2Style"></div>
        <div class="absolute left-1/4 bottom-0 h-32 w-32 rounded-full bg-sky-500/10 blur-3xl" :style="shape3Style"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
          <div class="grid gap-10 lg:grid-cols-[1.3fr_0.9fr] items-center">
            <div class="space-y-6">
              <p class="inline-flex rounded-full bg-cyan-500/15 px-4 py-1 text-sm font-semibold uppercase tracking-[0.24em] text-cyan-700 dark:text-cyan-300">Sistem Internal • TrustEquip</p>
              <h1 class="text-4xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-5xl lg:text-6xl">Peminjaman Alat Kantor untuk Karyawan.</h1>
              <p class="max-w-2xl text-lg text-slate-600 dark:text-slate-300 sm:text-xl">Sistem peminjaman alat kantor internal yang efisien untuk meningkatkan produktivitas dan mengelola inventaris organisasi.</p>
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
                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">
                      {{ statsLoading ? 'Mengambil data alat dari database.' : `${availableToolsCount} dari ${totalToolsCount} alat siap dipinjam.` }}
                    </p>
                  </div>
                  <div class="rounded-3xl bg-cyan-500/10 p-5 ring-1 ring-cyan-300/20">
                    <p class="text-xs uppercase tracking-[0.3em] text-cyan-700 dark:text-cyan-200">Kepercayaan</p>
                    <h2 class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">
                      {{ statsLoading ? 'Memuat...' : trustScoreLabel }}
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-slate-700 dark:text-cyan-100/80">
                      {{ statsLoading ? 'Menghitung peminjaman yang selesai.' : `${completedBorrowingsCount} transaksi selesai dari ${totalBorrowingsCount} peminjaman.` }}
                    </p>
                  </div>
                  <div class="sm:col-span-2 rounded-3xl bg-cyan-50/90 dark:bg-cyan-500/10 p-5 border border-cyan-200/70 dark:border-cyan-500/20">
                    <div class="flex items-center justify-between">
                      <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">Peminjaman</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">
                          {{ statsLoading ? 'Memuat...' : totalBorrowingsLabel }}
                        </h3>
                      </div>
                      <div class="rounded-2xl bg-slate-100/80 dark:bg-slate-800/80 px-3 py-2 text-xs uppercase tracking-[0.3em] text-slate-700 dark:text-slate-300">Database</div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-600 dark:text-slate-400">Data peminjaman ditarik langsung dari tabel borrowings.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="fitur" class="relative overflow-hidden py-24 bg-slate-50/95 dark:bg-slate-950/95">
        <div class="absolute right-0 top-0 h-48 w-48 rounded-full bg-cyan-500/10 blur-3xl"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="mb-14 text-center">
            <p class="text-sm uppercase tracking-[0.3em] text-cyan-700/80 dark:text-cyan-300/80">Fitur Sistem Internal</p>
            <h2 class="mt-3 text-4xl font-semibold tracking-tight text-slate-900 dark:text-white">Manajemen Peminjaman Alat Kantor</h2>
            <p class="mt-4 max-w-2xl mx-auto text-base text-slate-600 dark:text-slate-400">Sistem terintegrasi untuk mengelola peminjaman alat kantor dengan efisien dan transparan.</p>
          </div>

          <div class="grid gap-6">
            <div class="summary-card rounded-[2rem] border border-cyan-200/70 dark:border-cyan-500/20 bg-cyan-50/95 dark:bg-cyan-500/10 p-8 shadow-2xl shadow-slate-950/20">
              <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                  <p class="text-xs font-semibold uppercase tracking-[0.28em] text-cyan-700/80 dark:text-cyan-200/80">Alur Peminjaman</p>
                  <h3 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 dark:text-white">Sederhana, cepat, dan transparan</h3>
                </div>
                <div class="rounded-2xl border border-cyan-200/70 dark:border-cyan-500/20 bg-white/80 dark:bg-slate-950/60 px-4 py-3 text-right">
                  <p class="text-xs uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Rata-rata proses</p>
                  <p class="mt-1 text-2xl font-semibold text-slate-900 dark:text-white">&lt; 1 hari</p>
                </div>
              </div>

              <p class="mt-5 max-w-2xl text-base leading-7 text-slate-600 dark:text-slate-400">Sistem dirancang untuk membuat proses pinjam, setujui, dan ambil terasa rapi bagi staf kantor maupun pengelola inventaris.</p>

              <div class="mt-8 grid gap-4 sm:grid-cols-3">
                <div class="rounded-3xl bg-white/80 dark:bg-slate-950/60 p-4 ring-1 ring-cyan-200/50 dark:ring-white/10">
                  <p class="text-xs uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Ajukan</p>
                  <p class="mt-2 text-sm leading-6 text-slate-700 dark:text-slate-300">Pilih alat dari katalog internal.</p>
                </div>
                <div class="rounded-3xl bg-white/80 dark:bg-slate-950/60 p-4 ring-1 ring-cyan-200/50 dark:ring-white/10">
                  <p class="text-xs uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Setujui</p>
                  <p class="mt-2 text-sm leading-6 text-slate-700 dark:text-slate-300">Admin memproses dengan cepat.</p>
                </div>
                <div class="rounded-3xl bg-white/80 dark:bg-slate-950/60 p-4 ring-1 ring-cyan-200/50 dark:ring-white/10">
                  <p class="text-xs uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Ambil</p>
                  <p class="mt-2 text-sm leading-6 text-slate-700 dark:text-slate-300">Ambil alat dan gunakan sesuai kebutuhan.</p>
                </div>
              </div>

              <ul class="mt-8 space-y-4 text-slate-700 dark:text-slate-300">
                <li class="flex items-start gap-3">
                  <span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-cyan-500 dark:bg-cyan-300"></span>
                  Ajukan alat yang dibutuhkan melalui katalog internal.
                </li>
                <li class="flex items-start gap-3">
                  <span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-cyan-500 dark:bg-cyan-300"></span>
                  Administrator memproses dan menyetujui permintaan secara cepat.
                </li>
                <li class="flex items-start gap-3">
                  <span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-cyan-500 dark:bg-cyan-300"></span>
                  Ambil alat di gudang kantor saat disetujui, lalu kembali setelah selesai.
                </li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <section id="alat" class="py-24 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="mb-14 text-center">
            <p class="text-sm uppercase tracking-[0.3em] text-cyan-700/80 dark:text-cyan-300/80">Sistem Peminjaman Internal</p>
            <h2 class="mt-3 text-4xl font-semibold tracking-tight text-slate-900 dark:text-white">Alat Kantor untuk Karyawan</h2>
            <p class="mt-4 max-w-2xl mx-auto text-base text-slate-600 dark:text-slate-400">Koleksi lengkap peralatan kantor yang tersedia untuk peminjaman internal organisasi.</p>
          </div>

          <!-- Carousel Container -->
          <div class="relative overflow-hidden">
            <!-- Carousel Track -->
            <div class="flex transition-transform duration-500 ease-in-out" :style="{ transform: `translateX(-${currentSlide * (100 / visibleSlides)}%)` }">
              <div v-for="(item, index) in [...alat, ...alat, ...alat]" :key="`${item.id}-${index}`" class="flex-shrink-0 w-full md:w-1/2 lg:w-1/3 xl:w-1/4 px-3">
                <div class="group relative rounded-[2rem] bg-cyan-50/90 dark:bg-cyan-500/10 p-6 ring-1 ring-cyan-200/70 dark:ring-cyan-500/20 shadow-2xl shadow-slate-950/20 overflow-hidden hover:ring-cyan-300/30 transition-all duration-300">
                  <!-- Image Container -->
                  <div class="relative aspect-[4/3] mb-4 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800">
                    <img :src="item.gambar" :alt="item.nama" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    <div class="absolute top-3 left-3">
                      <span class="inline-flex items-center gap-1 rounded-full bg-cyan-500/90 px-3 py-1 text-xs font-medium text-white">
                        {{ item.kategori }}
                      </span>
                    </div>
                  </div>

                  <!-- Content -->
                  <div class="space-y-3">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white group-hover:text-cyan-700 dark:group-hover:text-cyan-300 transition-colors">{{ item.nama }}</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2">{{ item.deskripsi }}</p>
                    <div class="flex justify-center pt-2">
                      <button class="w-full rounded-full bg-cyan-500 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-400 transition-colors">
                        Ajukan Peminjaman
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Navigation Buttons -->
            <button @click="prevSlide" class="absolute left-4 top-1/2 -translate-y-1/2 rounded-full bg-slate-800/80 p-3 text-white hover:bg-slate-700 transition-colors backdrop-blur-sm">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
            </button>
            <button @click="nextSlide" class="absolute right-4 top-1/2 -translate-y-1/2 rounded-full bg-slate-800/80 p-3 text-white hover:bg-slate-700 transition-colors backdrop-blur-sm">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </button>

            <!-- Dots Indicator -->
            <div class="flex justify-center mt-8 space-x-2">
              <button v-for="(item, index) in alat" :key="index" @click="goToSlide(index)" class="w-3 h-3 rounded-full transition-colors" :class="currentSlide % alat.length === index ? 'bg-cyan-500' : 'bg-slate-600 hover:bg-slate-500'"></button>
            </div>
          </div>

</div>
      </section>

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
              @click="prevTestimonialSlide"
            >
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            <button
              type="button"
              class="absolute right-4 top-1/2 -translate-y-1/2 rounded-full bg-slate-950/80 p-3 text-white shadow-lg shadow-slate-950/20 backdrop-blur-sm transition hover:bg-slate-800 dark:bg-slate-100/10 dark:hover:bg-slate-100/20"
              aria-label="Testimoni berikutnya"
              @click="nextTestimonialSlide"
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
              @click="goToTestimonialSlide(index)"
            />
          </div>
          <div class="mt-16 rounded-[2rem] border border-slate-200/70 bg-slate-100/90 dark:bg-slate-900/80 p-10 shadow-2xl shadow-slate-950/10">
            <h3 class="text-center text-2xl font-semibold text-slate-900 dark:text-white">Berikan Penilaian Anda</h3>
            <p class="mt-2 text-center text-slate-600 dark:text-slate-400">Bagikan pengalaman Anda menggunakan Trustequip</p>

            <form @submit.prevent="submitRating" class="mt-8 space-y-6 max-w-2xl mx-auto">
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
                    ★
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
                  placeholder="Bagikan pengalaman Anda menggunakan Trustequip..."
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

          <div id="hubungi" class="mt-16 rounded-[2rem] border border-slate-200/70 dark:border-white/10 bg-slate-100/90 dark:bg-slate-900/80 p-10 text-center shadow-2xl shadow-slate-950/10">
            <h3 class="text-2xl font-semibold text-slate-900 dark:text-white">Siap mencoba Trustequip?</h3>
            <p class="mt-3 text-slate-600 dark:text-slate-400">Akses penuh, login sekarang atau daftar untuk mulai pinjam.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
              <router-link to="/register" class="rounded-full bg-cyan-500 px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400">Daftar</router-link>
              <router-link to="/login" class="rounded-full border border-slate-300 dark:border-slate-600 px-6 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200 transition hover:border-cyan-500 hover:text-cyan-700 dark:hover:border-cyan-300 dark:hover:text-cyan-200">Login</router-link>
            </div>
          </div>
        </div>
      </section>

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
                  <h3 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">Trustequip</h3>
                  <p class="text-xs text-slate-500 dark:text-slate-400">Sistem Peminjaman Alat</p>
                </div>
              </div>
              <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Sistem peminjaman alat kantor internal untuk meningkatkan produktivitas organisasi.</p>
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
              <p class="text-sm text-slate-500 dark:text-slate-400">&copy; 2026 Trustequip. Semua hak dilindungi.</p>
              <div class="flex justify-start sm:justify-end gap-6">
                <a href="#" class="text-sm text-slate-500 dark:text-slate-400 transition hover:text-cyan-600 dark:hover:text-cyan-300">Privasi</a>
                <a href="#" class="text-sm text-slate-500 dark:text-slate-400 transition hover:text-cyan-600 dark:hover:text-cyan-300">Syarat</a>
                <a href="#" class="text-sm text-slate-500 dark:text-slate-400 transition hover:text-cyan-600 dark:hover:text-cyan-300">Cookies</a>
              </div>
            </div>
          </div>
        </div>
      </footer>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { apiRequest } from '../lib/api'
import { useDarkMode } from '../composables/useDarkMode'

const { isDarkMode, toggleDarkMode, initDarkMode } = useDarkMode()

const scrollY = ref(0)
const mobileMenuOpen = ref(false)
const currentSlide = ref(0)
const currentTestimonialSlide = ref(0)
const statsLoading = ref(true)
const testimonialsLoading = ref(true)
const landingStats = ref({
  tools: [],
  borrowings: [],
})
const rating = ref({
  name: '',
  email: '',
  stars: 0,
  message: ''
})
const defaultTestimonials = [
  {
    name: 'Ahmad',
    role: 'Pengguna',
    initial: 'A',
    avatarClass: 'bg-cyan-500/10 text-cyan-700 dark:text-cyan-200',
    stars: 5,
    message: 'Sangat mudah meminjam alat di sini. Prosesnya cepat, alurnya jelas, dan alatnya selalu siap ketika saya butuhkan untuk pekerjaan yang cukup padat.',
  },
  {
    name: 'Budi',
    role: 'Kontraktor',
    initial: 'B',
    avatarClass: 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-200',
    stars: 5,
    message: 'Koleksi alat lengkap, harga terjangkau, dan petugasnya responsif saat saya butuh alat tambahan di tengah pekerjaan.',
  },
  {
    name: 'Citra',
    role: 'Mahasiswa',
    initial: 'C',
    avatarClass: 'bg-sky-500/10 text-sky-700 dark:text-sky-200',
    stars: 5,
    message: 'Interface yang user-friendly. Mudah digunakan bahkan untuk pemula, dan detail informasinya cukup membantu saat saya memilih alat.',
  },
  {
    name: 'Dewi',
    role: 'Admin Kantor',
    initial: 'D',
    avatarClass: 'bg-violet-500/10 text-violet-700 dark:text-violet-200',
    stars: 5,
    message: 'Pencatatan peminjaman jadi jauh lebih rapi. Saya bisa memantau status alat dan riwayat transaksi tanpa ribet.',
  },
  {
    name: 'Eka',
    role: 'Staff Operasional',
    initial: 'E',
    avatarClass: 'bg-amber-500/10 text-amber-700 dark:text-amber-200',
    stars: 5,
    message: 'Saya suka karena status alat dan informasi peminjaman terlihat jelas. Sangat membantu saat jam kerja sedang padat.',
  },
  {
    name: 'Fajar',
    role: 'Tim Proyek',
    initial: 'F',
    avatarClass: 'bg-rose-500/10 text-rose-700 dark:text-rose-200',
    stars: 5,
    message: 'Alurnya terasa sederhana, dan respon staff cepat. Kami jadi lebih efisien saat butuh alat mendadak untuk meeting atau presentasi.',
  },
]
const testimonials = ref([...defaultTestimonials])
const alat = ref([
  {
    id: 1,
    nama: 'Proyektor HD',
    deskripsi: 'Proyektor high definition untuk presentasi dan meeting kantor',
    gambar: 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=400&h=300&fit=crop',
    kategori: 'Multimedia'
  },
  {
    id: 2,
    nama: 'Laptop Kantor',
    deskripsi: 'Laptop untuk pekerjaan administrasi dan presentasi profesional',
    gambar: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400&h=300&fit=crop',
    kategori: 'Komputer'
  },
  {
    id: 3,
    nama: 'Printer Laser',
    deskripsi: 'Printer laser multifungsi untuk mencetak dokumen dan laporan kantor',
    gambar: 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?w=400&h=300&fit=crop',
    kategori: 'Printer'
  },
  {
    id: 4,
    nama: 'Mesin Fotocopy',
    deskripsi: 'Mesin fotocopy digital untuk menggandakan dokumen dengan cepat',
    gambar: 'https://images.unsplash.com/photo-1580910051078-da8bffc4f1f0?w=400&h=300&fit=crop',
    kategori: 'Printer'
  },
  {
    id: 5,
    nama: 'Webcam HD',
    deskripsi: 'Webcam high definition untuk video conference dan meeting jarak jauh',
    gambar: 'https://images.unsplash.com/photo-1516455207990-7a41ce80f7ee?w=400&h=300&fit=crop',
    kategori: 'Multimedia'
  },
  {
    id: 6,
    nama: 'Microphone Wireless',
    deskripsi: 'Microphone nirkabel untuk meeting dan presentasi internal',
    gambar: 'https://images.unsplash.com/photo-1515207727343-0c6f08f26a0f?w=400&h=300&fit=crop',
    kategori: 'Audio'
  },
  {
    id: 7,
    nama: 'Speaker Bluetooth',
    deskripsi: 'Speaker portable untuk presentasi dan audio meeting kantor',
    gambar: 'https://images.unsplash.com/photo-1506516864270-6f9ee22c6f01?w=400&h=300&fit=crop',
    kategori: 'Audio'
  },
  {
    id: 8,
    nama: 'Whiteboard Digital',
    deskripsi: 'Whiteboard interaktif digital untuk presentasi dan kolaborasi tim',
    gambar: 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?w=400&h=300&fit=crop',
    kategori: 'Multimedia'
  }
])

const toolStatusMap = {
  tersedia: 'tersedia',
  dipinjam: 'dipinjam',
  maintenance: 'maintenance',
  '1': 'tersedia',
  '2': 'dipinjam',
  '3': 'maintenance',
}

const borrowingStatusMap = {
  pending: 'pending',
  disetujui: 'disetujui',
  ditolak: 'ditolak',
  dipinjam: 'dipinjam',
  dikembalikan: 'dikembalikan',
  selesai: 'selesai',
  '1': 'pending',
  '2': 'disetujui',
  '3': 'ditolak',
  '4': 'dipinjam',
  '5': 'dikembalikan',
  '6': 'selesai',
}

const testimonialAvatarClasses = [
  'bg-cyan-500/10 text-cyan-700 dark:text-cyan-200',
  'bg-emerald-500/10 text-emerald-700 dark:text-emerald-200',
  'bg-sky-500/10 text-sky-700 dark:text-sky-200',
  'bg-violet-500/10 text-violet-700 dark:text-violet-200',
  'bg-amber-500/10 text-amber-700 dark:text-amber-200',
  'bg-rose-500/10 text-rose-700 dark:text-rose-200',
]

function normalizeStatus(value, map = {}) {
  const key = String(value ?? '').trim().toLowerCase()
  return map[key] ?? key
}

function makeTestimonialInitial(name) {
  const value = String(name ?? '').trim()
  return value ? value.charAt(0).toUpperCase() : 'U'
}

function mapFeedbackToTestimonial(entry, index = 0) {
  const name = String(entry?.nama ?? 'Pengguna').trim() || 'Pengguna'
  const stars = Number(entry?.stars ?? 5)

  return {
    id: entry?.id ?? `${Date.now()}-${index}`,
    name,
    role: 'Pengguna',
    initial: makeTestimonialInitial(name),
    avatarClass: testimonialAvatarClasses[index % testimonialAvatarClasses.length],
    stars: Number.isFinite(stars) && stars > 0 ? Math.min(5, Math.max(1, stars)) : 5,
    message: String(entry?.pesan ?? '').trim() || 'Pengalaman penggunaan Trustequip sangat membantu.',
    createdAt: entry?.createdAt ?? null,
  }
}

function getFallbackTestimonials() {
  return defaultTestimonials.map((item, index) => ({
    ...item,
    id: index + 1,
  }))
}

function isToolAvailable(tool) {
  const status = normalizeStatus(tool?.status, toolStatusMap)
  const stok = Number(tool?.stok ?? 0)
  return status === 'tersedia' && stok > 0
}

function isBorrowingCompleted(item) {
  const status = normalizeStatus(item?.status, borrowingStatusMap)
  return status === 'dikembalikan' || status === 'selesai'
}

function formatCompactNumber(value) {
  const amount = Number(value || 0)

  if (amount >= 1000000) {
    return `${(amount / 1000000).toFixed(1)} jt`
  }

  if (amount >= 1000) {
    const scaled = amount / 1000
    return `${scaled >= 10 ? Math.round(scaled) : scaled.toFixed(1)} rb`
  }

  return `${amount}`
}

const totalToolsCount = computed(() => (Array.isArray(landingStats.value.tools) ? landingStats.value.tools.length : 0))
const availableToolsCount = computed(() => (Array.isArray(landingStats.value.tools) ? landingStats.value.tools.filter(isToolAvailable).length : 0))
const availabilityPercent = computed(() => {
  const total = totalToolsCount.value
  if (!total) return 0
  return Math.round((availableToolsCount.value / total) * 100)
})

const totalBorrowingsCount = computed(() => (Array.isArray(landingStats.value.borrowings) ? landingStats.value.borrowings.length : 0))
const completedBorrowingsCount = computed(() => (Array.isArray(landingStats.value.borrowings) ? landingStats.value.borrowings.filter(isBorrowingCompleted).length : 0))
const trustScore = computed(() => {
  const total = totalBorrowingsCount.value
  if (!total) return 0
  return Math.min(10, Math.max(0, (completedBorrowingsCount.value / total) * 10))
})
const totalBorrowingsLabel = computed(() => formatCompactNumber(totalBorrowingsCount.value))
const trustScoreLabel = computed(() => `${trustScore.value.toFixed(1)}/10`)

const shape1Style = computed(() => ({
  transform: `translateY(${scrollY.value * 0.08}px)`,
}))

const shape2Style = computed(() => ({
  transform: `translateY(${scrollY.value * 0.05}px)`,
}))

const shape3Style = computed(() => ({
  transform: `translateY(${scrollY.value * 0.12}px)`,
}))

const visibleSlides = computed(() => {
  if (typeof window !== 'undefined') {
    if (window.innerWidth >= 1280) return 4 // xl
    if (window.innerWidth >= 1024) return 3 // lg
    if (window.innerWidth >= 768) return 2 // md
    return 1 // mobile
  }
  return 1
})

const testimonialVisibleSlides = visibleSlides

async function loadTestimonials() {
  testimonialsLoading.value = true

  try {
    const data = await apiRequest('/api/feedback')
    const entries = Array.isArray(data) ? data : []
    testimonials.value = entries.length
      ? entries.map((entry, index) => mapFeedbackToTestimonial(entry, index))
      : getFallbackTestimonials()
    currentTestimonialSlide.value = 0
  } catch (error) {
    testimonials.value = getFallbackTestimonials()
    currentTestimonialSlide.value = 0
  } finally {
    testimonialsLoading.value = false
  }
}

const submitRating = async () => {
  if (rating.value.stars === 0) {
    alert('Silakan berikan rating bintang terlebih dahulu')
    return
  }

  try {
    const created = await apiRequest('/api/feedback', {
      method: 'POST',
      body: {
        nama: rating.value.name,
        email: rating.value.email,
        stars: rating.value.stars,
        pesan: rating.value.message,
      },
    })

    testimonials.value = [
      mapFeedbackToTestimonial(created, 0),
      ...testimonials.value,
    ]
    currentTestimonialSlide.value = 0
    rating.value = {
      name: '',
      email: '',
      stars: 0,
      message: '',
    }
    alert('Terima kasih! Review Anda langsung ditampilkan di testimoni.')
  } catch (error) {
    alert(error?.message || 'Terjadi kesalahan saat mengirim review.')
  }
}

const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % alat.value.length
}

const prevSlide = () => {
  currentSlide.value = currentSlide.value === 0 ? alat.value.length - 1 : currentSlide.value - 1
}

const goToSlide = (index) => {
  currentSlide.value = index
}

const nextTestimonialSlide = () => {
  if (!testimonials.value.length) return
  currentTestimonialSlide.value = (currentTestimonialSlide.value + 1) % testimonials.value.length
}

const prevTestimonialSlide = () => {
  if (!testimonials.value.length) return
  currentTestimonialSlide.value = currentTestimonialSlide.value === 0
    ? testimonials.value.length - 1
    : currentTestimonialSlide.value - 1
}

const goToTestimonialSlide = (index) => {
  if (!testimonials.value.length) return
  currentTestimonialSlide.value = index
}

const handleScroll = () => {
  scrollY.value = window.scrollY
}

async function loadLandingStats() {
  statsLoading.value = true

  try {
    const [toolsResult, borrowingsResult] = await Promise.allSettled([
      apiRequest('/api/tools'),
      apiRequest('/api/borrowings'),
    ])

    landingStats.value = {
      tools: toolsResult.status === 'fulfilled' && Array.isArray(toolsResult.value) ? toolsResult.value : [],
      borrowings: borrowingsResult.status === 'fulfilled' && Array.isArray(borrowingsResult.value) ? borrowingsResult.value : [],
    }
  } catch (error) {
    landingStats.value = {
      tools: [],
      borrowings: [],
    }
  } finally {
    statsLoading.value = false
  }
}

onMounted(() => {
  initDarkMode()
  loadLandingStats()
  loadTestimonials()
  window.addEventListener('scroll', handleScroll, { passive: true })
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
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

.summary-card {
  position: relative;
  overflow: hidden;
}

.summary-card::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at top right, rgba(34, 211, 238, 0.16), transparent 38%);
  pointer-events: none;
}

:global(.dark) .bento-card:hover,
:global([data-theme='dark']) .bento-card:hover {
  background-color: rgba(15, 23, 42, 0.98);
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
