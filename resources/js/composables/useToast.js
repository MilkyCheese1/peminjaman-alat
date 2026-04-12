import { ref } from 'vue'

const toastMessage = ref('')
const toastType = ref('info')
const toastPosition = ref('top-right')
const toastDuration = ref(3000)

export function useToast() {
  const show = (message, type = 'info', duration = 3000) => {
    toastMessage.value = message
    toastType.value = type
    toastDuration.value = duration
  }

  const success = (message, duration = 3000) => {
    show(message, 'success', duration)
  }

  const error = (message, duration = 4000) => {
    show(message, 'error', duration)
  }

  const warning = (message, duration = 3500) => {
    show(message, 'warning', duration)
  }

  const info = (message, duration = 3000) => {
    show(message, 'info', duration)
  }

  const setPosition = (position) => {
    toastPosition.value = position
  }

  return {
    show,
    success,
    error,
    warning,
    info,
    setPosition,
    toastMessage,
    toastType,
    toastPosition,
    toastDuration
  }
}
