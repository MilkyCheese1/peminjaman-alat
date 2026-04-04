<template>
  <transition name="fade" @enter="onEnter" @leave="onLeave">
    <div v-if="isVisible" class="loading-screen">
      <div class="loading-container">
        <!-- Logo Animation -->
        <div class="loading-logo" :style="{ transform: `scale(${logoScale})`, opacity: logoOpacity }">
          🎓
        </div>

        <!-- App Title -->
        <h1 class="loading-title" :style="{ opacity: titleOpacity }">
          TrustEquip
        </h1>

        <!-- Loading Text -->
        <p class="loading-text" :style="{ opacity: loadingTextOpacity }">
          {{ loadingMessage }}
        </p>

        <!-- Subtitle -->
        <p class="loading-subtitle" :style="{ opacity: subtitleOpacity }">
          Ini akan memakan waktu beberapa saat...
        </p>

        <!-- Progress Indicator -->
        <div class="loading-progress">
          <span class="progress-dot" v-for="i in 3" :key="i" :style="{ 'animation-delay': `${i * 0.15}s` }"></span>
        </div>
      </div>

      <!-- Background Animation -->
      <div class="loading-background">
        <div class="gradient-blob blob1"></div>
        <div class="gradient-blob blob2"></div>
        <div class="gradient-blob blob3"></div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'

// Props
const props = defineProps({
  isLoading: {
    type: Boolean,
    default: false
  },
  message: {
    type: String,
    default: 'Memuat...'
  }
})

// State
const isVisible = ref(props.isLoading)
const loadingMessage = ref(props.message)
const logoScale = ref(0.5)
const logoOpacity = ref(0)
const titleOpacity = ref(0)
const loadingTextOpacity = ref(0)
const subtitleOpacity = ref(0)

// Methods
const onEnter = (el) => {
  startLoadingAnimation()
}

const onLeave = (el) => {
  // Reset animations
  logoScale.value = 0.5
  logoOpacity.value = 0
  titleOpacity.value = 0
  loadingTextOpacity.value = 0
  subtitleOpacity.value = 0
}

const startLoadingAnimation = () => {
  // Reset before animation
  logoScale.value = 0.5
  logoOpacity.value = 0
  titleOpacity.value = 0
  loadingTextOpacity.value = 0
  subtitleOpacity.value = 0

  // Logo animation
  setTimeout(() => {
    logoOpacity.value = 1
    logoScale.value = 1
  }, 100)

  // Title animation
  setTimeout(() => {
    titleOpacity.value = 1
  }, 300)

  // Loading text animation
  setTimeout(() => {
    loadingTextOpacity.value = 1
  }, 400)

  // Subtitle animation
  setTimeout(() => {
    subtitleOpacity.value = 1
  }, 500)
}

// Watchers
watch(() => props.isLoading, (newVal) => {
  isVisible.value = newVal
  if (newVal) {
    // Trigger animation after visibility changes
    setTimeout(() => {
      startLoadingAnimation()
    }, 50)
  }
}, {
  immediate: true
})

watch(() => props.message, (newVal) => {
  loadingMessage.value = newVal
}, {
  immediate: true
})

// Lifecycle
onMounted(() => {
  if (isVisible.value) {
    startLoadingAnimation()
  }
})
</script>

<style scoped>
.loading-screen {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, #0B7285 0%, #05454E 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 5000;
  overflow: hidden;
}

:root[data-theme="dark"] .loading-screen {
  background: linear-gradient(135deg, #05454E 0%, #0a2f37 100%);
}

.loading-background {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 0;
}

.gradient-blob {
  position: absolute;
  border-radius: 50%;
  mix-blend-mode: multiply;
  opacity: 0.3;
  filter: blur(40px);
}

.blob1 {
  width: 300px;
  height: 300px;
  background: rgba(255, 159, 28, 0.3);
  top: -50px;
  left: -50px;
  animation: blobFloat1 8s ease-in-out infinite;
}

.blob2 {
  width: 250px;
  height: 250px;
  background: rgba(11, 114, 133, 0.2);
  bottom: -50px;
  right: -50px;
  animation: blobFloat2 10s ease-in-out infinite;
}

.blob3 {
  width: 200px;
  height: 200px;
  background: rgba(255, 159, 28, 0.2);
  bottom: 100px;
  left: 50px;
  animation: blobFloat3 12s ease-in-out infinite;
}

@keyframes blobFloat1 {
  0%, 100% { transform: translate(0, 0); }
  50% { transform: translate(30px, -30px); }
}

@keyframes blobFloat2 {
  0%, 100% { transform: translate(0, 0); }
  50% { transform: translate(-30px, 30px); }
}

@keyframes blobFloat3 {
  0%, 100% { transform: translate(0, 0); }
  50% { transform: translate(20px, 20px); }
}

.loading-container {
  text-align: center;
  position: relative;
  z-index: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.loading-logo {
  font-size: 4rem;
  margin-bottom: 20px;
  transition: all 0.3s ease;
}

.loading-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: white;
  margin-bottom: 10px;
  transition: opacity 0.3s ease;
}

.loading-text {
  font-size: 1.1rem;
  color: rgba(255, 255, 255, 0.95);
  margin: 15px 0;
  font-weight: 500;
  transition: opacity 0.3s ease;
}

.loading-subtitle {
  font-size: 0.95rem;
  color: rgba(255, 255, 255, 0.7);
  margin-bottom: 30px;
  transition: opacity 0.3s ease;
}

.loading-progress {
  display: flex;
  justify-content: center;
  gap: 8px;
  margin-top: 20px;
}

.progress-dot {
  width: 10px;
  height: 10px;
  background: rgba(255, 159, 28, 0.8);
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
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
