<template>
  <!-- Splash Screen -->
  <SplashScreen />

  <!-- Loading Screen -->
  <LoadingScreen :is-loading="isLoading" :message="loadingMessage" />

  <!-- Main App -->
  <RouterView />
</template>

<script setup>
import { ref } from 'vue'
import { RouterView, useRouter } from 'vue-router'
import SplashScreen from './components/SplashScreen.vue'
import LoadingScreen from './components/LoadingScreen.vue'

// State
const isLoading = ref(false)
const loadingMessage = ref('Memproses...')

// Router
const router = useRouter()

// Global loading handler for route transitions
router.beforeEach((to, from, next) => {
  // Show loading screen on navigation
  if (to.name !== from.name) {
    isLoading.value = true
    loadingMessage.value = 'Memuat halaman...'
  }
  next()
})

router.afterEach(() => {
  // Hide loading screen after navigation completes
  setTimeout(() => {
    isLoading.value = false
  }, 300)
})
</script>


