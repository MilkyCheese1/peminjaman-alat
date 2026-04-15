import { createRouter, createWebHistory } from 'vue-router'
import LandingPage from './pages/LandingPage.vue'
import Login from './pages/Login.vue'
import Register from './pages/Register.vue'
import Dashboard from './pages/Dashboard.vue'
import Equipment from './pages/Equipment.vue'
import Reports from './pages/Reports.vue'
import Settings from './pages/Settings.vue'
import TermsAndConditions from './pages/TermsAndConditions.vue'
import MainLayout from './layouts/MainLayout.vue'

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
    meta: { requiresAuth: true, layout: 'MainLayout' }
  },
  {
    path: '/equipment',
    name: 'Equipment',
    component: Equipment,
    meta: { requiresAuth: true, layout: 'MainLayout' }
  },
  {
    path: '/reports',
    name: 'Reports',
    component: Reports,
    meta: { requiresAuth: true, layout: 'MainLayout' }
  },
  {
    path: '/settings',
    name: 'Settings',
    component: Settings,
    meta: { requiresAuth: true, layout: 'MainLayout' }
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
router.beforeEach((to, from) => {
  let isAuthenticated = false
  
  try {
    const userStr = localStorage.getItem('user')
    if (userStr) {
      const userData = JSON.parse(userStr)
      // Check if user has required fields
      isAuthenticated = !!(userData && userData.id && userData.email && userData.role)
      
      if (!isAuthenticated) {
        localStorage.removeItem('user')
      }
    }
  } catch (err) {
    isAuthenticated = false
    localStorage.removeItem('user')
  }
  
  // Redirect to login if trying to access protected route
  if (to.meta.requiresAuth && !isAuthenticated) {
    return { name: 'Login' }
  }
  
  // Redirect to landing if already logged in and going to login
  if (to.name === 'Login' && isAuthenticated) {
    return { name: 'Dashboard' }
  }
  
  // Explicitly allow navigation
  return true
})

export default router
