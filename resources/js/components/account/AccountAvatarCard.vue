<template>
  <div class="app-card app-card--cyan p-6">
    <div class="text-center">
      <div class="w-24 h-24 mx-auto mb-4 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center border border-slate-300 dark:border-white/10">
        <img
          v-if="profileImage"
          :src="profileImage"
          :alt="`Foto profil ${displayName}`"
          class="h-full w-full object-cover"
        />
        <svg v-else class="w-12 h-12 text-cyan-600 dark:text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
      </div>
      <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ displayName }}</h2>
      <p class="text-slate-600 dark:text-slate-400 text-sm mt-1">{{ displayRole }}</p>
      <button
        type="button"
        class="mt-4 w-full rounded-full bg-cyan-500 px-4 py-2 font-semibold text-slate-950 transition hover:bg-cyan-400 disabled:cursor-not-allowed disabled:opacity-60"
        :disabled="uploading"
        @click="triggerPicker"
      >
        {{ uploading ? 'Mengunggah...' : 'Ubah Foto' }}
      </button>
      <input ref="photoInput" type="file" accept="image/*" class="hidden" @change="handleFileChange" />

      <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">
        JPG, PNG, WEBP. Maksimal 2 MB.
      </div>

      <div v-if="error" class="mt-3 rounded-2xl border border-rose-500/20 bg-rose-500/10 px-3 py-2 text-left text-xs text-rose-200">
        {{ error }}
      </div>

      <slot name="menu" />
    </div>
  </div>

  <ImageCropModal
    :open="cropperOpen"
    :src="cropperSource"
    @cancel="closeCropper"
    @confirm="handleCroppedFile"
  />
</template>

<script>
import { apiRequest } from '../../lib/api'
import { getAuthSession, setAuthSession } from '../../auth/session'
import ImageCropModal from './ImageCropModal.vue'

export default {
  name: 'AccountAvatarCard',
  components: {
    ImageCropModal,
  },
  props: {
    fallbackName: {
      type: String,
      default: 'Pengguna',
    },
    fallbackRole: {
      type: String,
      default: 'Pengguna',
    },
  },
  data() {
    return {
      uploading: false,
      error: null,
      profile: null,
      profileImage: '',
      cropperOpen: false,
      cropperSource: '',
      cropperObjectUrl: '',
      pendingFile: null,
    }
  },
  computed: {
    displayName() {
      return this.profile?.nama || this.fallbackName
    },
    displayRole() {
      return this.profile?.role || this.fallbackRole
    },
  },
  async mounted() {
    await this.loadProfile()
    window.addEventListener('account-profile-updated', this.handleProfileUpdated)
  },
  beforeUnmount() {
    window.removeEventListener('account-profile-updated', this.handleProfileUpdated)
  },
  methods: {
    handleProfileUpdated(event) {
      const updated = event?.detail
      if (!updated || typeof updated !== 'object') {
        return
      }

      this.profile = { ...(this.profile || {}), ...updated }
      this.profileImage = updated.gambar || this.profileImage || ''
    },
    async loadProfile() {
      const session = getAuthSession()
      this.profileImage = session?.gambar || ''

      if (!session?.id) {
        return
      }

      try {
        const users = await apiRequest('/api/users')
        const match = Array.isArray(users)
          ? users.find((item) => Number(item.id) === Number(session.id))
          : null

        if (match) {
          this.profile = match
          this.profileImage = match.gambar || this.profileImage || ''
          setAuthSession({ ...session, ...match })
        } else {
          this.profile = session
        }
      } catch (error) {
        this.profile = session
      }
    },
    triggerPicker() {
      this.error = null
      this.$refs.photoInput?.click()
    },
    async handleFileChange(event) {
      const file = event?.target?.files?.[0]
      if (!file) return

      if (!file.type.startsWith('image/')) {
        this.error = 'File harus berupa gambar.'
        event.target.value = ''
        return
      }

      if (file.size > 2 * 1024 * 1024) {
        this.error = 'Ukuran gambar awal maksimal 2 MB.'
        event.target.value = ''
        return
      }

      this.openCropper(file)
      event.target.value = ''
    },
    openCropper(file) {
      this.closeCropper()
      this.pendingFile = file
      this.cropperObjectUrl = URL.createObjectURL(file)
      this.cropperSource = this.cropperObjectUrl
      this.cropperOpen = true
    },
    closeCropper() {
      this.cropperOpen = false
      this.pendingFile = null

      if (this.cropperObjectUrl) {
        URL.revokeObjectURL(this.cropperObjectUrl)
      }

      this.cropperObjectUrl = ''
      this.cropperSource = ''
    },
    async handleCroppedFile(file) {
      this.cropperOpen = false

      if (this.cropperObjectUrl) {
        URL.revokeObjectURL(this.cropperObjectUrl)
      }

      this.cropperObjectUrl = ''
      this.cropperSource = ''
      this.pendingFile = null

      if (!file) {
        return
      }

      if (file.size > 2 * 1024 * 1024) {
        this.error = 'Hasil kompres masih terlalu besar. Coba crop ulang lebih rapat.'
        return
      }

      await this.uploadPhoto(file)
    },
    async uploadPhoto(file) {
      const session = getAuthSession()
      if (!session?.id) {
        this.error = 'Sesi akun tidak ditemukan. Silakan login ulang.'
        return
      }

      const payload = new FormData()
      payload.append('nama', this.profile?.nama || session?.nama || this.fallbackName)
      payload.append('email', this.profile?.email || session?.email || '')
      payload.append('role', this.profile?.role || session?.role || this.fallbackRole)
      payload.append('status', this.profile?.status || session?.status || 'Aktif')
      payload.append('telepon', this.profile?.telepon || session?.telepon || '')
      payload.append('gambar', file)

      this.uploading = true
      this.error = null

      try {
        const updated = await apiRequest(`/api/users/${session.id}`, {
          method: 'POST',
          body: (() => {
            payload.append('_method', 'PUT')
            return payload
          })(),
        })

        this.profile = updated
        this.profileImage = updated?.gambar || this.profileImage || ''
        setAuthSession({ ...session, ...updated })
        window.dispatchEvent(new CustomEvent('account-profile-updated', { detail: updated }))
      } catch (error) {
        this.error = error?.message || 'Gagal memperbarui foto profil.'
      } finally {
        this.uploading = false
      }
    },
  },
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
