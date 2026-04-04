<template>
  <!-- Splash Screen -->
  <SplashScreen />

  <!-- Loading Screen -->
  <LoadingScreen :is-loading="isLoading" :message="loadingMessage" />

  <!-- Main App -->
  <RouterView />
</template>

<script setup>
import { ref, watch } from 'vue'
import { RouterView, useRouter } from 'vue-router'
import SplashScreen from './components/SplashScreen.vue'
import LoadingScreen from './components/LoadingScreen.vue'

// State
const isLoading = ref(false)
const loadingMessage = ref('Memuat...')

// Router
const router = useRouter()

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
    loadingMessage.value = 'error loading page'
    return
  }
  setTimeout(() => {
    isLoading.value = false
  }, 300)
})
</script>


