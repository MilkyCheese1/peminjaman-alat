<template>
  <div class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md">
      <!-- Logo/Title -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Peminjaman Alat</h1>
        <p class="text-slate-600 dark:text-slate-400">Masuk ke akun Anda</p>
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
            <input
              id="password"
              v-model="form.password"
              type="password"
              required
              class="w-full px-3 py-2 border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent placeholder-slate-500 dark:placeholder-slate-400"
              placeholder="Masukkan password"
            >
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

          <!-- Forgot Password Link -->
          <div class="text-right">
            <a href="#" class="text-sm text-cyan-500 hover:text-cyan-700 dark:text-cyan-300 dark:hover:text-cyan-200">
              Lupa password?
            </a>
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
export default {
  name: 'Login',
  data() {
    return {
      form: {
        email: '',
        password: '',
        remember: false
      },
      loading: false,
      error: null
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
    },
    async handleLogin() {
      this.loading = true
      this.error = null

      try {
        // Simulate API call
        await new Promise(resolve => setTimeout(resolve, 1000))

        // Mock validation
        if (this.form.email && this.form.password) {
          console.log('Login successful:', this.form)
          alert('Login berhasil! (Mock)')
          // Redirect to dashboard would happen here
        } else {
          throw new Error('Email dan password harus diisi')
        }
      } catch (error) {
        this.error = error.message || 'Terjadi kesalahan saat login'
        this.resetForm()
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
