import { createRouter, createWebHistory } from 'vue-router'
import LandingPage from './pages/LandingPage.vue'
import Login from './pages/Login.vue'
import Register from './pages/Register.vue'
import Dashboard from './pages/DashboardRoleAware.vue'
import TermsAndConditions from './pages/TermsAndConditions.vue'

const routes = [
  {
    path: '/',
    name: 'Landing',
    component: LandingPage
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/register',
    name: 'Register',
    component: Register
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard,
    meta: { requiresAuth: true }
  },
  {
    path: '/terms',
    name: 'TermsAndConditions',
    component: TermsAndConditions
  },
  {
    path: '/forgot-password',
    redirect: '/login'
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Navigation guard untuk proteksi halaman yang memerlukan autentikasi
// Updated: Use Vue Router 4 syntax (return instead of next callback)
router.beforeEach((to, from) => {
  let isAuthenticated = false
  
  try {
    const userStr = localStorage.getItem('user')
    if (userStr) {
      const userData = JSON.parse(userStr)
      // Check if user has required fields
      isAuthenticated = !!(userData && userData.id && userData.email && userData.role)
      
      if (!isAuthenticated) {
        console.warn('⚠️ User data incomplete in localStorage:', userData)
        localStorage.removeItem('user')
      } else {
        console.log('✅ User authenticated:', userData.email)
      }
    } else {
      console.log('ℹ️ No user data in localStorage')
    }
  } catch (err) {
    console.warn('❌ Failed to parse user data from localStorage:', err)
    console.warn('Error details:', err.message)
    isAuthenticated = false
    localStorage.removeItem('user')
  }
  
  // Redirect to login if trying to access protected route
  if (to.meta.requiresAuth && !isAuthenticated) {
    console.log('🔒 Redirecting to login - route requires auth:', to.path)
    return { name: 'Login' }
  }
  
  // Redirect to landing if already logged in and going to login
  if (to.name === 'Login' && isAuthenticated) {
    console.log('↩️ Already authenticated, redirecting to dashboard')
    return { name: 'Dashboard' }
  }
  
  // Explicitly allow navigation
  return true
})

export default router
