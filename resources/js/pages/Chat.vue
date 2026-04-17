<script setup lang="ts">
import { ref, onMounted, computed, watch, nextTick } from 'vue'
import { MessageCircle, Send, Loader } from 'lucide-vue-next'
import { ChatService, type ChatMessage, type ChatUser } from '@/services/ChatService'

const currentTab = ref<'customer' | 'staff'>('customer')
const selectedUser = ref<ChatUser | null>(null)
const newMessage = ref('')
const isSending = ref(false)
const userInfo = ref<any>(null)
const chatContainerRef = ref<HTMLElement | null>(null)

const customers = ref<ChatUser[]>([])
const staff = ref<ChatUser[]>([])
const messages = ref<ChatMessage[]>([])

const currentUsers = computed(() => {
  return currentTab.value === 'customer' ? customers.value : staff.value
})

const unreadCount = computed(() => {
  let count = 0
  customers.value.forEach(u => count += u.unread_count)
  staff.value.forEach(u => count += u.unread_count)
  return count
})

const selectUser = (user: ChatUser) => {
  selectedUser.value = user
  messages.value = ChatService.getMessages(user.id, user.type)
  ChatService.markAsRead(user.id, user.type)
  
  // Update local unread count
  user.unread_count = 0
  
  nextTick(() => {
    scrollToBottom()
  })
}

const sendMessage = () => {
  if (!newMessage.value.trim() || !selectedUser.value || !userInfo.value) return

  isSending.value = true

  setTimeout(() => {
    ChatService.sendMessage(
      userInfo.value.id_user,
      userInfo.value.username,
      'admin',
      newMessage.value,
      selectedUser.value!.id
    )

    messages.value = ChatService.getMessages(selectedUser.value!.id, selectedUser.value!.type)
    newMessage.value = ''
    isSending.value = false

    nextTick(() => {
      scrollToBottom()
    })
  }, 300)
}

const scrollToBottom = () => {
  if (chatContainerRef.value) {
    setTimeout(() => {
      chatContainerRef.value!.scrollTop = chatContainerRef.value!.scrollHeight
    }, 0)
  }
}

const handleKeyPress = (e: KeyboardEvent) => {
  if (e.key === 'Enter' && !e.shiftKey) {
    e.preventDefault()
    sendMessage()
  }
}

watch(currentTab, () => {
  selectedUser.value = null
  messages.value = []
})

const formatTime = (dateString: string) => {
  const date = new Date(dateString)
  const today = new Date()
  const yesterday = new Date(today)
  yesterday.setDate(yesterday.getDate() - 1)

  if (date.toDateString() === today.toDateString()) {
    return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false })
  } else if (date.toDateString() === yesterday.toDateString()) {
    return 'Kemarin'
  } else {
    return date.toLocaleDateString('id-ID', { month: 'short', day: 'numeric' })
  }
}

onMounted(() => {
  const userStr = localStorage.getItem('user')
  if (userStr) {
    userInfo.value = JSON.parse(userStr)
  }

  customers.value = ChatService.getCustomers()
  staff.value = ChatService.getStaff()
})
</script>

<template>
  <div class="space-y-6">
    <!-- Header -->
    <div>
      <h1 class="text-3xl font-bold text-foreground flex items-center gap-2">
        <MessageCircle :size="32" />
        Chat Support
      </h1>
      <p class="text-muted-foreground mt-2">Kelola chat dengan customer dan staff</p>
    </div>

    <!-- Tabs -->
    <div class="flex gap-4 border-b border-gray-200 dark:border-gray-700">
      <button
        @click="currentTab = 'customer'"
        :class="[
          'px-4 py-2 font-medium transition-colors relative',
          currentTab === 'customer'
            ? 'text-primary'
            : 'text-muted-foreground hover:text-foreground'
        ]"
      >
        Chat Customer
        <span v-if="currentTab === 'customer' && unreadCount > 0" class="ml-2 inline-block px-2 py-1 bg-red-500 text-white text-xs rounded-full">
          {{ unreadCount }}
        </span>
        <div v-if="currentTab === 'customer'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-primary"></div>
      </button>

      <button
        @click="currentTab = 'staff'"
        :class="[
          'px-4 py-2 font-medium transition-colors relative',
          currentTab === 'staff'
            ? 'text-primary'
            : 'text-muted-foreground hover:text-foreground'
        ]"
      >
        Chat Staff
        <span v-if="currentTab === 'staff' && unreadCount > 0" class="ml-2 inline-block px-2 py-1 bg-red-500 text-white text-xs rounded-full">
          {{ unreadCount }}
        </span>
        <div v-if="currentTab === 'staff'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-primary"></div>
      </button>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[600px]">
      <!-- Conversation List -->
      <div class="lg:col-span-1 border rounded-lg overflow-hidden bg-card">
        <div class="h-full flex flex-col">
          <!-- Search -->
          <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <input
              type="text"
              placeholder="Cari..."
              class="w-full px-3 py-2 border border-input rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-background"
            />
          </div>

          <!-- Conversations -->
          <div class="flex-1 overflow-y-auto">
            <button
              v-for="user in currentUsers"
              :key="`${user.type}_${user.id}`"
              @click="selectUser(user)"
              :class="[
                'w-full text-left px-4 py-3 border-b border-gray-100 dark:border-gray-800 transition-colors',
                selectedUser?.id === user.id
                  ? 'bg-primary/10'
                  : 'hover:bg-gray-50 dark:hover:bg-gray-900'
              ]"
            >
              <div class="flex items-center justify-between">
                <h3 class="font-semibold text-sm">{{ user.nama_lengkap }}</h3>
                <span v-if="user.unread_count > 0" class="bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                  {{ user.unread_count }}
                </span>
              </div>
              <p class="text-xs text-muted-foreground truncate mt-1">{{ user.last_message }}</p>
              <p class="text-xs text-muted-foreground mt-1">{{ formatTime(user.last_message_time || new Date().toISOString()) }}</p>
            </button>
          </div>
        </div>
      </div>

      <!-- Chat Window -->
      <div class="lg:col-span-2 border rounded-lg overflow-hidden bg-card flex flex-col">
        <div v-if="selectedUser" class="h-full flex flex-col">
          <!-- Chat Header -->
          <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-semibold">{{ selectedUser.nama_lengkap }}</h2>
            <p class="text-xs text-muted-foreground">@{{ selectedUser.username }}</p>
          </div>

          <!-- Messages -->
          <div
            ref="chatContainerRef"
            class="flex-1 overflow-y-auto p-4 space-y-3"
          >
            <div
              v-for="msg in messages"
              :key="msg.id"
              :class="[
                'flex gap-2',
                msg.sender_type === 'admin' ? 'justify-end' : 'justify-start'
              ]"
            >
              <div
                :class="[
                  'max-w-xs px-4 py-2 rounded-lg',
                  msg.sender_type === 'admin'
                    ? 'bg-primary text-primary-foreground'
                    : 'bg-gray-100 dark:bg-gray-800'
                ]"
              >
                <p class="text-sm">{{ msg.message }}</p>
                <p class="text-xs opacity-70 mt-1">{{ formatTime(msg.created_at) }}</p>
              </div>
            </div>
          </div>

          <!-- Message Input -->
          <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex gap-2">
              <textarea
                v-model="newMessage"
                @keypress="handleKeyPress"
                :disabled="isSending"
                placeholder="Ketik pesan..."
                rows="2"
                class="flex-1 px-3 py-2 border border-input rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-background disabled:bg-muted disabled:text-muted-foreground resize-none"
              ></textarea>
              <button
                @click="sendMessage"
                :disabled="isSending || !newMessage.trim()"
                class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
              >
                <Loader v-if="isSending" :size="16" class="animate-spin" />
                <Send v-else :size="16" />
              </button>
            </div>
          </div>
        </div>

        <!-- No Selection -->
        <div v-else class="h-full flex items-center justify-center">
          <p class="text-muted-foreground">Pilih percakapan untuk memulai chat</p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Additional styles if needed */
</style>
