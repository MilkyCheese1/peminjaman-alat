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
      <div class="text-center mb-8 flex flex-col items-center gap-4">
        <BrandLogo brand-name="TrustEquip.id" subtitle="Masuk ke akun Anda" layout="stacked" size="lg" />
      </div>

      <!-- Login Form -->
      <div class="bg-cyan-50 dark:bg-cyan-500/10 p-8 rounded-[2rem] shadow-2xl border border-cyan-200 dark:border-cyan-500/20">
        <form @submit.prevent="handleLogin" class="space-y-6">
          <!-- Email/Username Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-slate-200 mb-2">
              Email atau Username
            </label>
            <input
              id="email"
              v-model="form.email"
              type="text"
              required
              class="w-full px-3 py-2 border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent placeholder-slate-500 dark:placeholder-slate-400"
              placeholder="Masukkan email atau username"
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

          <!-- Remember Me -->
          <div class="flex items-center">
            <input
              id="remember"
              v-model="form.remember"
              type="checkbox"
              class="h-4 w-4 text-cyan-500 focus:ring-cyan-500 border-slate-600 rounded"
            >
            <label for="remember" class="ml-2 block text-sm text-slate-200">
              Ingat saya
            </label>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-cyan-500 text-slate-950 py-2 px-4 rounded-full hover:bg-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition duration-300"
          >
            <span v-if="loading">Sedang Masuk...</span>
            <span v-else>Masuk</span>
          </button>
        </form>

        <!-- Register Link -->
        <div class="mt-6 text-center">
          <p class="text-sm text-slate-400">
            Belum punya akun?
            <router-link to="/register" class="text-cyan-300 hover:text-cyan-100 font-medium">
              Daftar sekarang
            </router-link>
          </p>
        </div>
      </div>

      <!-- Error Message -->
      <div v-if="error" class="mt-4 p-4 bg-red-900/80 border border-red-500/30 text-red-200 rounded-2xl">
        {{ error }}
      </div>
    </div>
  </div>
</template>

<script>
import { apiRequest } from '../lib/api'
import { roleRedirectPath, setAuthSession } from '../auth/session'
import BrandLogo from '../components/BrandLogo.vue'

export default {
  name: 'Login',
  components: {
    BrandLogo,
  },
  data() {
      return {
        form: {
          email: '',
          password: '',
          remember: false
        },
        loading: false,
        error: null,
        showPassword: false
      }
    },
  mounted() {
    this.resetForm()
  },
  methods: {
    resetForm() {
      this.form = {
        email: '',
        password: '',
        remember: false
      }
      this.showPassword = false
    },
    async handleLogin() {
      this.loading = true
      this.error = null

      try {
        const session = await apiRequest('/api/auth/login', {
          method: 'POST',
          body: { email: this.form.email, password: this.form.password },
        })

        setAuthSession(session)

        const redirect = this.$route?.query?.redirect
        if (typeof redirect === 'string' && redirect.startsWith('/')) {
          this.$router.replace(redirect)
        } else {
          this.$router.replace(roleRedirectPath(session?.role))
        }
      } catch (error) {
        this.error = error.message || 'Terjadi kesalahan saat login'
        this.form.password = ''
      } finally {
        this.loading = false
      }
    },
    goBack() {
      this.$router.push('/')
    }
  }
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
