<template>
  <nav class="sticky top-0 z-10 bg-white dark:bg-slate-900 shadow-sm border-b border-slate-200 dark:border-slate-700 px-6 py-4">
    <div class="flex justify-between items-center">
      <div class="flex items-center space-x-4">
        <!-- Search bar or other navbar content can be added here -->
      </div>
      <div class="flex items-center space-x-4">
        <!-- Dark/Light mode toggle -->
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
        <!-- User menu or notifications can be added here -->
        <div class="w-8 h-8 bg-cyan-400/15 dark:bg-slate-800 border border-cyan-300/25 dark:border-slate-600 rounded-full flex items-center justify-center">
          <svg class="w-4 h-4 text-cyan-500 dark:text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { onMounted } from 'vue'
import { useDarkMode } from '../../composables/useDarkMode'

const { isDarkMode, toggleDarkMode, initDarkMode } = useDarkMode()

onMounted(() => {
  initDarkMode()
})
</script>

<style scoped>
/* Additional styles if needed */
</style>
