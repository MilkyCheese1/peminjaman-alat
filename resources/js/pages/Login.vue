<template>
  <div class="auth-container login-container">
    <!-- Background Decoration -->
    <div class="auth-background"></div>

    <!-- Login Form -->
    <div class="auth-card">
      <div class="auth-header">
        <div class="auth-logo">🎓</div>
        <h1>TrustEquip</h1>
        <p>Masuk ke akun Anda</p>
      </div>

      <form @submit.prevent="handleLogin" class="auth-form">
        <!-- Email Input -->
        <div class="form-group">
          <label for="email">Email</label>
          <div class="input-wrapper">
            <span class="input-icon">📧</span>
            <input
              id="email"
              v-model="form.email"
              type="email"
              placeholder="masukkan@email.com"
              required
              @focus="focusedInput = 'email'"
              @blur="focusedInput = null"
            />
          </div>
          <span v-if="errors.email" class="error-message">{{ errors.email }}</span>
        </div>

        <!-- Password Input -->
        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrapper">
            <span class="input-icon">🔒</span>
            <input
              id="password"
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              placeholder="••••••••"
              required
              @focus="focusedInput = 'password'"
              @blur="focusedInput = null"
            />
            <button
              type="button"
              class="password-toggle"
              @click="showPassword = !showPassword"
            >
              {{ showPassword ? '👁️' : '👁️‍🗨️' }}
            </button>
          </div>
          <span v-if="errors.password" class="error-message">{{ errors.password }}</span>
          <div v-if="form.password" class="password-info">
            <div class="password-length">
              <span :class="{ valid: form.password.length >= 8, warning: form.password.length < 8 }">
                {{ form.password.length }}/8-12
              </span>
            </div>
          </div>
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="form-footer">
          <label class="checkbox-label">
            <input v-model="form.rememberMe" type="checkbox" />
            Ingat saya
          </label>
          <router-link to="/forgot-password" class="forgot-link">Lupa password?</router-link>
        </div>

        <!-- Login Button -->
        <button type="submit" class="auth-button primary" :disabled="isLoading">
          <span v-if="!isLoading">Masuk</span>
          <span v-else>Memproses...</span>
        </button>

        <!-- Error Message -->
        <div v-if="errors.general" class="error-banner">
          {{ errors.general }}
        </div>

        <!-- Success Message -->
        <div v-if="successMessage" class="success-banner">
          {{ successMessage }}
        </div>
      </form>

      <!-- Register Link -->
      <p class="auth-footer">
        Belum punya akun?
        <router-link to="/register" class="auth-link">Daftar sekarang</router-link>
      </p>
    </div>

    <!-- Back to Landing -->
    <router-link to="/" class="back-button">
      ← Kembali ke halaman utama
    </router-link>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { validateUser } from '../data/dummyUsers.js'

const router = useRouter()
const route = useRoute()
const isLoading = ref(false)
const showPassword = ref(false)
const focusedInput = ref(null)
const successMessage = ref('')

const form = reactive({
  email: '',
  password: '',
  rememberMe: false
})

const errors = reactive({
  email: '',
  password: '',
  general: ''
})

// Check for email query parameter on mount
onMounted(() => {
  if (route.query.email) {
    form.email = decodeURIComponent(route.query.email)
  }
})

const validateForm = () => {
  errors.email = ''
  errors.password = ''
  errors.general = ''

  if (!form.email) {
    errors.email = 'Email tidak boleh kosong'
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
    errors.email = 'Format email tidak valid'
  }

  if (!form.password) {
    errors.password = 'Password tidak boleh kosong'
  } else if (form.password.length < 8) {
    errors.password = 'Password minimal 8 karakter'
  } else if (form.password.length > 12) {
    errors.password = 'Password maksimal 12 karakter'
  }

  return !errors.email && !errors.password
}

const handleLogin = async () => {
  if (!validateForm()) return

  isLoading.value = true
  
  try {
    // Simulasi API call
    await new Promise(resolve => setTimeout(resolve, 1500))
    
    // Validasi user dari dummy users
    const validation = validateUser(form.email, form.password)
    
    if (!validation.success) {
      errors.general = validation.message
      isLoading.value = false
      return
    }

    const user = validation.user
    successMessage.value = `Selamat datang, ${user.fullname}!`
    
    // Simpan ke localStorage dengan informasi lengkap
    localStorage.setItem('user', JSON.stringify({
      id: user.id,
      fullname: user.fullname,
      email: user.email,
      role: user.role,
      phone: user.phone,
      school: user.school,
      address: user.address,
      avatar: user.avatar,
      status: user.status,
      joinDate: user.joinDate,
      rememberMe: form.rememberMe
    }))
    
    // Redirect ke dashboard setelah 1 detik
    setTimeout(() => {
      router.push('/dashboard')
    }, 1000)
  } catch (error) {
    errors.general = 'Login gagal. Coba lagi nanti.'
  } finally {
    isLoading.value = false
  }
}
</script>

<style scoped>
.auth-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  position: relative;
  background: linear-gradient(135deg, #f8f9fa 0%, #f0f2f5 100%);
  overflow: hidden;
}

.auth-background {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: 
    radial-gradient(circle at 20% 50%, rgba(11, 114, 133, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(255, 159, 28, 0.05) 0%, transparent 50%);
  pointer-events: none;
}

.back-button {
  position: fixed;
  top: 20px;
  left: 20px;
  color: #0B7285;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s ease;
  z-index: 100;
}

.back-button:hover {
  color: #089FB3;
  transform: translateX(-5px);
}

.auth-card {
  background: white;
  border-radius: 16px;
  padding: 40px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
  width: 100%;
  max-width: 420px;
  position: relative;
  z-index: 10;
  animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.auth-header {
  text-align: center;
  margin-bottom: 30px;
}

.auth-logo {
  font-size: 3rem;
  margin-bottom: 10px;
}

.auth-header h1 {
  font-size: 1.8rem;
  color: #0B7285;
  margin-bottom: 5px;
  font-weight: 700;
}

.auth-header p {
  color: #666;
  font-size: 0.95rem;
}

.auth-form {
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  color: #1a1a2e;
  font-weight: 600;
  margin-bottom: 8px;
  font-size: 0.95rem;
}

.input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.input-icon {
  position: absolute;
  left: 12px;
  font-size: 1.2rem;
  pointer-events: none;
}

.input-wrapper input {
  width: 100%;
  padding: 12px 12px 12px 45px;
  border: 2px solid #e5e5e5;
  border-radius: 8px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: #f8f9fa;
}

.input-wrapper input:focus {
  outline: none;
  border-color: #0B7285;
  background: white;
  box-shadow: 0 0 0 3px rgba(11, 114, 133, 0.1);
}

.password-toggle {
  position: absolute;
  right: 12px;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.1rem;
  padding: 0;
  transition: opacity 0.3s ease;
}

.password-toggle:hover {
  opacity: 0.7;
}

.error-message {
  display: block;
  color: #dc3545;
  font-size: 0.85rem;
  margin-top: 5px;
  animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-5px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Password Info Styling */
.password-info {
  margin-top: 8px;
  font-size: 0.85rem;
}

.password-length {
  display: flex;
  align-items: center;
  gap: 5px;
}

.password-length span {
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 4px;
  transition: all 0.3s ease;
}

.password-length span.valid {
  color: #28a745;
  background: rgba(40, 167, 69, 0.1);
}

.password-length span.warning {
  color: #ffc107;
  background: rgba(255, 193, 7, 0.1);
}

.form-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  font-size: 0.9rem;
}

.checkbox-label {
  display: flex;
  align-items: center;
  cursor: pointer;
  color: #666;
}

.checkbox-label input {
  margin-right: 6px;
  cursor: pointer;
}

.forgot-link {
  color: #0B7285;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s ease;
}

.forgot-link:hover {
  color: #089FB3;
  text-decoration: underline;
}

.auth-button {
  width: 100%;
  padding: 12px;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.auth-button.primary {
  background: linear-gradient(135deg, #0B7285 0%, #089FB3 100%);
  color: white;
}

.auth-button.primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(11, 114, 133, 0.3);
}

.auth-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.error-banner,
.success-banner {
  text-align: center;
  color: #666;
  font-size: 0.95rem;
  margin: 0;
}

.auth-link {
  color: #FF9F1C;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease;
}

.auth-link:hover {
  color: #FFBF42;
  text-decoration: underline;
}

@media (max-width: 480px) {
  .auth-card {
    padding: 30px 20px;
  }

  .auth-header h1 {
    font-size: 1.5rem;
  }

  .form-footer {
    flex-direction: column;
    gap: 10px;
    align-items: flex-start;
  }
}
</style>
