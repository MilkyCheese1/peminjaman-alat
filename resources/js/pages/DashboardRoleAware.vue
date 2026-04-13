<template>
  <div class="dashboard-wrapper">
    <!-- Header -->
    <header class="dash-header" ref="headerElement" :style="isMobileView ? { transform: `translateY(${headerTranslateY}px)` } : {}">
      <div class="header-top">
        <div class="logo-section">
          <div class="logo-icon">🎓</div>
          <div class="logo-text">
            <h1>TrustEquip</h1>
            <p class="role-label" :style="{ color: roleColor }">{{ roleLabel }}</p>
          </div>
        </div>
        
        <div class="header-actions">
          <!-- Notifications -->
          <div class="notification-container">
            <button class="notif-btn" @click="showNotifications = !showNotifications">
              🔔
              <span class="notif-badge" v-if="unreadCount > 0">{{ unreadCount }}</span>
            </button>
            <div v-if="showNotifications" class="notif-dropdown">
              <div class="notif-header">
                <h3>Notifikasi ({{ notifications.length }})</h3>
                <button @click="clearNotifications" class="clear-btn">Hapus Semua</button>
              </div>
              <div class="notif-items">
                <div v-for="notif in notifications" :key="notif.id" class="notif-item" :class="{ unread: !notif.read }">
                  <div class="notif-icon">{{ notif.icon }}</div>
                  <div class="notif-text">
                    <p class="notif-title">{{ notif.title }}</p>
                    <p class="notif-desc">{{ notif.desc }}</p>
                    <p class="notif-time">{{ notif.time }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- User Menu -->
          <div class="user-section">
            <div class="user-info">
              <div class="user-avatar" :style="{ background: roleColor }">{{ userInitial }}</div>
              <div class="user-details">
                <p class="user-name">{{ userName }}</p>
                <p class="user-role">{{ roleLabel }}</p>
              </div>
            </div>
          </div>

          <!-- Logout Button - Top Right -->
          <button @click="logout" class="logout-btn" title="Logout">
            Keluar
          </button>
        </div>
      </div>

      <!-- Navigation Tabs - Role-based -->
      <nav class="dash-nav">
        <button v-for="tab in allowedTabs" :key="tab.id"
                :class="['tab-btn', { active: activeTab === tab.id }]"
                @click="activeTab = tab.id"
                :style="{ borderBottomColor: activeTab === tab.id ? roleColor : 'transparent' }">
          {{ tab.icon }} {{ tab.label }}
        </button>
      </nav>
    </header>

    <!-- Main Content -->
    <main class="dash-main">
      <!-- CUSTOMER DASHBOARD -->
      <template v-if="userRole === 'customer'">
        <CustomerDashboard :activeTab="activeTab" :roleColor="roleColor" />
      </template>

      <!-- OWNER DASHBOARD -->
      <template v-else-if="userRole === 'owner'">
        <OwnerDashboard :activeTab="activeTab" :roleColor="roleColor" />
      </template>

      <!-- STAFF DASHBOARD -->
      <template v-else-if="userRole === 'staff'">
        <StaffDashboard :activeTab="activeTab" :roleColor="roleColor" />
      </template>

      <!-- ADMIN DASHBOARD -->
      <template v-else-if="userRole === 'admin'">
        <AdminDashboard :activeTab="activeTab" :roleColor="roleColor" />
      </template>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { getAllowedTabs, getRoleColor, getRoleLabel } from '../data/rolePermissions.js'
import { useSessionRestoration } from '../composables/useSessionRestoration.js'
import CustomerDashboard from '../components/dashboards/CustomerDashboard.vue'
import OwnerDashboard from '../components/dashboards/OwnerDashboard.vue'
import StaffDashboard from '../components/dashboards/StaffDashboard.vue'
import AdminDashboard from '../components/dashboards/AdminDashboard.vue'

const router = useRouter()
const { saveState, getState } = useSessionRestoration()

// State
const activeTab = ref('overview')
const showNotifications = ref(false)
const userName = ref('User')
const userRole = ref('customer')
const userInitial = ref('U')
const headerTranslateY = ref(0)
const lastScrollY = ref(0)
const isMobileView = ref(window.innerWidth < 576)
const headerElement = ref(null)
const headerHeight = ref(0) // Will be set after mount

// Notifications
const notifications = ref([
  {
    id: 1,
    icon: '📦',
    title: 'Siap Diambil',
    desc: 'Laptop Dell XPS siap untuk diambil',
    time: '2 jam yang lalu',
    read: false
  },
  {
    id: 2,
    icon: '⏰',
    title: 'Pengembalian Tertunda',
    desc: 'Kamera DSLR Canon harus dikembalikan hari ini',
    time: '5 jam yang lalu',
    read: false
  },
  {
    id: 3,
    icon: '✅',
    title: 'Peminjaman Dikonfirmasi',
    desc: 'Proyektor 4K telah dikonfirmasi',
    time: '1 hari yang lalu',
    read: true
  }
])

const unreadCount = computed(() => notifications.value.filter(n => !n.read).length)

const roleColor = computed(() => getRoleColor(userRole.value))
const roleLabel = computed(() => getRoleLabel(userRole.value))

const allowedTabs = computed(() => {
  const tabs = getAllowedTabs(userRole.value)
  const tabsData = {
    overview: { id: 'overview', label: 'Beranda', icon: '🏠' },
    explore: { id: 'explore', label: 'Jelajahi Alat', icon: '🛍️' },
    browse: { id: 'browse', label: 'Jelajahi Alat', icon: '🛍️' },
    'my-borrowings': { id: 'my-borrowings', label: 'Peminjaman Saya', icon: '📦' },
    'my-items': { id: 'my-items', label: 'Alat Saya', icon: '🔧' },
    borrowings: { id: 'borrowings', label: 'Peminjaman', icon: '📋' },
    orders: { id: 'orders', label: 'Pesanan', icon: '🛒' },
    users: { id: 'users', label: 'Pengguna', icon: '👥' },
    items: { id: 'items', label: 'Alat', icon: '📦' },
    returns: { id: 'returns', label: 'Pengembalian', icon: '↩️' },
    reports: { id: 'reports', label: 'Laporan', icon: '📊' },
    settings: { id: 'settings', label: 'Pengaturan', icon: '⚙️' },
    'activity-logs': { id: 'activity-logs', label: 'Log Aktivitas', icon: '📝' },
    approvals: { id: 'approvals', label: 'Persetujuan', icon: '✓' },
    verifications: { id: 'verifications', label: 'Verifikasi', icon: '🔍' },
    profile: { id: 'profile', label: 'Profil', icon: '👤' },
    help: { id: 'help', label: 'Bantuan', icon: '❓' }
  }
  
  return tabs.map(tabId => tabsData[tabId]).filter(tab => tab)
})

// Methods
const clearNotifications = () => {
  notifications.value = []
}

const logout = () => {
  localStorage.removeItem('user')
  const { clearSession } = useSessionRestoration()
  clearSession()
  router.push('/login')
}

// Lifecycle
onMounted(() => {
  const userStr = localStorage.getItem('user')
  if (userStr) {
    try {
      const user = JSON.parse(userStr)
      userName.value = user.fullname || user.email.split('@')[0] || 'User'
      userRole.value = user.role || 'customer'
      userInitial.value = userName.value.charAt(0).toUpperCase()
      
      // Restore active tab from session, or use first allowed tab
      const tabs = getAllowedTabs(userRole.value)
      if (tabs.length > 0) {
        const savedTab = getState('activeTab')
        activeTab.value = (savedTab && tabs.includes(savedTab)) ? savedTab : tabs[0]
      }
    } catch (e) {
      userName.value = 'User'
    }
  }
  
  // Set actual header height after render
  if (headerElement.value) {
    headerHeight.value = headerElement.value.offsetHeight
  }
  
  // Add scroll and resize listeners with passive option
  window.addEventListener('scroll', handleScroll, { passive: true })
  window.addEventListener('resize', handleResize, { passive: true })
})

// Save active tab whenever it changes
watch(activeTab, (newTab) => {
  saveState('activeTab', newTab)
})

// Handle scroll behavior for mobile
const handleScroll = () => {
  // Only handle for mobile
  if (!isMobileView.value || headerHeight.value === 0) return
  
  const currentScrollY = window.scrollY
  
  // If at the very top, fully show header
  if (currentScrollY <= 0) {
    headerTranslateY.value = 0
    lastScrollY.value = 0
    return
  }
  
  // Calculate scroll direction
  const diff = currentScrollY - lastScrollY.value
  
  // Only update if significant scroll (to avoid micro-movements)
  if (Math.abs(diff) < 1) {
    return
  }
  
  // Calculate new position (INVERTED: subtract diff instead of add)
  // Scroll DOWN (diff positive) → translate negative (hide navbar)
  // Scroll UP (diff negative) → translate positive (show navbar)
  let newTranslateY = headerTranslateY.value - diff
  
  // Clamp between 0 (fully visible) and -headerHeight (fully hidden)
  newTranslateY = Math.max(-headerHeight.value, Math.min(0, newTranslateY))
  
  headerTranslateY.value = newTranslateY
  lastScrollY.value = currentScrollY
}

// Handle window resize
const handleResize = () => {
  const wasMobile = isMobileView.value
  isMobileView.value = window.innerWidth < 576
  
  // If changed from desktop to mobile or vice versa
  if (wasMobile !== isMobileView.value) {
    headerTranslateY.value = 0
    lastScrollY.value = 0
    
    // Update header height for new layout
    if (isMobileView.value && headerElement.value) {
      setTimeout(() => {
        if (headerElement.value) {
          headerHeight.value = headerElement.value.offsetHeight
        }
      }, 0)
    }
  }
}

// Lifecycle
onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
  window.removeEventListener('resize', handleResize)
})
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

:root {
  --primary: #0B7285;
  --primary-light: #089FB3;
  --accent: #FF9F1C;
  --text-primary: #1a1a2e;
  --text-secondary: #666;
  --bg-light: #f8f9fa;
  --border: #e0e0e0;
}

.dashboard-wrapper {
  min-height: 100vh;
  background: linear-gradient(135deg, var(--bg-light) 0%, #f0f2f5 100%);
}

/* ===== HEADER ===== */
.dash-header {
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  color: white;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  z-index: 100;
}

/* Desktop: sticky */
@media (min-width: 577px) {
  .dash-header {
    position: sticky;
    top: 0;
    transition: transform 0.3s ease-in-out;
  }
}

.header-top {
  max-width: 1400px;
  margin: 0 auto;
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 30px;
  position: relative;
}

.logo-section {
  display: flex;
  align-items: center;
  gap: 15px;
}

.logo-icon {
  font-size: 2.5rem;
}

.logo-text h1 {
  font-size: 1.5rem;
  margin: 0;
}

.logo-text p {
  font-size: 0.85rem;
  opacity: 0.9;
  margin: 0;
}

.role-label {
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.8rem;
  letter-spacing: 1px;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 20px;
  flex: 1;
}

.notification-container {
  position: relative;
}

.notif-btn {
  background: rgba(255, 255, 255, 0.2);
  border: none;
  color: white;
  font-size: 1.3rem;
  cursor: pointer;
  padding: 8px 12px;
  border-radius: 6px;
  position: relative;
  transition: all 0.3s ease;
}

.notif-btn:hover {
  background: rgba(255, 255, 255, 0.3);
}

.notif-badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background: #FF6B6B;
  color: white;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.7rem;
  font-weight: bold;
}

.notif-dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  background: white;
  color: var(--text-primary);
  width: 350px;
  border-radius: 8px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  z-index: 1000;
  margin-top: 10px;
  max-height: 400px;
  display: flex;
  flex-direction: column;
}

.notif-header {
  padding: 15px;
  border-bottom: 1px solid var(--border);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.notif-header h3 {
  margin: 0;
  font-size: 1rem;
}

.clear-btn {
  background: none;
  border: none;
  color: var(--primary);
  cursor: pointer;
  font-size: 0.85rem;
  text-decoration: underline;
}

.notif-items {
  overflow-y: auto;
  max-height: 300px;
}

.notif-item {
  padding: 15px;
  border-bottom: 1px solid var(--border);
  display: flex;
  gap: 12px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.notif-item.unread {
  background: rgba(11, 114, 133, 0.05);
}

.notif-item:hover {
  background: var(--bg-light);
}

.notif-icon {
  font-size: 1.5rem;
  flex-shrink: 0;
}

.notif-text {
  flex: 1;
}

.notif-title {
  margin: 0 0 5px 0;
  font-weight: 600;
  font-size: 0.95rem;
}

.notif-desc {
  margin: 0 0 5px 0;
  font-size: 0.85rem;
  color: var(--text-secondary);
}

.notif-time {
  margin: 0;
  font-size: 0.75rem;
  color: #999;
}

.user-section {
  display: flex;
  align-items: center;
  gap: 15px;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  color: white;
  font-size: 1rem;
}

.user-details p {
  margin: 0;
  font-size: 0.9rem;
}

.user-name {
  font-weight: 600;
}

.user-role {
  font-size: 0.8rem;
  opacity: 0.9;
}

.logout-btn {
  background: #dc3545;
  border: none;
  color: white;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  padding: 10px 24px;
  border-radius: 6px;
  transition: all 0.3s ease;
  position: absolute;
  top: 20px;
  right: 20px;
}

.logout-btn:hover {
  background: #c82333;
  transform: scale(1.05);
}

/* ===== NAVIGATION ===== */
.dash-nav {
  background: rgba(0, 0, 0, 0.1);
  padding: 0;
  display: flex;
  max-width: 1400px;
  margin: 0 auto;
  overflow-x: auto;
}

.tab-btn {
  background: none;
  border: none;
  color: rgba(255, 255, 255, 0.7);
  padding: 15px 20px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
  border-bottom: 3px solid transparent;
  white-space: nowrap;
}

.tab-btn:hover {
  color: white;
}

.tab-btn.active {
  color: white;
  border-bottom-width: 3px;
}

/* ===== MAIN CONTENT ===== */
.dash-main {
  max-width: 1400px;
  margin: 0 auto;
  padding: 30px 20px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .header-top {
    flex-direction: column;
    gap: 15px;
  }

  .header-actions {
    flex-direction: column;
    width: 100%;
  }

  .dash-nav {
    flex-wrap: wrap;
  }

  .dash-main {
    padding: 15px;
  }
}

/* ===== MOBILE NAVBAR BEHAVIOR (576px and below) ===== */
@media (max-width: 576px) {
  .dash-header {
    position: fixed !important;
    top: 0 !important;
    left: 0;
    right: 0;
    width: 100%;
    will-change: transform;
    transform-origin: top;
    /* transform will be set via inline style for scroll behavior */
  }
  
  .header-top {
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
  }
  
  .logo-section {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
  }

  .logo-icon {
    font-size: 1.8rem;
    flex-shrink: 0;
  }

  .logo-text {
    min-width: 0;
  }

  .logo-text h1 {
    font-size: 1rem;
    margin: 0;
    white-space: nowrap;
  }

  .logo-text p {
    font-size: 0.65rem;
    opacity: 0.9;
    margin: 0;
    white-space: nowrap;
  }

  .header-actions {
    display: none !important;
  }
  
  .dash-nav {
    padding: 0;
    background: rgba(0, 0, 0, 0.15);
    min-height: 45px;
    display: flex;
    align-items: center;
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
  }

  .dash-main {
    padding-top: 140px;
    padding-left: 15px;
    padding-right: 15px;
    padding-bottom: 15px;
    margin-top: 10px;
  }

  .tab-btn {
    padding: 10px 14px;
    font-size: 0.8rem;
    white-space: nowrap;
    flex-shrink: 0;
  }

  /* Ensure header stays on top */
  .notification-container,
  .user-section,
  .logout-btn {
    display: none !important;
  }
}
</style>
