<template>
  <div class="auth-container register-container">
    <!-- Background Decoration -->
    <div class="auth-background"></div>

    <!-- Register Form -->
    <div class="auth-card">
      <div class="auth-header">
        <div class="auth-logo">🎓</div>
        <h1>TrustEquip</h1>
        <p>Buat akun baru Anda</p>
      </div>

      <form @submit.prevent="handleRegister" class="auth-form">
        <!-- Full Name Input -->
        <div class="form-group">
          <label for="fullname">Nama Lengkap</label>
          <div class="input-wrapper">
            <span class="input-icon">👤</span>
            <input
              id="fullname"
              v-model="form.fullName"
              type="text"
              placeholder="Nama Anda"
              required
            />
          </div>
          <span v-if="errors.fullName" class="error-message">{{ errors.fullName }}</span>
        </div>

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
          <div class="password-strength">
            <div class="strength-bar" :class="passwordStrength.level"></div>
            <span class="strength-text">{{ passwordStrength.text }}</span>
          </div>
        </div>

        <!-- Confirm Password Input -->
        <div class="form-group">
          <label for="confirm-password">Konfirmasi Password</label>
          <div class="input-wrapper">
            <span class="input-icon">🔐</span>
            <input
              id="confirm-password"
              v-model="form.confirmPassword"
              :type="showConfirmPassword ? 'text' : 'password'"
              placeholder="••••••••"
              required
            />
            <button
              type="button"
              class="password-toggle"
              @click="showConfirmPassword = !showConfirmPassword"
            >
              {{ showConfirmPassword ? '👁️' : '👁️‍🗨️' }}
            </button>
          </div>
          <span v-if="errors.confirmPassword" class="error-message">{{ errors.confirmPassword }}</span>
        </div>

        <!-- Phone Number Input -->
        <div class="form-group">
          <label for="phone">Nomor Telepon</label>
          <div class="input-wrapper">
            <span class="input-icon">📱</span>
            <input
              id="phone"
              v-model="form.phone"
              type="tel"
              placeholder="+62 812-3456-7890"
            />
          </div>
        </div>

        <!-- School/Institution -->
        <div class="form-group">
          <label for="school">Sekolah / Institusi</label>
          <div class="input-wrapper">
            <span class="input-icon">🏫</span>
            <input
              id="school"
              v-model="form.school"
              type="text"
              placeholder="Nama sekolah Anda"
              required
            />
          </div>
        </div>

        <!-- Terms & Conditions -->
        <div class="form-group checkbox-group">
          <label class="checkbox-label">
            <input v-model="form.agreeTerms" type="checkbox" required />
            Saya setuju dengan <router-link to="/terms" class="link">Syarat & Ketentuan</router-link>
          </label>
          <span v-if="errors.agreeTerms" class="error-message">{{ errors.agreeTerms }}</span>
        </div>

        <!-- Register Button -->
        <button type="submit" class="auth-button primary" :disabled="isLoading">
          <span v-if="!isLoading">Daftar</span>
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

      <!-- Login Link -->
      <p class="auth-footer">
        Sudah punya akun?
        <router-link to="/login" class="auth-link">Masuk di sini</router-link>
      </p>
    </div>

    <!-- Back to Landing -->
    <router-link to="/" class="back-button">
      ← Kembali ke halaman utama
    </router-link>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const isLoading = ref(false)
const showPassword = ref(false)
const showConfirmPassword = ref(false)
const successMessage = ref('')

const form = reactive({
  fullName: '',
  email: '',
  password: '',
  confirmPassword: '',
  phone: '',
  school: '',
  agreeTerms: false
})

const errors = reactive({
  fullName: '',
  email: '',
  password: '',
  confirmPassword: '',
  agreeTerms: '',
  general: ''
})

const passwordStrength = computed(() => {
  let strength = 'weak'
  let text = 'Lemah'
  
  if (form.password.length >= 8) {
    if (/[a-z]/.test(form.password) && /[A-Z]/.test(form.password) && /\d/.test(form.password)) {
      strength = 'strong'
      text = 'Kuat'
    } else if (/[a-z]/.test(form.password) && /\d/.test(form.password)) {
      strength = 'medium'
      text = 'Sedang'
    }
  }
  
  return { level: strength, text }
})

const validateForm = () => {
  errors.fullName = ''
  errors.email = ''
  errors.password = ''
  errors.confirmPassword = ''
  errors.agreeTerms = ''
  errors.general = ''

  if (!form.fullName) {
    errors.fullName = 'Nama lengkap tidak boleh kosong'
  }

  if (!form.email) {
    errors.email = 'Email tidak boleh kosong'
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
    errors.email = 'Format email tidak valid'
  }

  if (!form.password) {
    errors.password = 'Password tidak boleh kosong'
  } else if (form.password.length < 6) {
    errors.password = 'Password minimal 6 karakter'
  }

  if (!form.confirmPassword) {
    errors.confirmPassword = 'Konfirmasi password tidak boleh kosong'
  } else if (form.password !== form.confirmPassword) {
    errors.confirmPassword = 'Password tidak cocok'
  }

  if (!form.agreeTerms) {
    errors.agreeTerms = 'Anda harus menyetujui Syarat & Ketentuan'
  }

  return !Object.values(errors).some(error => error !== '')
}

const handleRegister = async () => {
  if (!validateForm()) return

  isLoading.value = true
  
  try {
    // Simulasi API call
    await new Promise(resolve => setTimeout(resolve, 2000))
    
    successMessage.value = 'Registrasi berhasil! Silakan login untuk melanjutkan...'
    
    // Simpan ke localStorage
    localStorage.setItem('newUser', JSON.stringify({
      fullName: form.fullName,
      email: form.email,
      phone: form.phone,
      school: form.school
    }))
    
    // Redirect ke login setelah 2 detik
    setTimeout(() => {
      router.push('/login')
    }, 2000)
  } catch (error) {
    errors.general = 'Registrasi gagal. Coba lagi nanti.'
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
  max-width: 480px;
  position: relative;
  z-index: 10;
  animation: slideUp 0.6s ease-out;
  max-height: 90vh;
  overflow-y: auto;
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

.password-strength {
  margin-top: 8px;
}

.strength-bar {
  height: 4px;
  background: #e5e5e5;
  border-radius: 2px;
  overflow: hidden;
  margin-bottom: 4px;
}

.strength-bar::after {
  content: '';
  display: block;
  height: 100%;
  width: 0%;
  transition: width 0.3s ease, background-color 0.3s ease;
}

.strength-bar.weak::after {
  width: 33%;
  background: #dc3545;
}

.strength-bar.medium::after {
  width: 66%;
  background: #ffc107;
}

.strength-bar.strong::after {
  width: 100%;
  background: #28a745;
}

.strength-text {
  font-size: 0.8rem;
  color: #666;
}

.checkbox-group {
  margin-bottom: 25px;
}

.checkbox-label {
  display: flex;
  align-items: flex-start;
  cursor: pointer;
  color: #666;
  font-size: 0.9rem;
}

.checkbox-label input {
  margin-right: 8px;
  margin-top: 3px;
  cursor: pointer;
  flex-shrink: 0;
}

.link {
  color: #0B7285;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease;
}

.link:hover {
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
  margin-top: 15px;
  padding: 12px;
  border-radius: 8px;
  font-size: 0.9rem;
  animation: slideDown 0.3s ease-out;
  text-align: center;
}

.error-banner {
  background: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

.success-banner {
  background: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.auth-footer {
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
    max-width: 100%;
  }

  .auth-header h1 {
    font-size: 1.5rem;
  }
}
</style>
