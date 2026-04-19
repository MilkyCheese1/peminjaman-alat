<template>
  <div class="sticky top-0 h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 w-64 p-4 border-r border-slate-300 dark:border-white/10 flex flex-col overflow-y-auto">
    <div class="mb-8">
      <h2 class="text-xl font-bold text-slate-900 dark:text-white">Staff Panel</h2>
    </div>

    <nav class="space-y-2 flex-1">
      <router-link to="/statistik-staff" class="block py-2 px-4 rounded hover:bg-slate-200 dark:hover:bg-slate-800/50 hover:text-cyan-600 dark:hover:text-cyan-300 transition duration-300">
        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
        Statistik
      </router-link>
      <a href="#" class="block py-2 px-4 rounded hover:bg-green-200 dark:hover:bg-green-700 transition duration-300">
        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
        </svg>
        Alat
      </a>
      <a href="#" class="block py-2 px-4 rounded hover:bg-green-200 dark:hover:bg-green-700 transition duration-300">
        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        Peminjaman
      </a>
      <router-link to="/akun-staff" class="block py-2 px-4 rounded hover:bg-slate-200 dark:hover:bg-slate-800/50 hover:text-cyan-600 dark:hover:text-cyan-300 transition duration-300">
        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        Akun
      </router-link>
    </nav>

    <a href="#" class="block py-2 px-4 rounded bg-red-100 dark:bg-red-600/10 hover:bg-red-200 dark:hover:bg-red-600/20 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition duration-300 border border-red-300 dark:border-red-600/30 mt-auto">
      <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
      </svg>
      Logout
    </a>
  </div>
</template>

<script>
export default {
  name: 'SidebarStaff',
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
