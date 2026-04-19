<template>
  <nav class="sticky top-0 z-10 bg-slate-900 shadow-sm border-b border-white/10 px-6 py-4">
    <div class="flex justify-between items-center">
      <div class="flex items-center space-x-4">
        <!-- Search bar or other navbar content can be added here -->
        <span class="text-slate-400">Navbar - Content akan ditambahkan nanti</span>
      </div>
      <div class="flex items-center space-x-4">
        <!-- User menu or notifications can be added here -->
        <div class="w-8 h-8 bg-cyan-400/15 border border-cyan-300/25 rounded-full flex items-center justify-center">
          <svg class="w-4 h-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navbar'
}
</script>

<style scoped>
/* Additional styles if needed */
</style>