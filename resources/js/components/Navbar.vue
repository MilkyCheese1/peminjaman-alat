<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { Search, User } from 'lucide-vue-next'

const router = useRouter()
const searchQuery = ref('')
const userInfo = ref<any>(null)

onMounted(() => {
  const userStr = localStorage.getItem('user')
  if (userStr) {
    userInfo.value = JSON.parse(userStr)
  }
})

const handleSearch = (e: Event) => {
  e.preventDefault()
  console.log('Search:', searchQuery.value)
}
</script>

<template>
  <nav class="bg-card border-b sticky top-0 z-40">
    <div class="px-4 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <div class="flex items-center gap-4">
          <form @submit="handleSearch" class="hidden md:block">
            <div class="relative">
              <Search :size="18" class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground" />
              <input
                v-model="searchQuery"
                type="search"
                placeholder="Search..."
                class="pl-10 pr-4 py-2 bg-background border rounded-md focus:outline-none focus:ring-2 focus:ring-primary w-64 lg:w-96"
              />
            </div>
          </form>
        </div>

        <div class="flex items-center gap-2">
          <div class="flex items-center gap-2 px-3 py-2">
            <div class="w-8 h-8 rounded-full bg-primary text-primary-foreground flex items-center justify-center">
              <User :size="18" />
            </div>
            <span class="hidden sm:block text-sm font-medium">{{ userInfo?.username || 'User' }}</span>
          </div>
        </div>
      </div>

      <div class="md:hidden pb-3">
        <form @submit="handleSearch">
          <div class="relative">
            <Search :size="18" class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground" />
            <input
              v-model="searchQuery"
              type="search"
              placeholder="Search..."
              class="w-full pl-10 pr-4 py-2 bg-background border rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
            />
          </div>
        </form>
      </div>
    </div>
  </nav>
</template>
