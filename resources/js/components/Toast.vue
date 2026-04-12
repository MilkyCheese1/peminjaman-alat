<template>
  <div v-if="isVisible" class="toast" :class="[type, position]">
    <div class="toast-content">
      <span class="toast-icon">{{ icon }}</span>
      <span class="toast-message">{{ message }}</span>
    </div>
    <button class="toast-close" @click="close" aria-label="Close notification">
      ✕
    </button>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue'

const props = defineProps({
  message: {
    type: String,
    default: ''
  },
  type: {
    type: String,
    enum: ['success', 'error', 'warning', 'info'],
    default: 'info'
  },
  duration: {
    type: Number,
    default: 3000
  },
  position: {
    type: String,
    enum: ['top-left', 'top-right', 'top-center', 'bottom-left', 'bottom-right', 'bottom-center'],
    default: 'top-right'
  }
})

const isVisible = ref(!!props.message)
let timeout = null

const icon = computed(() => {
  const icons = {
    success: '✅',
    error: '❌',
    warning: '⚠️',
    info: 'ℹ️'
  }
  return icons[props.type] || icons.info
})

const close = () => {
  isVisible.value = false
  if (timeout) clearTimeout(timeout)
}

watch(
  () => props.message,
  (newMessage) => {
    if (newMessage) {
      isVisible.value = true
      if (timeout) clearTimeout(timeout)
      if (props.duration > 0) {
        timeout = setTimeout(() => {
          isVisible.value = false
        }, props.duration)
      }
    }
  }
)
</script>

<style scoped>
.toast {
  position: fixed;
  padding: 12px 16px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  font-size: 14px;
  font-weight: 500;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  animation: slideIn 0.3s ease-out;
  z-index: 9999;
  min-width: 300px;
  max-width: 500px;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(100%);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* Position Classes */
.toast.top-right {
  top: 20px;
  right: 20px;
}

.toast.top-left {
  top: 20px;
  left: 20px;
}

.toast.top-center {
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
}

.toast.bottom-right {
  bottom: 20px;
  right: 20px;
}

.toast.bottom-left {
  bottom: 20px;
  left: 20px;
}

.toast.bottom-center {
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
}

/* Type Classes */
.toast.success {
  background: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.toast.error {
  background: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

.toast.warning {
  background: #fff3cd;
  color: #856404;
  border: 1px solid #ffeaa7;
}

.toast.info {
  background: #d1ecf1;
  color: #0c5460;
  border: 1px solid #bee5eb;
}

.toast-content {
  display: flex;
  align-items: center;
  gap: 8px;
  flex: 1;
}

.toast-icon {
  font-size: 16px;
  flex-shrink: 0;
}

.toast-message {
  word-break: break-word;
}

.toast-close {
  background: none;
  border: none;
  color: inherit;
  cursor: pointer;
  padding: 0;
  font-size: 18px;
  flex-shrink: 0;
  opacity: 0.7;
  transition: opacity 0.2s;
}

.toast-close:hover {
  opacity: 1;
}

/* Dark mode support */
:root[data-theme='dark'] .toast.success {
  background: #1e4620;
  color: #a6e3a1;
  border-color: #40916c;
}

:root[data-theme='dark'] .toast.error {
  background: #5a1e1e;
  color: #fd7e7e;
  border-color: #a13a3a;
}

:root[data-theme='dark'] .toast.warning {
  background: #5a4a1e;
  color: #ffd700;
  border-color: #a67a1e;
}

:root[data-theme='dark'] .toast.info {
  background: #1e4a5a;
  color: #7ef4f8;
  border-color: #3a7a8a;
}
</style>
