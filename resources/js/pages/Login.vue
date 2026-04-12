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
          <label for="password">
            Password 
            <span v-if="form.password" class="password-length-hint">
              ({{ form.password.length }} chars)
            </span>
          </label>
          <div class="input-wrapper">
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
              :title="showPassword ? 'Sembunyikan password' : 'Tampilkan password'"
            >
              <!-- Open Eye Icon -->
              <svg v-if="showPassword" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="eye-icon">
                <!-- Outer eye shape -->
                <ellipse cx="12" cy="12" rx="9" ry="6" stroke="#666666" stroke-width="1.5" fill="none"/>
                <!-- Inner circle (iris) -->
                <circle cx="12" cy="12" r="3" stroke="#666666" stroke-width="1.5" fill="#666666"/>
                <!-- Pupil -->
                <circle cx="12" cy="12" r="1.5" fill="#999999"/>
              </svg>
              <!-- Closed Eye Icon -->
              <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="eye-icon">
                <!-- Left eye closed -->
                <path d="M4 12c1.5 1.5 3.5 2 5 2" stroke="#666666" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                <!-- Right eye closed -->
                <path d="M20 12c-1.5 1.5-3.5 2-5 2" stroke="#666666" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                <!-- Slash line -->
                <line x1="3" y1="4" x2="21" y2="20" stroke="#666666" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
            </button>
          </div>
          <span v-if="errors.password" class="error-message">{{ errors.password }}</span>
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
    <router-link to="/" class="back-button" title="Kembali ke halaman utama">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M15 18L9 12L15 6" stroke="#0B7285" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </router-link>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'

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

// Get API base URL from environment
const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

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
  } else if (form.password.length < 6) {
    errors.password = 'Password minimal 6 karakter'
  }

  return !errors.email && !errors.password
}

const handleLogin = async () => {
  if (!validateForm()) return

  isLoading.value = true
  errors.general = ''
  
  console.group('🔐 LOGIN PROCESS STARTED')
  console.log('📧 Email:', form.email)
  console.log('🔒 Password length:', form.password.length)
  
  try {
    const email = form.email.trim()
    const password = form.password.trim()
    
    console.log('📤 Sending to:', `${API_BASE_URL}/login`)
    console.log('📋 Payload:', { email, passwordLength: password.length })
    
    // Simple fetch without credentials - not needed for localStorage auth
    const response = await fetch(`${API_BASE_URL}/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ email, password })
    })

    console.log('📨 Response received')
    console.log('📊 Status:', response.status, `(${response.ok ? '✅ OK' : '❌ ERROR'})`)
    console.log('🔗 Headers - Content-Type:', response.headers.get('content-type'))
    
    let data
    try {
      const text = await response.text()
      console.log('📄 Raw response body:', text.substring(0, 500))
      data = JSON.parse(text)
      console.log('✅ Parsed JSON successfully')
    } catch (parseError) {
      console.error('❌ JSON parse failed:', parseError.message)
      errors.general = 'Server error: Invalid response format'
      isLoading.value = false
      console.groupEnd()
      return
    }

    console.log('📦 Response data object:', data)

    // Check for success flag first (regardless of HTTP status)
    if (data.success && data.data) {
      console.log('✅ API returned success: true')
      const user = data.data
      
      // Validate required fields
      if (!user.id || !user.email || !user.role) {
        console.warn('⚠️ Missing required user fields', { id: user.id, email: user.email, role: user.role })
        errors.general = 'Login gagal - Data user tidak lengkap'
        isLoading.value = false
        console.groupEnd()
        return
      }
      
      console.log('✅ User data complete')
      console.log('👤 User role:', user.role)
      
      successMessage.value = `Selamat datang, ${user.fullname || user.email}!`
      
      // Prepare user object for localStorage
      const userData = {
        id_user: user.id,
        id: user.id,
        fullname: user.fullname || '',
        email: user.email || '',
        role: user.role || 'customer',
        phone: user.phone || '',
        school: user.school || '',
        address: user.address || '',
        avatar: user.avatar || '👤',
        status: user.status || 'active',
        joinDate: user.joinDate || new Date().toISOString(),
        rememberMe: form.rememberMe,
        loginAt: new Date().toISOString()
      }
      
      console.log('💾 Saving to localStorage...')
      localStorage.setItem('user', JSON.stringify(userData))
      const verify = localStorage.getItem('user')
      if (verify) {
        console.log('✅ localStorage saved and verified')
      } else {
        throw new Error('localStorage save failed')
      }
      
      console.log('✨ Login successful! Redirecting...')
      console.groupEnd()
      
      setTimeout(() => {
        router.push('/dashboard')
      }, 1000)
      return
    }
    
    // If not success, show error
    const errorMsg = data.message || `Login gagal (Status: ${response.status})`
    console.error('❌ Login failed:', errorMsg)
    console.log('📋 Error details:', data)
    errors.general = errorMsg
    
  } catch (error) {
    console.error('❌ Unexpected error:', error)
    console.error('📍 Error stack:', error.stack)
    errors.general = `Login error: ${error.message}`
  } finally {
    console.log('🏁 Login process finished')
    console.groupEnd()
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
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
}

.back-button svg {
  width: 24px;
  height: 24px;
}

.back-button:hover {
  transform: scale(1.15);
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
  padding: 12px;
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
  padding: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: opacity 0.3s ease;
}

.password-toggle:hover {
  opacity: 0.7;
}

.eye-icon {
  display: block;
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

.password-length-hint {
  font-size: 0.8rem;
  color: #999;
  margin-left: 5px;
}

.test-credentials {
  background: #f0f8fa;
  border: 1px solid #c0e9f0;
  border-radius: 12px;
  padding: 15px;
  margin: 20px 0;
  text-align: center;
}

.test-label {
  font-size: 0.85rem;
  color: #666;
  margin: 0 0 10px 0;
  font-weight: 500;
}

.test-buttons {
  display: flex;
  gap: 10px;
  justify-content: center;
}

.test-btn {
  background: #e3f2fd;
  border: 1px solid #90caf9;
  color: #1976d2;
  padding: 8px 12px;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  flex: 1;
  overflow: hidden;
  text-decoration: none;
  white-space: nowrap;
}

.test-btn:hover {
  background: #bbdefb;
  border-color: #64b5f6;
  transform: translateY(-2px);
}

.test-btn.direct {
  background: #fff3e0;
  border-color: #ffb74d;
  color: #e65100;
}

.test-btn.direct:hover {
  background: #ffe0b2;
  border-color: #ffa726;
}

.test-btn small {
  display: block;
  font-size: 0.75rem;
  color: #999;
  margin-top: 3px;
}

.test-btn.direct small {
  color: #cc3300;
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
