<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { ChevronDown, LogOut } from 'lucide-vue-next'

interface Props {
  user: any
  menuItems: any[]
  isOpen: boolean
}

const props = defineProps<Props>()
const emit = defineEmits<{
  toggle: []
}>()

const router = useRouter()

const getRoleLabel = (role: string) => {
  const roleMap: Record<string, string> = {
    admin: 'Admin',
    owner: 'Pemilik',
    staff: 'Staff',
    customer: 'Pengguna'
  }
  return roleMap[role] || role
}

const getInitials = (name: string) => {
  if (!name) return 'U'
  return name
    .split(' ')
    .map(n => n[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
}

const getJoinDate = (user: any) => {
  if (!user?.created_at) return 'Terbaru'
  const date = new Date(user.created_at)
  return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' })
}

const handleNavigate = (path: string) => {
  router.push(path)
  emit('toggle')
}

const handleLogout = () => {
  localStorage.removeItem('user')
  localStorage.removeItem('token')
  router.push('/login')
}
</script>

<template>
  <div class="border-b p-4">
    <button
      @click="emit('toggle')"
      class="w-full p-3 rounded-lg hover:bg-accent transition-colors mb-2"
    >
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-primary text-primary-foreground flex items-center justify-center font-semibold text-sm">
          {{ getInitials(user?.name || '') }}
        </div>
        <div class="flex-1 text-left">
          <p class="text-sm font-medium">{{ user?.name || 'User' }}</p>
          <p class="text-xs text-muted-foreground truncate">{{ user?.email }}</p>
        </div>
        <ChevronDown :size="16" :class="isOpen ? 'rotate-180' : ''" class="transition-transform" />
      </div>
    </button>

    <!-- Profile Card (Expanded) -->
    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="max-h-0 opacity-0"
      enter-to-class="max-h-96 opacity-100"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="max-h-96 opacity-100"
      leave-to-class="max-h-0 opacity-0"
    >
      <div v-if="isOpen" class="bg-accent/50 rounded-lg p-4 space-y-4 overflow-hidden">
        <!-- User Info Card -->
        <div class="space-y-3">
          <div class="flex justify-center">
            <div class="w-16 h-16 rounded-full bg-primary text-primary-foreground flex items-center justify-center font-bold text-xl">
              {{ getInitials(user?.name || '') }}
            </div>
          </div>

          <div class="text-center space-y-2">
            <p class="font-semibold text-sm">{{ user?.name || 'User' }}</p>
            <div class="space-y-1 text-xs">
              <p class="text-muted-foreground">{{ user?.email }}</p>
              <p class="inline-block px-2 py-1 bg-primary/20 text-primary rounded">{{ getRoleLabel(user?.role) }}</p>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-2 pt-2 border-t text-xs">
            <div class="text-center">
              <p class="text-muted-foreground">Status</p>
              <p class="font-medium">🟢 Aktif</p>
            </div>
            <div class="text-center">
              <p class="text-muted-foreground">Bergabung</p>
              <p class="font-medium">{{ getJoinDate(user) }}</p>
            </div>
          </div>
        </div>

        <!-- Menu Items -->
        <div class="space-y-1 pt-2 border-t">
          <button
            v-for="item in menuItems"
            :key="item.path"
            @click="handleNavigate(item.path)"
            class="w-full flex items-center gap-2 px-3 py-2 text-sm rounded-md hover:bg-accent transition-colors text-left"
          >
            <component :is="item.icon" :size="16" />
            <span>{{ item.name }}</span>
          </button>
        </div>

        <!-- Logout Button -->
        <button
          @click="handleLogout"
          class="w-full flex items-center gap-2 px-3 py-2 text-sm rounded-md bg-destructive text-destructive-foreground hover:bg-destructive/90 transition-colors"
        >
          <LogOut :size="16" />
          <span>Logout</span>
        </button>
      </div>
    </transition>
  </div>
</template>
