<template>
  <div class="relative min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 flex items-center justify-center px-4 py-8">
    <button
      type="button"
      class="absolute left-4 top-4 inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700 shadow-sm transition hover:bg-slate-100 dark:border-white/10 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800"
      aria-label="Kembali"
      @click="goBack"
    >
      &lt;
    </button>
    <div class="w-full max-w-md">
      <!-- Logo/Title -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Peminjaman Alat</h1>
        <p class="text-slate-600 dark:text-slate-400">Buat akun baru</p>
      </div>

      <!-- Registration Form -->
      <div class="bg-cyan-50 dark:bg-cyan-500/10 p-8 rounded-[2rem] shadow-2xl border border-cyan-200 dark:border-cyan-500/20">
        <form @submit.prevent="handleRegister" class="space-y-6">
          <!-- Name Field -->
          <div>
            <label for="name" class="block text-sm font-medium text-slate-200 mb-2">
              Nama Lengkap
            </label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              class="w-full px-3 py-2 border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent placeholder-slate-500 dark:placeholder-slate-400"
              placeholder="Masukkan nama lengkap"
            >
          </div>

          <!-- Email Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-slate-200 mb-2">
              Email
            </label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              required
              class="w-full px-3 py-2 border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent placeholder-slate-500 dark:placeholder-slate-400"
              placeholder="Masukkan email"
            >
          </div>

          <!-- Phone Field -->
          <div>
            <label for="phone" class="block text-sm font-medium text-slate-200 mb-2">
              Nomor Telepon
            </label>
            <input
              id="phone"
              v-model="form.phone"
              type="tel"
              required
              class="w-full px-3 py-2 border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent placeholder-slate-500 dark:placeholder-slate-400"
              placeholder="Masukkan nomor telepon"
            >
          </div>

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-medium text-slate-200 mb-2">
              Password
            </label>
            <div class="relative">
              <input
                id="password"
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                required
                class="w-full px-3 py-2 pr-12 border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent placeholder-slate-500 dark:placeholder-slate-400"
                placeholder="Masukkan password"
              >
              <button
                type="button"
                class="absolute inset-y-0 right-0 flex items-center justify-center px-4 text-slate-500 transition hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"
                :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'"
                :title="showPassword ? 'Sembunyikan password' : 'Tampilkan password'"
                @click="showPassword = !showPassword"
              >
                <svg v-if="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7 .39-1.244 1.037-2.397 1.885-3.36m2.02-1.825A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.08 10.08 0 01-4.138 5.185M15 12a3 3 0 11-4.243-2.757M3 3l18 18" />
                </svg>
                <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12c1.272-4.057 5.061-7 9.75-7 4.689 0 8.478 2.943 9.75 7-1.272 4.057-5.061 7-9.75 7-4.689 0-8.478-2.943-9.75-7z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Confirm Password Field -->
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-200 mb-2">
              Konfirmasi Password
            </label>
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              type="password"
              required
              class="w-full px-3 py-2 border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent placeholder-slate-500 dark:placeholder-slate-400"
              placeholder="Konfirmasi password"
            >
          </div>

          <!-- Password Match Validation -->
          <div v-if="form.password && form.password_confirmation && form.password !== form.password_confirmation" class="text-red-600 text-sm">
            Password tidak cocok
          </div>

          <!-- Terms Agreement -->
          <div class="flex items-start">
            <input
              id="terms"
              v-model="form.terms"
              type="checkbox"
              required
              class="h-4 w-4 text-cyan-500 focus:ring-cyan-500 border-slate-600 rounded mt-1"
            >
            <label for="terms" class="ml-2 block text-sm text-slate-200">
              Saya setuju dengan <a href="#" class="text-blue-600 hover:text-blue-800">syarat dan ketentuan</a>
            </label>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="loading || !form.terms || form.password !== form.password_confirmation"
            class="w-full bg-cyan-500 text-slate-950 py-2 px-4 rounded-full hover:bg-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition duration-300"
          >
            <span v-if="loading">Mendaftarkan...</span>
            <span v-else>Daftar</span>
          </button>
        </form>

        <div class="mt-4 space-y-3">
          <div class="rounded-3xl border border-white/10 bg-slate-900/80 px-4 py-3 text-sm text-slate-200">
            Form registrasi ini akan menyimpan input Anda selama 2 jam jika halaman di-refresh atau ditutup sebelum selesai.
          </div>
          <div v-if="restoredFromStorage" class="rounded-3xl border border-cyan-500/20 bg-cyan-500/10 px-4 py-3 text-sm text-cyan-100">
            Data Anda dipulihkan dari sesi sebelumnya. Silakan lanjutkan registrasi tanpa mengisi ulang semua field.
          </div>
        </div>

        <!-- Login Link -->
        <div class="mt-6 text-center">
          <p class="text-sm text-slate-400">
            Sudah punya akun?
            <router-link to="/login" class="text-cyan-300 hover:text-cyan-100 font-medium">
              Masuk sekarang
            </router-link>
          </p>
        </div>
      </div>

      <!-- Success/Error Messages -->
      <div v-if="success" class="mt-4 p-4 bg-emerald-900/80 border border-emerald-500/30 text-emerald-100 rounded-2xl">
        {{ success }}
      </div>
      <div v-if="error" class="mt-4 p-4 bg-red-900/80 border border-red-500/30 text-red-200 rounded-2xl">
        {{ error }}
      </div>
    </div>
  </div>
</template>

<script>
import { apiRequest } from '../lib/api'

const REGISTRATION_STORAGE_KEY = 'trustequip_register_form'
const REGISTRATION_TTL_MS = 2 * 60 * 60 * 1000 // 2 hours

export default {
  name: 'Registrasi',
  data() {
      return {
        form: {
          name: '',
          email: '',
          phone: '',
          password: '',
          password_confirmation: '',
          terms: false
        },
        loading: false,
        error: null,
        success: null,
        restoredFromStorage: false,
        showPassword: false
      }
    },
  mounted() {
    this.loadSavedForm()
  },
  watch: {
    form: {
      handler() {
        this.saveForm()
      },
      deep: true
    }
  },
  methods: {
    goBack() {
      this.$router.push('/')
    },
    getSavedForm() {
      try {
        const raw = localStorage.getItem(REGISTRATION_STORAGE_KEY)
        if (!raw) return null
        const data = JSON.parse(raw)
        if (data && data.savedAt && Date.now() - data.savedAt < REGISTRATION_TTL_MS) {
          return data.form
        }
      } catch (e) {
        console.warn('Unable to load saved registration form', e)
      }
      return null
    },
    saveForm() {
      try {
        const payload = {
          savedAt: Date.now(),
          form: this.form
        }
        localStorage.setItem(REGISTRATION_STORAGE_KEY, JSON.stringify(payload))
      } catch (e) {
        console.warn('Unable to save registration form', e)
      }
    },
    clearSavedForm() {
      localStorage.removeItem(REGISTRATION_STORAGE_KEY)
    },
    loadSavedForm() {
      const saved = this.getSavedForm()
      if (saved) {
        this.form = {
          name: saved.name || '',
          email: saved.email || '',
          phone: saved.phone || '',
          password: saved.password || '',
          password_confirmation: saved.password_confirmation || '',
          terms: saved.terms || false
        }
        this.restoredFromStorage = true
      }
    },
    resetForm() {
      this.form = {
        name: '',
        email: '',
        phone: '',
        password: '',
        password_confirmation: '',
        terms: false
      }
      this.showPassword = false
      this.clearSavedForm()
    },
    async handleRegister() {
      this.loading = true
      this.error = null
      this.success = null

      try {
        // Validate password match
        if (this.form.password !== this.form.password_confirmation) {
          throw new Error('Password tidak cocok')
        }

        if (!this.form.terms) {
          throw new Error('Anda harus menyetujui syarat dan ketentuan')
        }

        await apiRequest('/api/auth/register', {
          method: 'POST',
          body: {
            nama: this.form.name,
            email: this.form.email,
            telepon: this.form.phone,
            password: this.form.password,
          },
        })

        this.success = 'Pendaftaran berhasil! Silakan login menggunakan akun yang baru dibuat.'
        this.resetForm()
      } catch (error) {
        this.error = error.message || 'Terjadi kesalahan saat pendaftaran'
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
