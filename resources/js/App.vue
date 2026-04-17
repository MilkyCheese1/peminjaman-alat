<template>
  <!-- Splash Screen -->
  <SplashScreen />

  <!-- Loading Screen -->
  <LoadingScreen :is-loading="isLoading" :message="loadingMessage" />

  <!-- Main App with conditional layout -->
  <template v-if="showMainLayout">
    <MainLayout>
      <RouterView />
    </MainLayout>
  </template>
  <template v-else>
    <RouterView />
  </template>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue'
import { RouterView, useRouter } from 'vue-router'
import SplashScreen from './components/SplashScreen.vue'
import LoadingScreen from './components/LoadingScreen.vue'
import MainLayout from './layouts/MainLayout.vue'
import { useSessionRestoration } from './composables/useSessionRestoration.js'

// State
const isLoading = ref(false)
const loadingMessage = ref('Memuat...')

// Router
const router = useRouter()

// Session restoration
const { initializeSession, getLastRoute, restoreScrollPosition } = useSessionRestoration()

// Determine if MainLayout should be shown
const showMainLayout = computed(() => {
  const layoutRoutes = ['/dashboard', '/equipment', '/reports', '/settings', '/profile', '/chat']
  return layoutRoutes.includes(router.currentRoute.value.path)
})

// Initialize session restoration on app mount
onMounted(() => {
  initializeSession()
  // Restore scroll position on page load
  setTimeout(restoreScrollPosition, 100)
})

// Global loading handler for route transitions
router.beforeEach((to, from) => {
  // Show loading screen on navigation (except on initial load)
  if (from.name !== undefined && to.name !== from.name) {
    isLoading.value = true
    loadingMessage.value = 'Memuat halaman...'
  }
  // Explicitly allow navigation
  return true
})

router.afterEach((to, from, failure) => {
  // Hide loading screen after navigation completes
  if (failure) {
    isLoading.value = false
    loadingMessage.value = 'Gagal memuat halaman'
    return
  }
  setTimeout(() => {
    isLoading.value = false
  }, 300)
})
</script>

<style>
/* Global minimalist scrollbar styling */
html,
body,
::-webkit-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: #d1d5db transparent;
}

::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

::-webkit-scrollbar-track {
  background: transparent;
}

::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 3px;
  transition: background-color 0.3s ease;
}

::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}

/* Firefox scrollbar */
* {
  scrollbar-width: thin;
  scrollbar-color: #d1d5db transparent;
}

*::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

*::-webkit-scrollbar-track {
  background: transparent;
}

*::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 3px;
}

*::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}
</style>


