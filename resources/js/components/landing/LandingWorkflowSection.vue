<template>
  <section id="alur" class="relative overflow-hidden py-24 bg-slate-50/95 dark:bg-slate-950/95">
    <div class="absolute right-0 top-0 h-48 w-48 rounded-full bg-cyan-500/10 blur-3xl"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-14 text-center">
        <p class="text-sm uppercase tracking-[0.3em] text-cyan-700/80 dark:text-cyan-300/80">Alur peminjaman</p>
        <h2 class="mt-3 text-4xl font-semibold tracking-tight text-slate-900 dark:text-white">Manajemen Peminjaman Peralatan</h2>
        <p class="mt-4 max-w-2xl mx-auto text-base text-slate-600 dark:text-slate-400">Sistem terintegrasi untuk mengelola peminjaman peralatan dengan efisien dan transparan.</p>
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
              <p class="mt-2 text-sm leading-6 text-slate-700 dark:text-slate-300">Pilih alat dari katalog yang tersedia.</p>
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
              Ajukan alat yang dibutuhkan melalui katalog yang tersedia.
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
</template>

<style scoped>
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
</style>
