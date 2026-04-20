<template>
  <main class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 flex items-center justify-center px-4 py-8">
    <div class="app-card app-card--cyan w-full max-w-3xl p-10">
      <h1 class="text-4xl font-semibold mb-4">Laravel + Vue 3</h1>
      <p class="text-slate-600 dark:text-slate-300 mb-6">Framework baru siap digunakan dengan Vue di frontend dan PHP Laravel di backend.</p>
      <div class="space-y-4 text-slate-700 dark:text-slate-200">
        <p>Gunakan folder <code class="rounded bg-slate-100 px-2 py-1 dark:bg-slate-800">resources/js</code> untuk komponen Vue.</p>
        <p>Jalankan <code class="rounded bg-slate-100 px-2 py-1 dark:bg-slate-800">npm run dev</code> untuk pengembangan.</p>
      </div>
    </div>
  </main>
</template>

<script>
export default {
  name: 'ExampleComponent',
};
</script>

<style scoped>
code {
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;
}
</style>
