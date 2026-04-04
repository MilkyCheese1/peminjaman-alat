import { createRouter, createWebHistory } from 'vue-router'
import LandingPage from './pages/LandingPage.vue'
import Login from './pages/Login.vue'
import Register from './pages/Register.vue'
import Dashboard from './pages/DashboardRoleAware.vue'
import DemoUsers from './pages/DemoUsers.vue'

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
    path: '/demo-users',
    name: 'DemoUsers',
    component: DemoUsers
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
      isAuthenticated = !!(userData && userData.id)
    }
  } catch (err) {
    console.warn('Failed to parse user data from localStorage:', err)
    isAuthenticated = false
    localStorage.removeItem('user')
  }
  
  if (to.meta.requiresAuth && !isAuthenticated) {
    return { name: 'Login' }
  }
  
  // Explicitly allow navigation
  return true
})

export default router
