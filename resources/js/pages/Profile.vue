<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { Mail, Building, User, Calendar, LogOut, Phone, MapPin, FileText, Camera } from 'lucide-vue-next'

const router = useRouter()
const user = ref<any>(null)
const isEditing = ref(false)
const photoPreview = ref<string>('')
const fileInputRef = ref<HTMLInputElement | null>(null)
const formData = ref({
  username: '',
  nama_lengkap: '',
  email: '',
  phone: '',
  address: '',
  id_number: '',
  birth_place: '',
  birth_date: '',
  gender: '',
  city: '',
  province: '',
  postal_code: ''
})

const getRoleLabel = (role: string) => {
  const roleMap: Record<string, string> = {
    admin: 'Admin',
    owner: 'Pemilik',
    staff: 'Staf',
    customer: 'Pengguna'
  }
  return roleMap[role] || role
}

const canChangeUsername = () => {
  const role = user.value?.role
  return role === 'customer' || role === 'owner'
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

const formatDate = (date: string) => {
  if (!date) return '-'
  return new Date(date).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const handleEdit = () => {
  isEditing.value = true
  photoPreview.value = user.value?.photo || ''
  formData.value = {
    username: user.value.username || '',
    nama_lengkap: user.value.nama_lengkap || '',
    email: user.value.email || '',
    phone: user.value.phone || '',
    address: user.value.address || '',
    id_number: user.value.id_number || '',
    birth_place: user.value.birth_place || '',
    birth_date: user.value.birth_date || '',
    gender: user.value.gender || '',
    city: user.value.city || '',
    province: user.value.province || '',
    postal_code: user.value.postal_code || ''
  }
}

const handleSave = () => {
  if (user.value) {
    const updatedUser: any = { 
      ...user.value, 
      nama_lengkap: formData.value.nama_lengkap,
      phone: formData.value.phone,
      address: formData.value.address,
      id_number: formData.value.id_number,
      birth_place: formData.value.birth_place,
      birth_date: formData.value.birth_date,
      gender: formData.value.gender,
      city: formData.value.city,
      province: formData.value.province,
      postal_code: formData.value.postal_code,
      photo: photoPreview.value 
    }
    
    // Only update username if user's role allows it
    if (canChangeUsername()) {
      updatedUser.username = formData.value.username
    }
    
    localStorage.setItem('user', JSON.stringify(updatedUser))
    user.value = updatedUser
    isEditing.value = false
    photoPreview.value = ''
  }
}

const handlePhotoUpload = (event: Event) => {
  const file = (event.target as HTMLInputElement).files?.[0]
  if (file) {
    const reader = new FileReader()
    reader.onload = (e) => {
      photoPreview.value = e.target?.result as string
    }
    reader.readAsDataURL(file)
  }
}

const triggerPhotoUpload = () => {
  fileInputRef.value?.click()
}

const handleCancel = () => {
  isEditing.value = false
}

const handleLogout = () => {
  localStorage.removeItem('user')
  localStorage.removeItem('token')
  router.push('/login')
}

onMounted(() => {
  const userStr = localStorage.getItem('user')
  if (userStr) {
    user.value = JSON.parse(userStr)
  }
})
</script>

<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div>
      <h1 class="text-3xl font-bold">Akun Saya</h1>
      <p class="text-muted-foreground mt-2">Kelola informasi profil akun Anda</p>
    </div>

    <!-- Profile Card -->
    <div class="bg-card border rounded-lg overflow-hidden">
      <!-- Header Section -->
      <div class="bg-gradient-to-r from-primary to-primary/60 p-6 text-white">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <div v-if="!isEditing" class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center font-bold text-2xl overflow-hidden">
              <img v-if="user?.photo" :src="user.photo" alt="Profile" class="w-full h-full object-cover" />
              <span v-else>{{ getInitials(user?.name || '') }}</span>
            </div>
            <div v-else class="relative group">
              <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center font-bold text-2xl overflow-hidden">
                <img v-if="photoPreview" :src="photoPreview" alt="Profile Preview" class="w-full h-full object-cover" />
              <span v-else>{{ getInitials(user?.nama_lengkap || user?.username || '') }}</span>
              </div>
              <button
                @click="triggerPhotoUpload"
                class="absolute bottom-0 right-0 bg-white text-primary rounded-full p-2 shadow-lg hover:bg-gray-100 transition-colors"
              >
                <Camera :size="16" />
              </button>
              <input
                ref="fileInputRef"
                type="file"
                accept="image/*"
                @change="handlePhotoUpload"
                class="hidden"
              />
            </div>
            <div>
            <h2 class="text-2xl font-bold">{{ user?.username || 'User' }}</h2>
            <p class="text-white/80">{{ user?.nama_lengkap || user?.email }}</p>
              <span class="inline-block mt-2 px-3 py-1 bg-white/20 rounded-full text-sm font-medium">
                {{ getRoleLabel(user?.role) }}
              </span>
            </div>
          </div>
          <button
            v-if="!isEditing"
            @click="handleEdit"
            class="px-6 py-2 bg-white text-primary rounded-lg hover:bg-white/90 transition-colors font-medium"
          >
            Edit Profil
          </button>
        </div>
      </div>

      <!-- Content Section -->
      <div class="p-6 space-y-6">
        <!-- Display Mode -->
        <div v-if="!isEditing" class="space-y-4">
          <!-- Name -->
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center flex-shrink-0">
              <User :size="20" class="text-accent-foreground" />
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-muted-foreground mb-1">Username</p>
              <p class="text-base font-medium">{{ user?.username || '-' }}</p>
            </div>
          </div>

          <!-- Nama Lengkap -->
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center flex-shrink-0">
              <User :size="20" class="text-accent-foreground" />
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-muted-foreground mb-1">Nama</p>
              <p class="text-base font-medium">{{ user?.nama_lengkap || '-' }}</p>
            </div>
          </div>

          <!-- Email -->
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center flex-shrink-0">
              <Mail :size="20" class="text-accent-foreground" />
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-muted-foreground mb-1">Email</p>
              <p class="text-base font-medium">{{ user?.email || '-' }}</p>
            </div>
          </div>

          <!-- Phone -->
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center flex-shrink-0">
              <Phone :size="20" class="text-accent-foreground" />
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-muted-foreground mb-1">Nomor Telepon</p>
              <p class="text-base font-medium">{{ user?.phone || '-' }}</p>
            </div>
          </div>

          <!-- ID Number -->
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center flex-shrink-0">
              <FileText :size="20" class="text-accent-foreground" />
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-muted-foreground mb-1">Nomor Identitas (KTP)</p>
              <p class="text-base font-medium">{{ user?.id_number || '-' }}</p>
            </div>
          </div>

          <!-- Birth Place & Date -->
          <div class="grid grid-cols-2 gap-4">
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center flex-shrink-0">
                <MapPin :size="20" class="text-accent-foreground" />
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm text-muted-foreground mb-1">Tempat Lahir</p>
                <p class="text-base font-medium">{{ user?.birth_place || '-' }}</p>
              </div>
            </div>
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center flex-shrink-0">
                <Calendar :size="20" class="text-accent-foreground" />
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm text-muted-foreground mb-1">Tanggal Lahir</p>
                <p class="text-base font-medium">{{ formatDate(user?.birth_date) }}</p>
              </div>
            </div>
          </div>

          <!-- Gender -->
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center flex-shrink-0">
              <User :size="20" class="text-accent-foreground" />
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-muted-foreground mb-1">Jenis Kelamin</p>
              <p class="text-base font-medium">{{ user?.gender === 'M' ? 'Laki-laki' : user?.gender === 'F' ? 'Perempuan' : '-' }}</p>
            </div>
          </div>

          <!-- Address -->
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center flex-shrink-0">
              <Building :size="20" class="text-accent-foreground" />
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-muted-foreground mb-1">Alamat</p>
              <p class="text-base font-medium">{{ user?.address || '-' }}</p>
            </div>
          </div>

          <!-- City & Province -->
          <div class="grid grid-cols-2 gap-4">
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center flex-shrink-0">
                <MapPin :size="20" class="text-accent-foreground" />
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm text-muted-foreground mb-1">Kota</p>
                <p class="text-base font-medium">{{ user?.city || '-' }}</p>
              </div>
            </div>
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center flex-shrink-0">
                <MapPin :size="20" class="text-accent-foreground" />
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm text-muted-foreground mb-1">Provinsi</p>
                <p class="text-base font-medium">{{ user?.province || '-' }}</p>
              </div>
            </div>
          </div>

          <!-- Postal Code -->
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center flex-shrink-0">
              <FileText :size="20" class="text-accent-foreground" />
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-muted-foreground mb-1">Kode Pos</p>
              <p class="text-base font-medium">{{ user?.postal_code || '-' }}</p>
            </div>
          </div>

          <!-- Join Date -->
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center flex-shrink-0">
              <Calendar :size="20" class="text-accent-foreground" />
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-muted-foreground mb-1">Bergabung Sejak</p>
              <p class="text-base font-medium">{{ formatDate(user?.created_at) }}</p>
            </div>
          </div>
        </div>

        <!-- Edit Mode -->
        <form v-else @submit.prevent="handleSave" class="space-y-4">
          <!-- Name -->
          <div>
            <label class="block text-sm font-medium mb-2">Username</label>
            <input
              v-model="formData.username"
              type="text"
              :disabled="!canChangeUsername()"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background disabled:bg-muted disabled:text-muted-foreground disabled:cursor-not-allowed"
            />
            <p v-if="!canChangeUsername()" class="text-xs text-muted-foreground mt-1">Username tidak dapat diubah (Role: {{ formData.username === 'Admin' || formData.username === 'Staff' ? 'Admin/Staff' : 'User' }})</p>
          </div>

          <!-- Nama Lengkap -->
          <div>
            <label class="block text-sm font-medium mb-2">Nama Lengkap</label>
            <input
              v-model="formData.nama_lengkap"
              type="text"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background"
            />
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input
              v-model="formData.email"
              type="email"
              disabled
              class="w-full px-4 py-2 border rounded-lg bg-muted text-muted-foreground cursor-not-allowed"
            />
            <p class="text-xs text-muted-foreground mt-1">Email tidak dapat diubah</p>
          </div>

          <!-- Phone -->
          <div>
            <label class="block text-sm font-medium mb-2">Nomor Telepon</label>
            <input
              v-model="formData.phone"
              type="tel"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background"
            />
          </div>

          <!-- ID Number -->
          <div>
            <label class="block text-sm font-medium mb-2">Nomor Identitas (KTP)</label>
            <input
              v-model="formData.id_number"
              type="text"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background"
            />
          </div>

          <!-- Birth Place & Birth Date -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-2">Tempat Lahir</label>
              <input
                v-model="formData.birth_place"
                type="text"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background"
              />
            </div>
            <div>
              <label class="block text-sm font-medium mb-2">Tanggal Lahir</label>
              <input
                v-model="formData.birth_date"
                type="date"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background"
              />
            </div>
          </div>

          <!-- Gender -->
          <div>
            <label class="block text-sm font-medium mb-2">Jenis Kelamin</label>
            <select
              v-model="formData.gender"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background"
            >
              <option value="">-- Pilih Jenis Kelamin --</option>
              <option value="M">Laki-laki</option>
              <option value="F">Perempuan</option>
            </select>
          </div>

          <!-- Address -->
          <div>
            <label class="block text-sm font-medium mb-2">Alamat</label>
            <textarea
              v-model="formData.address"
              rows="3"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background resize-none"
            ></textarea>
          </div>

          <!-- City & Province -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-2">Kota</label>
              <input
                v-model="formData.city"
                type="text"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background"
              />
            </div>
            <div>
              <label class="block text-sm font-medium mb-2">Provinsi</label>
              <input
                v-model="formData.province"
                type="text"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background"
              />
            </div>
          </div>

          <!-- Postal Code -->
          <div>
            <label class="block text-sm font-medium mb-2">Kode Pos</label>
            <input
              v-model="formData.postal_code"
              type="text"
              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background"
            />
          </div>

          <!-- Action Buttons -->
          <div class="flex gap-3 pt-4 border-t">
            <button type="button" @click="handleCancel" class="px-6 py-2 border rounded-lg hover:bg-accent transition-colors">
              Batal
            </button>
            <button type="submit" class="px-6 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors">
              Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Logout Button -->
    <button
      @click="handleLogout"
      class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-destructive text-destructive-foreground rounded-lg hover:bg-destructive/90 transition-colors font-medium"
    >
      <LogOut :size="20" />
      Keluar
    </button>
  </div>
</template>
