import { ref, watch } from 'vue'

const isDarkMode = ref(false)

// Initialize dark mode from localStorage
const initDarkMode = () => {
  const savedDarkMode = localStorage.getItem('darkMode')
  const shouldBeDark = savedDarkMode === 'true'
  isDarkMode.value = shouldBeDark
  applyDarkMode(shouldBeDark)
}

// Apply dark mode by updating DOM
const applyDarkMode = (isDark) => {
  if (isDark) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
  localStorage.setItem('darkMode', isDark ? 'true' : 'false')
}

// Toggle dark mode
const toggleDarkMode = () => {
  const newValue = !isDarkMode.value
  isDarkMode.value = newValue
  applyDarkMode(newValue)
  console.log('Dark mode toggled:', newValue)
}

// Watch for changes
watch(isDarkMode, (newValue) => {
  applyDarkMode(newValue)
})

export const useDarkMode = () => {
  return {
    isDarkMode,
    initDarkMode,
    toggleDarkMode
  }
}

