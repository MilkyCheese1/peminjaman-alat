<template>
  <router-view />
</template>

<script setup>
import { onMounted, provide } from 'vue'
import { useDarkMode } from './composables/useDarkMode'

const { isDarkMode, initDarkMode } = useDarkMode()

// Provide dark mode state globally
provide('darkMode', { isDarkMode })

onMounted(() => {
  initDarkMode()
})
</script>

<style>
/* Global app styles can go here */
</style>
