<script setup lang="ts">
import { Heart } from 'lucide-vue-next'
</script>

<template>
  <footer class="bg-card border-t py-6 px-4 md:px-8 mt-auto">
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
      <div class="flex items-center gap-1 text-sm text-muted-foreground">
        <span>© 2026, Equipment Rental System</span>
        <Heart :size="16" class="text-red-500 fill-red-500" />
      </div>

      <div class="flex items-center gap-6 text-sm">
        <a 
          href="#!" 
          rel="noopener noreferrer"
          class="text-muted-foreground hover:text-primary transition-colors"
        >
          About Us
        </a>
        <a 
          href="#!" 
          rel="noopener noreferrer"
          class="text-muted-foreground hover:text-primary transition-colors"
        >
          Contact
        </a>
        <a 
          href="/terms"
          rel="noopener noreferrer"
          class="text-muted-foreground hover:text-primary transition-colors"
        >
          Terms
        </a>
      </div>
    </div>
  </footer>
</template>
