<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">
    <component :is="sidebarComponent" />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-6">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Notifikasi</h1>
          <p class="text-slate-700 dark:text-slate-300">Daftar notifikasi untuk {{ roleLabel }}</p>
        </div>

        <div class="app-card app-card--cyan p-6">
          <div v-if="loading" class="rounded-2xl bg-slate-100 dark:bg-slate-800/50 p-4 text-sm text-slate-700 dark:text-slate-300">
            Memuat notifikasi...
          </div>
          <div v-else-if="displayItems.length" class="space-y-3">
            <div v-for="item in displayItems" :key="item.id" class="rounded-2xl bg-slate-100 dark:bg-slate-800/50 p-4 text-sm text-slate-700 dark:text-slate-300">
              <div class="flex items-start justify-between gap-4">
                <div>
                  <p class="font-semibold text-slate-900 dark:text-white">{{ item.judul }}</p>
                  <p class="mt-1">{{ item.pesan }}</p>
                  <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ item.createdAt }}</p>
                </div>
                <span class="rounded-full px-3 py-1 text-xs font-semibold" :class="typeClass(item.tipe)">
                  {{ item.tipe }}
                </span>
              </div>
            </div>
          </div>
          <div v-else class="rounded-2xl bg-slate-100 dark:bg-slate-800/50 p-4 text-sm text-slate-700 dark:text-slate-300">
            Belum ada notifikasi.
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import SidebarAdmin from '../components/layout/SidebarAdmin.vue'
import SidebarOwner from '../components/layout/SidebarOwner.vue'
import SidebarStaff from '../components/layout/SidebarStaff.vue'
import SidebarPeminjam from '../components/layout/SidebarPeminjam.vue'
import Navbar from '../components/layout/Navbar.vue'
import { apiRequest } from '../lib/api'
import { getAuthSession } from '../auth/session'

const sidebarByRole = {
  admin: SidebarAdmin,
  owner: SidebarOwner,
  staff: SidebarStaff,
  peminjam: SidebarPeminjam,
}

const roleLabelByRole = {
  admin: 'Admin',
  owner: 'Owner',
  staff: 'Staff',
  peminjam: 'Peminjam',
}

export default {
  name: 'Notifikasi',
  components: {
    SidebarAdmin,
    SidebarOwner,
    SidebarStaff,
    SidebarPeminjam,
    Navbar,
  },
  props: {
    role: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      items: [],
    }
  },
  computed: {
    sidebarComponent() {
      return sidebarByRole[this.role] ?? SidebarPeminjam
    },
    roleLabel() {
      return roleLabelByRole[this.role] ?? 'Pengguna'
    },
    displayItems() {
      const sessionId = Number(getAuthSession()?.id || 0)
      const matched = (Array.isArray(this.items) ? this.items : []).filter((item) => !sessionId || Number(item.userId || 0) === sessionId)
      return matched.length ? matched : (Array.isArray(this.items) ? this.items : [])
    },
  },
  async created() {
    await this.loadNotifications()
  },
  methods: {
    async loadNotifications() {
      this.loading = true
      try {
        const data = await apiRequest('/api/notifications')
        this.items = Array.isArray(data) ? data : []
      } catch (error) {
        this.items = []
      } finally {
        this.loading = false
      }
    },
    typeClass(type) {
      const key = String(type || '').toLowerCase()
      if (key === 'success') return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-200'
      if (key === 'warning') return 'bg-amber-100 text-amber-800 dark:bg-amber-500/15 dark:text-amber-200'
      if (key === 'error') return 'bg-rose-100 text-rose-800 dark:bg-rose-500/15 dark:text-rose-200'
      return 'bg-cyan-100 text-cyan-800 dark:bg-cyan-500/15 dark:text-cyan-200'
    },
  },
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
