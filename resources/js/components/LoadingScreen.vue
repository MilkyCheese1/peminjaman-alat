<template>
  <transition name="loading-fade">
    <div v-if="isVisible" class="loading-screen">
      <div class="loading-container">
        <!-- Animated Loader -->
        <div class="loader-spinner"></div>

        <!-- Loading Text -->
        <p class="loading-text">{{ loadingMessage }}</p>

        <!-- Progress Indicator -->
        <div class="loading-progress">
          <span class="progress-dot" v-for="i in 3" :key="i" :style="{ 'animation-delay': `${i * 0.15}s` }"></span>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, watch } from 'vue'

// Props
const props = defineProps({
  isLoading: {
    type: Boolean,
    default: false
  },
  message: {
    type: String,
    default: 'Memproses...'
  }
})

// State
const isVisible = ref(props.isLoading)
const loadingMessage = ref(props.message)

// Watchers
watch(() => props.isLoading, (newVal) => {
  isVisible.value = newVal
}, {
  immediate: true
})

watch(() => props.message, (newVal) => {
  loadingMessage.value = newVal
}, {
  immediate: true
})
</script>

<style scoped>
.loading-screen {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 5000;
  backdrop-filter: blur(4px);
}

.loading-container {
  text-align: center;
  background: white;
  padding: 40px;
  border-radius: 12px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
  animation: slideUp 0.4s ease;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.loader-spinner {
  width: 50px;
  height: 50px;
  margin: 0 auto 20px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #0B7285;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.loading-text {
  font-size: 1rem;
  color: #333;
  margin: 15px 0;
  font-weight: 500;
}

.loading-progress {
  display: flex;
  justify-content: center;
  gap: 8px;
  margin-top: 15px;
}

.progress-dot {
  width: 8px;
  height: 8px;
  background: #0B7285;
  border-radius: 50%;
  animation: dotBounce 1.4s infinite;
}

@keyframes dotBounce {
  0%, 80%, 100% {
    transform: scale(0.8);
    opacity: 0.5;
  }
  40% {
    transform: scale(1);
    opacity: 1;
  }
}

/* Transition animations */
.loading-fade-enter-active,
.loading-fade-leave-active {
  transition: opacity 0.3s ease;
}

.loading-fade-enter-from,
.loading-fade-leave-to {
  opacity: 0;
}
</style>
