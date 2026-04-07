import { ref, watch, onMounted, onUnmounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'

export function useSessionRestoration() {
  const router = useRouter()
  const route = useRoute()
  const scrollPositions = ref({})

  const SESSION_KEY = 'app_session_state'
  const SCROLL_KEY = 'app_scroll_positions'

  /**
   * Save component state to sessionStorage
   */
  function saveState(key, value) {
    try {
      const state = JSON.parse(sessionStorage.getItem(SESSION_KEY) || '{}')
      state[key] = value
      sessionStorage.setItem(SESSION_KEY, JSON.stringify(state))
    } catch (err) {
      console.warn('Failed to save session state:', err)
    }
  }

  /**
   * Restore component state from sessionStorage
   */
  function getState(key, defaultValue = null) {
    try {
      const state = JSON.parse(sessionStorage.getItem(SESSION_KEY) || '{}')
      return state[key] !== undefined ? state[key] : defaultValue
    } catch (err) {
      console.warn('Failed to restore session state:', err)
      return defaultValue
    }
  }

  /**
   * Save scroll position for current route
   */
  function saveScrollPosition() {
    const routePath = route.path
    const scrollTop = window.scrollY || window.pageYOffset || document.documentElement.scrollTop
    
    try {
      const positions = JSON.parse(sessionStorage.getItem(SCROLL_KEY) || '{}')
      positions[routePath] = scrollTop
      sessionStorage.setItem(SCROLL_KEY, JSON.stringify(positions))
    } catch (err) {
      console.warn('Failed to save scroll position:', err)
    }
  }

  /**
   * Restore scroll position for current route
   */
  function restoreScrollPosition() {
    try {
      const positions = JSON.parse(sessionStorage.getItem(SCROLL_KEY) || '{}')
      const scrollTop = positions[route.path] || 0
      
      // Use nextTick to ensure DOM is ready
      setTimeout(() => {
        window.scrollTo(0, scrollTop)
      }, 0)
    } catch (err) {
      console.warn('Failed to restore scroll position:', err)
    }
  }

  /**
   * Clear all session data (used on logout)
   */
  function clearSession() {
    try {
      sessionStorage.removeItem(SESSION_KEY)
      sessionStorage.removeItem(SCROLL_KEY)
    } catch (err) {
      console.warn('Failed to clear session:', err)
    }
  }

  /**
   * Save last visited route
   */
  function saveLastRoute() {
    try {
      sessionStorage.setItem('last_route', route.path)
      sessionStorage.setItem('last_route_name', route.name || '')
    } catch (err) {
      console.warn('Failed to save last route:', err)
    }
  }

  /**
   * Get last visited route
   */
  function getLastRoute() {
    try {
      return {
        path: sessionStorage.getItem('last_route') || '/dashboard',
        name: sessionStorage.getItem('last_route_name') || 'Dashboard'
      }
    } catch (err) {
      console.warn('Failed to get last route:', err)
      return { path: '/dashboard', name: 'Dashboard' }
    }
  }

  /**
   * Initialize session restoration
   */
  function initializeSession() {
    // Watch for route changes and save scroll position
    watch(() => route.path, () => {
      saveLastRoute()
      // Restore scroll position after a small delay for DOM update
      setTimeout(restoreScrollPosition, 100)
    })

    // Save scroll position when page is about to unload/refresh
    const handleBeforeUnload = () => {
      saveScrollPosition()
      saveLastRoute()
    }

    window.addEventListener('beforeunload', handleBeforeUnload)
    window.addEventListener('scroll', saveScrollPosition, { passive: true })

    onUnmounted(() => {
      window.removeEventListener('beforeunload', handleBeforeUnload)
      window.removeEventListener('scroll', saveScrollPosition)
    })

    // Restore scroll position on mount
    onMounted(() => {
      restoreScrollPosition()
    })
  }

  return {
    saveState,
    getState,
    saveScrollPosition,
    restoreScrollPosition,
    saveLastRoute,
    getLastRoute,
    clearSession,
    initializeSession,
    SESSION_KEY,
    SCROLL_KEY
  }
}
