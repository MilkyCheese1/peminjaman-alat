<template>
  <aside class="hidden lg:flex sticky top-0 h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 w-64 p-4 border-r border-slate-300 dark:border-white/10 flex-col overflow-y-auto">
    <div class="mb-8">
      <h2 class="text-xl font-bold text-slate-900 dark:text-white">Admin Panel</h2>
    </div>

    <nav class="space-y-2 flex-1">
      <router-link
        v-for="item in menuItems"
        :key="item.to"
        :to="item.to"
        class="block rounded-xl px-4 py-3 whitespace-nowrap transition duration-300"
        :class="isActive(item.to)
          ? 'bg-cyan-100 text-cyan-700 dark:bg-cyan-500/15 dark:text-cyan-200'
          : 'hover:bg-slate-200 hover:text-cyan-600 dark:hover:bg-slate-800/50 dark:hover:text-cyan-300'"
      >
        <svg class="mr-2 inline h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"></path>
        </svg>
        {{ item.label }}
      </router-link>
    </nav>

    <button
      type="button"
      class="block whitespace-nowrap py-2 px-4 rounded bg-red-100 dark:bg-red-600/10 hover:bg-red-200 dark:hover:bg-red-600/20 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition duration-300 border border-red-300 dark:border-red-600/30 mt-auto text-left"
      @click="handleLogout"
    >
      <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a2 2 0 013 3v1"></path>
      </svg>
      Logout
    </button>
  </aside>

  <div v-if="isSidebarOpen" class="lg:hidden fixed inset-0 z-50">
    <button type="button" class="absolute inset-0 bg-slate-950/70" aria-label="Tutup menu" @click="closeSidebar" />
    <div class="relative h-full w-80 max-w-[85vw] bg-slate-50 dark:bg-slate-950 border-r border-slate-300 dark:border-white/10 p-4 overflow-y-auto">
      <div class="mb-6 flex items-center justify-between gap-3">
        <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">Menu</h2>
        <button
          type="button"
          class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-700 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
          aria-label="Tutup menu"
          @click="closeSidebar"
        >
          ✕
        </button>
      </div>

      <nav class="space-y-2">
        <router-link
          v-for="item in menuItems"
          :key="item.to"
          :to="item.to"
          class="block rounded-2xl px-4 py-3 whitespace-nowrap transition duration-300"
          :class="isActive(item.to)
            ? 'bg-cyan-100 text-cyan-700 dark:bg-cyan-500/15 dark:text-cyan-200'
            : 'hover:bg-slate-200 hover:text-cyan-600 dark:hover:bg-slate-800/50 dark:hover:text-cyan-300'"
          @click="closeSidebar"
        >
          <svg class="mr-2 inline h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"></path>
          </svg>
          {{ item.label }}
        </router-link>
      </nav>

      <button
        type="button"
        class="mt-6 block w-full whitespace-nowrap py-3 px-4 rounded-2xl bg-red-100 dark:bg-red-600/10 hover:bg-red-200 dark:hover:bg-red-600/20 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition duration-300 border border-red-300 dark:border-red-600/30 text-left"
        @click="handleLogout"
      >
        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a2 2 0 013 3v1"></path>
        </svg>
        Logout
      </button>
    </div>
  </div>
</template>

<script>
import { useSidebarDrawer } from '../../composables/useSidebarDrawer'
import { clearAuthSession } from '../../auth/session'

export default {
  name: 'SidebarAdmin',
  data() {
    const { isSidebarOpen, closeSidebar } = useSidebarDrawer()

    return {
      isSidebarOpen,
      closeSidebar,
      menuItems: [
        {
          label: 'Statistik',
          to: '/statistik-admin',
          icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        },
        {
          label: 'Notifikasi',
          to: '/notifikasi-admin',
          icon: 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
        },
        {
          label: 'Log Aktivitas',
          to: '/log-aktivitas-admin',
          icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        },
        {
          label: 'User',
          to: '/management-user',
          icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        },
        {
          label: 'Alat',
          to: '/management-alat',
          icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
        },
        {
          label: 'Kategori',
          to: '/management-kategori',
          icon: 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
        },
        {
          label: 'Akun',
          to: '/akun-admin',
          icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
        },
      ],
    }
  },
  methods: {
    isActive(path) {
      return this.$route.path === path
    },
    handleLogout() {
      clearAuthSession()
      this.closeSidebar?.()
      this.$router.replace('/login')
    },
  },
}
</script>
