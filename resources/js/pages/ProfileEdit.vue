<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { Upload, X, Check } from 'lucide-vue-next'

const router = useRouter()
const user = ref<any>(null)
const formData = ref({
  username: '',
  role: '',
  nama_lengkap: '',
  email: '',
  phone: '',
  address: '',
  city: '',
  province: '',
  postal_code: ''
})
const isSaving = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
const avatarFile = ref<File | null>(null)
const avatarPreview = ref('')

const getInitials = (username: string, nama_lengkap: string = '') => {
  const name = nama_lengkap || username || 'U'
  if (!name) return 'U'
  return name
    .split(' ')
    .map(n => n[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
}

const handleAvatarChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  const file = target.files?.[0]
  if (file) {
    avatarFile.value = file
    const reader = new FileReader()
    reader.onload = (e) => {
      avatarPreview.value = e.target?.result as string
    }
    reader.readAsDataURL(file)
  }
}

const clearAvatar = () => {
  avatarFile.value = null
  avatarPreview.value = ''
}

const canChangeUsername = () => {
  const role = user.value?.role
  return role === 'customer' || role === 'owner'
}

const saveProfile = async () => {
  isSaving.value = true
  errorMessage.value = ''
  successMessage.value = ''

  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // Update localStorage - username can only be changed by customer and owner
    const updatedUser: any = { 
      ...user.value, 
      nama_lengkap: formData.value.nama_lengkap,
      phone: formData.value.phone,
      address: formData.value.address,
      city: formData.value.city,
      province: formData.value.province,
      postal_code: formData.value.postal_code
    }
    
    // Only update username if user's role allows it
    if (canChangeUsername()) {
      updatedUser.username = formData.value.username
    }
    
    localStorage.setItem('user', JSON.stringify(updatedUser))
    user.value = updatedUser
    
    successMessage.value = 'Profil berhasil diperbarui!'
    setTimeout(() => {
      router.push('/profile')
    }, 2000)
  } catch (error) {
    errorMessage.value = 'Gagal menyimpan profil. Silakan coba lagi.'
  } finally {
    isSaving.value = false
  }
}

const cancelEdit = () => {
  router.push('/profile')
}

onMounted(() => {
  const userStr = localStorage.getItem('user')
  if (userStr) {
    user.value = JSON.parse(userStr)
    formData.value = {
      username: user.value.username || '',
      role: user.value.role || '',
      nama_lengkap: user.value.nama_lengkap || '',
      email: user.value.email || '',
      phone: user.value.phone || '',
      address: user.value.address || '',
      city: user.value.city || '',
      province: user.value.province || '',
      postal_code: user.value.postal_code || ''
    }
  }
})
</script>

<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div>
      <h1 class="text-3xl font-bold">Ubah Profil</h1>
      <p class="text-muted-foreground mt-2">Perbarui informasi profil Anda</p>
    </div>

    <!-- Messages -->
    <div v-if="successMessage" class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-2">
      <Check :size="20" />
      {{ successMessage }}
    </div>
    <div v-if="errorMessage" class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-2">
      <X :size="20" />
      {{ errorMessage }}
    </div>

    <div class="bg-card border rounded-lg p-8">
      <form @submit.prevent="saveProfile" class="space-y-8">
        <!-- Avatar Section -->
        <div>
          <h3 class="font-semibold mb-4">Foto Profil</h3>
          <div class="flex items-end gap-6">
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-primary to-primary/60 text-primary-foreground flex items-center justify-center font-bold text-2xl overflow-hidden flex-shrink-0">
              <img v-if="avatarPreview" :src="avatarPreview" alt="Avatar preview" class="w-full h-full object-cover">
              <span v-else>{{ getInitials(formData.username, formData.nama_lengkap) }}</span>
            </div>
            <div class="space-y-3">
              <div class="flex gap-2">
                <label class="flex items-center gap-2 px-4 py-2 bg-accent hover:bg-accent/80 rounded-lg cursor-pointer transition-colors">
                  <Upload :size="18" />
                  <span class="text-sm">Upload Foto</span>
                  <input type="file" accept="image/*" @change="handleAvatarChange" hidden />
                </label>
                <button v-if="avatarPreview" type="button" @click="clearAvatar" class="px-4 py-2 bg-destructive/20 text-destructive rounded-lg hover:bg-destructive/30 transition-colors">
                  Hapus
                </button>
              </div>
              <p class="text-xs text-muted-foreground">Ukuran maksimal 5MB. Format: JPG, PNG</p>
            </div>
          </div>
        </div>

        <!-- Personal Info -->
        <div>
          <h3 class="font-semibold mb-4">Informasi Pribadi</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Username -->
            <div>
              <label class="block text-sm font-medium mb-2">Username *</label>
              <input 
                v-model="formData.username" 
                type="text" 
                required
                :disabled="!canChangeUsername()"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background disabled:bg-muted disabled:text-muted-foreground disabled:cursor-not-allowed" 
              />
              <p v-if="!canChangeUsername()" class="text-xs text-muted-foreground mt-1">Username tidak dapat diubah (Role: {{ formData.role }})</p>
            </div>

            <!-- Role -->
            <div>
              <label class="block text-sm font-medium mb-2">Role</label>
              <input v-model="formData.role" type="text" disabled class="w-full px-4 py-2 border rounded-lg bg-muted text-muted-foreground cursor-not-allowed" />
              <p class="text-xs text-muted-foreground mt-1">Role tidak dapat diubah</p>
            </div>

            <!-- Nama Lengkap -->
            <div>
              <label class="block text-sm font-medium mb-2">Nama Lengkap *</label>
              <input v-model="formData.nama_lengkap" type="text" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background" />
            </div>

            <!-- Email -->
            <div>
              <label class="block text-sm font-medium mb-2">Email *</label>
              <input v-model="formData.email" type="email" disabled class="w-full px-4 py-2 border rounded-lg bg-muted text-muted-foreground cursor-not-allowed" />
              <p class="text-xs text-muted-foreground mt-1">Email tidak dapat diubah</p>
            </div>

            <!-- Phone -->
            <div>
              <label class="block text-sm font-medium mb-2">Nomor Telepon</label>
              <input v-model="formData.phone" type="tel" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background" />
            </div>

            <!-- Address -->
            <div>
              <label class="block text-sm font-medium mb-2">Alamat</label>
              <input v-model="formData.address" type="text" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background" />
            </div>

            <!-- City -->
            <div>
              <label class="block text-sm font-medium mb-2">Kota</label>
              <input v-model="formData.city" type="text" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background" />
            </div>

            <!-- Province -->
            <div>
              <label class="block text-sm font-medium mb-2">Provinsi</label>
              <input v-model="formData.province" type="text" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background" />
            </div>

            <!-- Postal Code -->
            <div>
              <label class="block text-sm font-medium mb-2">Kode Pos</label>
              <input v-model="formData.postal_code" type="text" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background" />
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 pt-6 border-t">
          <button type="button" @click="cancelEdit" class="px-6 py-2 border rounded-lg hover:bg-accent transition-colors">
            Batal
          </button>
          <button type="submit" :disabled="isSaving" class="px-6 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
            {{ isSaving ? 'Menyimpan...' : 'Simpan Perubahan' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
