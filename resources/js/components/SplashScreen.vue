<template>
  <transition name="fade" @enter="onEnter" @leave="onLeave">
    <div v-if="isVisible" class="splash-screen">
      <div class="splash-container">
        <!-- Logo Animation -->
        <div class="splash-logo" :style="{ transform: `scale(${logoScale})`, opacity: logoOpacity }">
          🎓
        </div>

        <!-- App Title -->
        <h1 class="splash-title" :style="{ opacity: titleOpacity }">
          TrustEquip
        </h1>

        <!-- Subtitle -->
        <p class="splash-subtitle" :style="{ opacity: subtitleOpacity }">
          Platform Penyewaan Alat Terpercaya
        </p>

        <!-- Loading Progress -->
        <div class="splash-progress">
          <div class="progress-bar" :style="{ width: progress + '%' }"></div>
        </div>

        <!-- Loading Text -->
        <p class="splash-loading-text" :style="{ opacity: loadingTextOpacity }">
          {{ loadingText }}
        </p>
      </div>

      <!-- Background Animation -->
      <div class="splash-background">
        <div class="gradient-blob blob1"></div>
        <div class="gradient-blob blob2"></div>
        <div class="gradient-blob blob3"></div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

// State
const isVisible = ref(true)
const progress = ref(0)
const logoScale = ref(0.5)
const logoOpacity = ref(0)
const titleOpacity = ref(0)
const subtitleOpacity = ref(0)
const loadingTextOpacity = ref(0)

const loadingText = ref('Memuat...')
const loadingTexts = [
  'Menyiapkan platform...',
  'Mengoptimalkan layanan...',
  'Membuka pintu kesempatan...',
  'Siap untuk memulai...'
]

// Methods
const onEnter = (el) => {
  // Trigger animations on enter
}

const onLeave = (el) => {
  // Cleanup on leave
}

const startAnimation = () => {
  // Logo animation
  setTimeout(() => {
    logoOpacity.value = 1
    logoScale.value = 1
  }, 100)

  // Title animation
  setTimeout(() => {
    titleOpacity.value = 1
  }, 300)

  // Subtitle animation
  setTimeout(() => {
    subtitleOpacity.value = 1
  }, 500)

  // Loading text animation
  setTimeout(() => {
    loadingTextOpacity.value = 1
  }, 700)

  // Progress animation
  let currentProgress = 0
  const progressInterval = setInterval(() => {
    currentProgress += Math.random() * 30
    if (currentProgress > 100) currentProgress = 100
    progress.value = currentProgress

    if (currentProgress >= 100) {
      clearInterval(progressInterval)
      // Finish splash screen
      setTimeout(() => {
        logoOpacity.value = 0
        titleOpacity.value = 0
        subtitleOpacity.value = 0
        loadingTextOpacity.value = 0
        setTimeout(() => {
          isVisible.value = false
        }, 300)
      }, 500)
    }

    // Change loading text
    const textIndex = Math.floor((currentProgress / 100) * loadingTexts.length)
    if (textIndex < loadingTexts.length) {
      loadingText.value = loadingTexts[textIndex]
    }
  }, 300)
}

// Lifecycle
onMounted(() => {
  startAnimation()
})
</script>

<style scoped>
.splash-screen {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, #0B7285 0%, #05454E 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  overflow: hidden;
}

.splash-background {
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

.splash-container {
  position: relative;
  z-index: 10;
  text-align: center;
  color: white;
  max-width: 400px;
  padding: 40px;
}

.splash-logo {
  font-size: 80px;
  margin-bottom: 30px;
  display: inline-block;
  transition: all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.splash-title {
  font-size: 3rem;
  font-weight: 700;
  margin: 0 0 10px 0;
  transition: opacity 0.8s ease;
  letter-spacing: 2px;
}

.splash-subtitle {
  font-size: 1.1rem;
  opacity: 0.9;
  margin: 0 0 40px 0;
  transition: opacity 0.8s ease;
  letter-spacing: 0.5px;
}

.splash-progress {
  width: 100%;
  height: 6px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 3px;
  margin: 30px 0;
  overflow: hidden;
  backdrop-filter: blur(10px);
}

.progress-bar {
  height: 100%;
  background: linear-gradient(90deg, #FF9F1C, #FFB547);
  border-radius: 3px;
  transition: width 0.3s ease;
  box-shadow: 0 0 10px rgba(255, 159, 28, 0.6);
}

.splash-loading-text {
  font-size: 0.95rem;
  margin: 0;
  transition: opacity 0.6s ease;
  letter-spacing: 0.3px;
  animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 0.7; }
  50% { opacity: 1; }
}

/* Transition animations */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
