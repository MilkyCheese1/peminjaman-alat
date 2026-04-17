<script setup lang="ts">
import { ref, onMounted, computed, nextTick } from 'vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui'
import { Send, MessageCircle } from 'lucide-vue-next'
import { ChatService, type ChatMessage } from '@/services/ChatService'

const userInfo = ref<any>(null)
const messages = ref<ChatMessage[]>([])
const messageInput = ref('')
const isSending = ref(false)
const messagesContainer = ref<HTMLDivElement | null>(null)
const hasUnread = ref(false)

const scrollToBottom = async () => {
  await nextTick()
  if (messagesContainer.value) {
    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
  }
}

const loadMessages = () => {
  if (!userInfo.value) return
  messages.value = ChatService.getMessages(userInfo.value.id_user, 'staff')
  hasUnread.value = ChatService.hasUnreadMessages(userInfo.value.id_user, 'staff')
}

const sendMessage = () => {
  if (!messageInput.value.trim() || !userInfo.value) return

  isSending.value = true

  ChatService.sendUserMessage(
    userInfo.value.id_user,
    userInfo.value.nama_lengkap,
    'staff',
    messageInput.value
  )

  messages.value = ChatService.getMessages(userInfo.value.id_user, 'staff')
  messageInput.value = ''
  isSending.value = false
  scrollToBottom()
}

const formatTime = (dateString: string) => {
  const date = new Date(dateString)
  const today = new Date()
  const yesterday = new Date(today)
  yesterday.setDate(yesterday.getDate() - 1)

  if (date.toDateString() === today.toDateString()) {
    return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
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
    loadMessages()
    scrollToBottom()
  }
})
</script>

<template>
  <Card v-if="userInfo">
    <CardHeader>
      <CardTitle class="flex items-center gap-2">
        <MessageCircle :size="20" />
        Staff Services
        <span v-if="hasUnread" class="ml-auto px-2 py-1 bg-red-600 text-white text-xs rounded-full font-semibold">
          Ada Pesan Baru
        </span>
      </CardTitle>
    </CardHeader>
    <CardContent class="flex flex-col h-96 space-y-4">
      <!-- Messages -->
      <div ref="messagesContainer" class="flex-1 overflow-y-auto space-y-3 bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
        <div v-if="messages.length === 0" class="flex items-center justify-center h-full text-muted-foreground">
          <p class="text-sm">Mulai percakapan dengan admin kami</p>
        </div>
        <div
          v-for="msg in messages"
          :key="msg.id"
          :class="[
            'flex',
            msg.sender_type === 'staff' ? 'justify-end' : 'justify-start'
          ]"
        >
          <div
            :class="[
              'max-w-xs px-3 py-2 rounded-lg text-sm',
              msg.sender_type === 'staff'
                ? 'bg-purple-600 text-white rounded-br-none'
                : 'bg-white dark:bg-gray-800 text-foreground rounded-bl-none border border-gray-200 dark:border-gray-700'
            ]"
          >
            <p>{{ msg.message }}</p>
            <p :class="['text-xs mt-1', msg.sender_type === 'staff' ? 'text-purple-100' : 'text-muted-foreground']">
              {{ formatTime(msg.created_at) }}
            </p>
          </div>
        </div>
      </div>

      <!-- Input -->
      <div class="flex gap-2">
        <input
          v-model="messageInput"
          type="text"
          placeholder="Ketik pesan..."
          @keyup.enter="sendMessage"
          :disabled="isSending"
          class="flex-1 px-3 py-2 border border-input rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-600 bg-background disabled:opacity-50"
        />
        <button
          @click="sendMessage"
          :disabled="!messageInput.trim() || isSending"
          class="px-3 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
        >
          <Send :size="16" />
        </button>
      </div>
    </CardContent>
  </Card>
</template>

<style scoped>
/* Additional styles if needed */
</style>
