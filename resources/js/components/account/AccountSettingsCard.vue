<template>
  <div class="space-y-6">
    <div class="app-card app-card--cyan p-6">
      <h2 id="data-diri" class="mb-6 text-xl font-bold text-slate-900 dark:text-white scroll-mt-28">
        Data Diri
      </h2>

      <form class="space-y-6" @submit.prevent="saveProfile">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Lengkap</label>
            <input
              v-model.trim="profileForm.nama"
              type="text"
              readonly
              class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-cyan-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
            >
          </div>

          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Email</label>
            <input
              v-model="profileForm.email"
              type="email"
              readonly
              class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-cyan-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
            >
          </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Status Akun</label>
            <input
              v-model="profileForm.status"
              type="text"
              readonly
              class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-cyan-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
            >
          </div>

          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Nomor Telepon</label>
            <input
              v-model.trim="profileForm.telepon"
              type="tel"
              class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-cyan-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
              placeholder="+62 ..."
            >
          </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">NIK / Nomor Identitas</label>
            <input
              v-model.trim="profileForm.nik"
              type="text"
              class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-cyan-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
              placeholder="Opsional"
            >
          </div>

          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Jenis Kelamin</label>
            <select
              v-model="profileForm.jenis_kelamin"
              class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-cyan-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
            >
              <option value="">Pilih jenis kelamin</option>
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
              <option value="Lainnya">Lainnya</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Tempat Lahir</label>
            <input
              v-model.trim="profileForm.tempat_lahir"
              type="text"
              class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-cyan-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
              placeholder="Contoh: Jakarta"
            >
          </div>

          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Lahir</label>
            <input
              v-model="profileForm.tanggal_lahir"
              type="date"
              class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-cyan-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
            >
          </div>
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Alamat</label>
          <textarea
            v-model.trim="profileForm.alamat"
            rows="4"
            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-cyan-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
            placeholder="Masukkan alamat lengkap"
          ></textarea>
        </div>

        <div class="flex gap-3 pt-2">
          <button
            type="submit"
            :disabled="profileSaving"
            class="flex-1 rounded-full bg-cyan-500 px-4 py-3 font-semibold text-slate-950 transition hover:bg-cyan-400 disabled:cursor-not-allowed disabled:opacity-60"
          >
            {{ profileSaving ? 'Menyimpan...' : 'Simpan Perubahan' }}
          </button>
          <button
            type="button"
            class="flex-1 rounded-full bg-slate-200 px-4 py-3 font-semibold text-slate-800 transition hover:bg-slate-300 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
            @click="resetProfile"
          >
            Batal
          </button>
        </div>

        <p v-if="profileMessage" class="text-sm" :class="profileMessageType === 'error' ? 'text-rose-500' : 'text-emerald-500'">
          {{ profileMessage }}
        </p>
      </form>
    </div>

    <div id="keamanan-akun" class="app-card app-card--rose p-6 scroll-mt-28">
      <h3 class="text-base font-bold text-slate-900 dark:text-white">Keamanan Akun</h3>
      <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
        Ubah password akun untuk meningkatkan keamanan.
      </p>

      <form class="mt-5 space-y-4" @submit.prevent="changePassword">
        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Password Saat Ini</label>
          <input
            v-model="passwordForm.current_password"
            :type="showCurrentPassword ? 'text' : 'password'"
            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 pr-12 text-slate-900 focus:border-cyan-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
            placeholder="••••••••"
          >
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Password Baru</label>
          <input
            v-model="passwordForm.password"
            :type="showNewPassword ? 'text' : 'password'"
            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 pr-12 text-slate-900 focus:border-cyan-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
            placeholder="Minimal 8 karakter"
          >
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Konfirmasi Password Baru</label>
          <input
            v-model="passwordForm.password_confirmation"
            :type="showConfirmPassword ? 'text' : 'password'"
            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 pr-12 text-slate-900 focus:border-cyan-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
            placeholder="Ulangi password baru"
          >
        </div>

        <div class="flex gap-3 pt-2">
          <button
            type="submit"
            :disabled="passwordSaving"
            class="flex-1 rounded-full bg-cyan-500 px-4 py-3 font-semibold text-slate-950 transition hover:bg-cyan-400 disabled:cursor-not-allowed disabled:opacity-60"
          >
            {{ passwordSaving ? 'Menyimpan...' : 'Simpan Password' }}
          </button>
          <button
            type="button"
            class="rounded-full bg-slate-200 px-4 py-3 font-semibold text-slate-800 transition hover:bg-slate-300 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
            @click="toggleVisibility"
          >
            {{ showAnyPassword ? 'Sembunyikan' : 'Tampilkan' }}
          </button>
        </div>

        <p v-if="passwordMessage" class="text-sm" :class="passwordMessageType === 'error' ? 'text-rose-500' : 'text-emerald-500'">
          {{ passwordMessage }}
        </p>
      </form>
    </div>
  </div>
</template>

<script>
import { apiRequest } from '../../lib/api'
import { getAuthSession, setAuthSession } from '../../auth/session'

export default {
  name: 'AccountSettingsCard',
  data() {
    return {
      profileSaving: false,
      passwordSaving: false,
      profileMessage: '',
      profileMessageType: 'success',
      passwordMessage: '',
      passwordMessageType: 'success',
      showCurrentPassword: false,
      showNewPassword: false,
      showConfirmPassword: false,
      profileForm: {
        id: null,
        nama: '',
        nik: '',
        email: '',
        telepon: '',
        jenis_kelamin: '',
        tempat_lahir: '',
        tanggal_lahir: '',
        alamat: '',
        status: 'Aktif',
      },
      originalProfile: null,
      passwordForm: {
        current_password: '',
        password: '',
        password_confirmation: '',
      },
    }
  },
  computed: {
    showAnyPassword() {
      return this.showCurrentPassword || this.showNewPassword || this.showConfirmPassword
    },
  },
  async mounted() {
    await this.loadProfile()
  },
  methods: {
    async loadProfile() {
      const session = getAuthSession()
      if (!session?.id) {
        return
      }

      try {
        const users = await apiRequest('/api/users')
        const match = Array.isArray(users)
          ? users.find((item) => Number(item.id) === Number(session.id))
          : null

        const profile = match || session

        this.profileForm = {
          id: profile.id || session.id,
          nama: profile.nama || '',
          nik: profile.nik || '',
          email: profile.email || '',
          telepon: profile.telepon || '',
          jenis_kelamin: profile.jenis_kelamin || '',
          tempat_lahir: profile.tempat_lahir || '',
          tanggal_lahir: profile.tanggal_lahir || '',
          alamat: profile.alamat || '',
          status: profile.status || 'Aktif',
        }
        this.originalProfile = { ...this.profileForm }

        if (match) {
          setAuthSession({ ...session, ...match })
        }
      } catch (error) {
        this.profileForm = {
          id: session.id,
          nama: session.nama || '',
          nik: session.nik || '',
          email: session.email || '',
          telepon: session.telepon || '',
          jenis_kelamin: session.jenis_kelamin || '',
          tempat_lahir: session.tempat_lahir || '',
          tanggal_lahir: session.tanggal_lahir || '',
          alamat: session.alamat || '',
          status: session.status || 'Aktif',
        }
        this.originalProfile = { ...this.profileForm }
      }
    },
    resetProfile() {
      if (this.originalProfile) {
        this.profileForm = { ...this.originalProfile }
      }
      this.profileMessage = ''
    },
    async saveProfile() {
      const session = getAuthSession()
      if (!session?.id) {
        this.profileMessageType = 'error'
        this.profileMessage = 'Sesi akun tidak ditemukan. Silakan login ulang.'
        return
      }

      this.profileSaving = true
      this.profileMessage = ''

      try {
        const updated = await apiRequest(`/api/users/${session.id}`, {
          method: 'PUT',
          body: {
            nama: this.profileForm.nama,
            nik: this.profileForm.nik,
            email: this.profileForm.email,
            role: session.role,
            status: this.profileForm.status,
            telepon: this.profileForm.telepon,
            jenis_kelamin: this.profileForm.jenis_kelamin,
            tempat_lahir: this.profileForm.tempat_lahir,
            tanggal_lahir: this.profileForm.tanggal_lahir,
            alamat: this.profileForm.alamat,
          },
        })

        this.profileForm = {
          id: updated.id,
          nama: updated.nama || this.profileForm.nama,
          nik: updated.nik || this.profileForm.nik,
          email: updated.email || this.profileForm.email,
          telepon: updated.telepon || this.profileForm.telepon,
          jenis_kelamin: updated.jenis_kelamin || this.profileForm.jenis_kelamin,
          tempat_lahir: updated.tempat_lahir || this.profileForm.tempat_lahir,
          tanggal_lahir: updated.tanggal_lahir || this.profileForm.tanggal_lahir,
          alamat: updated.alamat || this.profileForm.alamat,
          status: updated.status || this.profileForm.status,
        }
        this.originalProfile = { ...this.profileForm }
        setAuthSession({ ...session, ...updated })
        window.dispatchEvent(new CustomEvent('account-profile-updated', { detail: updated }))
        this.profileMessageType = 'success'
        this.profileMessage = 'Perubahan profil berhasil disimpan.'
      } catch (error) {
        this.profileMessageType = 'error'
        this.profileMessage = error?.message || 'Gagal menyimpan perubahan profil.'
      } finally {
        this.profileSaving = false
      }
    },
    toggleVisibility() {
      const next = !this.showAnyPassword
      this.showCurrentPassword = next
      this.showNewPassword = next
      this.showConfirmPassword = next
    },
    async changePassword() {
      const session = getAuthSession()
      if (!session?.id) {
        this.passwordMessageType = 'error'
        this.passwordMessage = 'Sesi akun tidak ditemukan. Silakan login ulang.'
        return
      }

      if (!this.passwordForm.current_password || !this.passwordForm.password || !this.passwordForm.password_confirmation) {
        this.passwordMessageType = 'error'
        this.passwordMessage = 'Lengkapi semua field password.'
        return
      }

      if (this.passwordForm.password.length < 8) {
        this.passwordMessageType = 'error'
        this.passwordMessage = 'Password baru minimal 8 karakter.'
        return
      }

      if (this.passwordForm.password !== this.passwordForm.password_confirmation) {
        this.passwordMessageType = 'error'
        this.passwordMessage = 'Konfirmasi password tidak sama.'
        return
      }

      this.passwordSaving = true
      this.passwordMessage = ''

      try {
        await apiRequest(`/api/users/${session.id}/password`, {
          method: 'PUT',
          body: this.passwordForm,
        })

        this.passwordForm = {
          current_password: '',
          password: '',
          password_confirmation: '',
        }
        this.passwordMessageType = 'success'
        this.passwordMessage = 'Password berhasil diperbarui.'
      } catch (error) {
        this.passwordMessageType = 'error'
        this.passwordMessage = error?.message || 'Gagal memperbarui password.'
      } finally {
        this.passwordSaving = false
      }
    },
  },
}
</script>
