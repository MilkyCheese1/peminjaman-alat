<template>
  <nav class="sticky top-0 z-10 bg-slate-900 dark:bg-slate-100 shadow-sm border-b border-white/10 dark:border-slate-300 px-6 py-4">
    <div class="flex justify-between items-center">
      <div class="flex items-center space-x-4">
        <!-- Search bar or other navbar content can be added here -->
        <span class="text-slate-400 dark:text-slate-600">Navbar - Content akan ditambahkan nanti</span>
      </div>
      <div class="flex items-center space-x-4">
        <!-- Dark/Light mode toggle -->
        <button @click="toggleDarkMode" class="w-8 h-8 bg-cyan-400/15 dark:bg-slate-200 border border-cyan-300/25 dark:border-slate-300 rounded-full flex items-center justify-center hover:bg-cyan-400/25 dark:hover:bg-slate-300 transition-colors">
          <svg v-if="isDarkMode" class="w-4 h-4 text-cyan-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
          </svg>
          <svg v-else class="w-4 h-4 text-cyan-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
          </svg>
        </button>
        <!-- User menu or notifications can be added here -->
        <div class="w-8 h-8 bg-cyan-400/15 dark:bg-slate-200 border border-cyan-300/25 dark:border-slate-300 rounded-full flex items-center justify-center">
          <svg class="w-4 h-4 text-cyan-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const isDarkMode = ref(true)

const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value
  if (isDarkMode.value) {
    document.documentElement.classList.add('dark')
    localStorage.setItem('darkMode', 'true')
  } else {
    document.documentElement.classList.remove('dark')
    localStorage.setItem('darkMode', 'false')
  }
}

onMounted(() => {
  const savedDarkMode = localStorage.getItem('darkMode')
  if (savedDarkMode === 'false') {
    isDarkMode.value = false
    document.documentElement.classList.remove('dark')
  } else {
    isDarkMode.value = true
    document.documentElement.classList.add('dark')
  }
})
</script>

<style scoped>
/* Additional styles if needed */
</style>