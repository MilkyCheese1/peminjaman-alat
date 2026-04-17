<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { RouterView, useRoute, useRouter } from 'vue-router'
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'
import { ChatService } from '@/services/ChatService'
import {
  LayoutDashboard,
  Package,
  FileText,
  Settings,
  User,
  LogOut,
  MessageCircle
} from 'lucide-vue-next'

const route = useRoute()
const router = useRouter()
const isMobile = ref(false)
const userInfo = ref<any>(null)
const unreadCount = ref(0)
const pollInterval = ref<NodeJS.Timeout | null>(null)

const baseNavigation = [
  { name: 'Dasbor', path: '/dashboard', icon: LayoutDashboard }
]

const allNavigation = [
  { name: 'Dasbor', path: '/dashboard', icon: LayoutDashboard },
  { name: 'Alat', path: '/equipment', icon: Package, roles: ['admin', 'staff', 'owner'] },
  { name: 'Laporan', path: '/reports', icon: FileText, roles: ['admin', 'staff', 'owner'] },
  { name: 'Obrolan', path: '/chat', icon: MessageCircle, roles: ['admin', 'owner'] },
  { name: 'Akun', path: '/profile', icon: User },
  { name: 'Pengaturan', path: '/settings', icon: Settings }
]

const navigation = computed(() => {
  if (!userInfo.value) return baseNavigation
  
  const role = userInfo.value.role
  return allNavigation.filter(item => !item.roles || item.roles.includes(role))
})

const checkMobile = () => {
  isMobile.value = window.innerWidth < 1024
}

const updateUnreadCount = () => {
  unreadCount.value = ChatService.getUnreadCount()
}

const hasSettingsUnread = computed(() => {
  if (!userInfo.value) return false
  const role = userInfo.value.role
  if (role === 'customer') {
    return ChatService.hasUnreadMessages(userInfo.value.id_user, 'customer')
  } else if (role === 'staff') {
    return ChatService.hasUnreadMessages(userInfo.value.id_user, 'staff')
  }
  return false
})

const startPolling = () => {
  updateUnreadCount()
  pollInterval.value = setInterval(() => {
    updateUnreadCount()
  }, 3000) // Check every 3 seconds
}

const stopPolling = () => {
  if (pollInterval.value) {
    clearInterval(pollInterval.value)
    pollInterval.value = null
  }
}

const handleLogout = () => {
  stopPolling()
  localStorage.removeItem('user')
  localStorage.removeItem('token')
  router.push('/login')
}

onMounted(() => {
  const userStr = localStorage.getItem('user')
  if (userStr) {
    userInfo.value = JSON.parse(userStr)
    // Start polling for unread messages if admin/owner
    if (userInfo.value.role === 'admin' || userInfo.value.role === 'owner') {
      startPolling()
    }
  }
  checkMobile()
  window.addEventListener('resize', checkMobile)
})

onUnmounted(() => {
  stopPolling()
  window.removeEventListener('resize', checkMobile)
})
</script>

<template>
  <div class="flex h-screen bg-background">
    <aside class="w-64 bg-card border-r border-gray-200 dark:border-gray-700 flex flex-col h-full overflow-hidden shadow-sm no-print">
      <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center">
        <h3 class="text-sm font-semibold">Peminjaman Alat</h3>
      </div>

      <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <router-link v-for="item in navigation" :key="item.path" :to="item.path" :class="[
          'flex items-center gap-3 px-4 py-2 rounded-lg transition-colors text-sm font-medium relative',
          route.path === item.path
            ? 'bg-blue-600 text-white shadow-md'
            : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'
        ]">
          <component :is="item.icon" :size="20" />
          <span>{{ item.name }}</span>
          <!-- Unread notification badge for Chat menu -->
          <div v-if="item.name === 'Chat' && unreadCount > 0" class="ml-auto flex items-center gap-2">
            <span class="px-2 py-0.5 bg-red-600 text-white text-xs rounded-full font-semibold">
              {{ unreadCount }}
            </span>
          </div>
          <!-- Red line indicator under Chat menu if has unread -->
          <div v-if="item.name === 'Obrolan' && unreadCount > 0 && route.path !== item.path" class="absolute bottom-0 left-0 right-0 h-1 bg-red-600 rounded-b-lg"></div>
          <!-- Red line indicator under Settings menu for customer/staff with unread -->
          <div v-if="item.name === 'Pengaturan' && hasSettingsUnread && route.path !== item.path" class="absolute bottom-0 left-0 right-0 h-1 bg-red-600 rounded-b-lg"></div>
        </router-link>
      </nav>

      <!-- Logout Button at Bottom -->
      <div class="p-4 border-t border-gray-200 dark:border-gray-700">
        <button
          @click="handleLogout"
          class="w-full flex items-center gap-3 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white transition-colors text-sm font-medium"
        >
          <LogOut :size="20" />
          <span>Logout</span>
        </button>
      </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
      <div class="no-print">
        <Navbar :on-toggle-sidebar="() => {}" />
      </div>

      <main class="flex-1 overflow-auto">
        <div class="p-4 md:p-8">
          <RouterView />
        </div>
      </main>

      <div class="no-print">
        <Footer />
      </div>
    </div>
  </div>
</template>

<style scoped>
@media print {
  /* Reset main content padding for clean print */
  main {
    margin: 0 !important;
    padding: 0 !important;
    background: white !important;
  }

  main > div {
    margin: 0 !important;
    padding: 0 !important;
  }

  /* Hide no-print elements */
  .no-print {
    display: none !important;
  }
}
</style>
