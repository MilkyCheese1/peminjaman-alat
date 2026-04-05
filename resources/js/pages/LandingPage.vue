<template>
  <div id="landing" class="landing-page">
    <!-- HEADER -->
    <header class="header" :class="{ 'header-scroll': isScrolled, 'dark': isDarkMode }">
      <div class="header-container">
        <!-- Logo -->
        <div class="logo">
          🎓 TrustEquip
        </div>

        <!-- Dark Mode Toggle & Desktop Nav (Left controls) -->
        <div class="header-left-controls">
          <!-- Dark Mode Toggle -->
          <button class="theme-toggle" @click="toggleDarkMode" :title="isDarkMode ? 'Light Mode' : 'Dark Mode'">
            {{ isDarkMode ? '☀️' : '🌙' }}
          </button>

          <!-- Desktop Nav -->
          <nav class="nav-desktop">
            <a href="#hero" @click.prevent="scrollToSection(0)" :class="{ 'active': currentSection === 0 }">
              Home
            </a>
            <a href="#products" @click.prevent="scrollToSection(1)" :class="{ 'active': currentSection === 1 }">
              Produk
            </a>
            <a href="#about" @click.prevent="scrollToSection(2)" :class="{ 'active': currentSection === 2 }">
              Tentang
            </a>
            <router-link to="/register" class="nav-link nav-link-primary">
              Mulai
            </router-link>
          </nav>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" @click="isMobileMenuOpen = !isMobileMenuOpen" :class="{ 'active': isMobileMenuOpen }">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>

      <!-- Mobile Menu -->
      <nav class="nav-mobile" v-show="isMobileMenuOpen">
        <a href="#hero" @click.prevent="scrollToSectionAndCloseMobile(0)">Home</a>
        <a href="#products" @click.prevent="scrollToSectionAndCloseMobile(1)">Produk</a>
        <a href="#about" @click.prevent="scrollToSectionAndCloseMobile(2)">Tentang</a>
        <router-link to="/register" class="mobile-link">Mulai</router-link>
        <button class="mobile-theme-toggle" @click="toggleDarkMode">
          {{ isDarkMode ? '☀️ Light Mode' : '🌙 Dark Mode' }}
        </button>
      </nav>
    </header>

    <!-- MAIN CONTENT -->
    <main class="main-content">
      <!-- SECTION 1: HERO -->
      <section class="section hero-section" :class="{ 'active': currentSection === 0 }">
        <div class="hero-background" :style="{ transform: `translateY(${parallaxOffset * 0.5}px)` }"></div>
        
        <div class="hero-content">
          <div class="hero-text" :style="{ opacity: heroOpacity, transform: `translateY(${(1 - heroOpacity) * 20}px)` }">
            <h1 class="hero-title">Pinjam Alat dengan Mudah</h1>
            <p class="hero-subtitle">Platform penyewaan alat terpercaya untuk komunitas sekolah Anda</p>
            
            <div class="hero-stats">
              <div class="stat">
                <div class="stat-number">500+</div>
                <div class="stat-label">Alat Tersedia</div>
              </div>
              <div class="stat">
                <div class="stat-number">2000+</div>
                <div class="stat-label">Pengguna Aktif</div>
              </div>
              <div class="stat">
                <div class="stat-number">98%</div>
                <div class="stat-label">Kepuasan</div>
              </div>
            </div>

            <div class="hero-buttons">
              <button class="cta-button primary" @click="scrollToSection(3)">
                Daftar Sekarang
                <span class="arrow">→</span>
              </button>
              <router-link to="/login" class="cta-button secondary">Masuk</router-link>
            </div>
          </div>

          <div class="hero-cards" :style="{ transform: `translateY(${parallaxOffset * 0.3}px)` }">
            <div class="card" v-for="(card, idx) in heroCards" :key="idx" :style="{ animation: `float 3s ease-in-out ${idx * 0.2}s infinite` }">
              <div class="card-icon">{{ card.icon }}</div>
              <div class="card-title">{{ card.title }}</div>
            </div>
          </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="scroll-indicator" :style="{ animation: `bounce 2s infinite` }">
          <div class="mouse">
            <div class="wheel"></div>
          </div>
          <div class="arrow">↓</div>
        </div>
      </section>

      <!-- SECTION 2: PRODUCTS -->
      <section class="section products-section" :class="{ 'active': currentSection === 1 }">
        <div class="section-header">
          <h2>Koleksi Alat Kami</h2>
          <p>Berbagai alat berkualitas tinggi siap untuk memenuhi kebutuhan Anda</p>
        </div>

        <div class="carousel-container" ref="carouselContainer" @wheel="handleCarouselWheel">
          <div class="products-carousel">
            <div class="product-card" v-for="(product, idx) in products" :key="idx" :style="{ 'animation-delay': `${idx * 0.1}s` }">
              <div class="product-image">{{ product.icon }}</div>
              <h3>{{ product.name }}</h3>
              <p>{{ product.description }}</p>
              <div class="product-info">
                <span class="stock">Stok: {{ product.stock }}</span>
              </div>
              <button class="product-button">Lihat Detail</button>
            </div>
          </div>
        </div>

        <div class="carousel-nav">
          <button class="carousel-arrow left" @click="scrollCarousel(-1)" :disabled="carouselPosition <= 0">←</button>
          <div class="carousel-dots">
            <span 
              v-for="(_, idx) in products" 
              :key="idx" 
              class="dot"
              :class="{ 'active': idx === carouselPosition }"
              @click="scrollCarouselToIndex(idx)"
            ></span>
          </div>
          <button class="carousel-arrow right" @click="scrollCarousel(1)" :disabled="carouselPosition >= products.length - 1">→</button>
        </div>
      </section>

      <!-- SECTION 3: CTA -->
      <section class="section cta-section" :class="{ 'active': currentSection === 2 }">
        <div class="cta-wrapper">
          <div class="cta-content">
            <h2>Siap Memulai?</h2>
            <p>Bergabunglah dengan ribuan pengguna yang telah merasakan kemudahan berbagi alat</p>
            
            <div class="cta-features">
              <div class="feature" v-for="feature in ctaFeatures" :key="feature">
                <span class="check">✓</span>
                <span>{{ feature }}</span>
              </div>
            </div>

            <div class="cta-buttons">
              <button class="cta-button primary large">Daftar Sebagai Peminjam</button>
              <button class="cta-button secondary large">Daftar Sebagai Pemilik</button>
            </div>
          </div>

          <div class="cta-image">
            <div class="cta-illustration">
              📦
            </div>
          </div>
        </div>
      </section>

      <!-- SECTION 4: FOOTER -->
      <footer class="section footer" :class="{ 'active': currentSection === 3 }">
        <div class="footer-content">
          <div class="footer-section">
            <h3>TrustEquip</h3>
            <p>Platform terpercaya untuk penyewaan alat di komunitas sekolah</p>
            <div class="social-links">
              <a href="#" title="Facebook">f</a>
              <a href="#" title="Instagram">ig</a>
              <a href="#" title="Twitter">𝕏</a>
            </div>
          </div>

          <div class="footer-section">
            <h4>Navigasi</h4>
            <ul>
              <li><a href="#" @click.prevent="scrollToSection(0)">Home</a></li>
              <li><a href="#" @click.prevent="scrollToSection(1)">Produk</a></li>
              <li><a href="#" @click.prevent="scrollToSection(2)">Tentang</a></li>
              <li><a href="#" @click.prevent="scrollToSection(3)">Kontak</a></li>
            </ul>
          </div>

          <div class="footer-section">
            <h4>Bantuan</h4>
            <ul>
              <li><a href="#">FAQ</a></li>
              <li><a href="#">Syarat & Ketentuan</a></li>
              <li><a href="#">Kebijakan Privasi</a></li>
              <li><a href="#">Hubungi Kami</a></li>
            </ul>
          </div>

          <div class="footer-section">
            <h4>Kontak</h4>
            <p>📧 info@trustequip.id</p>
            <p>📱 +62 812-3456-7890</p>
            <p>📍 Sekolah Teknologi Digital</p>
          </div>
        </div>

        <div class="footer-bottom">
          <p>&copy; 2026 TrustEquip. All rights reserved.</p>
        </div>
      </footer>
      <!-- Floating Back to Top Button -->
      <button
        class="floating-back-to-top"
        @click="scrollToSection(0)"
        v-show="currentSection > 0"
        aria-label="Kembali ke atas"
      >
        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="14" cy="14" r="14" fill="#0B7285"/>
          <path d="M14 20V8M14 8L8 14M14 8L20 14" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
    </main>

    <!-- KEYBOARD NAVIGATION HINT -->
    <div class="nav-hint" v-if="showNavHint">
      <span>⌨️ Gunakan arrow keys untuk navigasi</span>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

// State
const isDarkMode = ref(false)
const isMobileMenuOpen = ref(false)
const currentSection = ref(0)
const scrollProgress = ref(0)
const parallaxOffset = ref(0)
const isScrolled = ref(false)
const showNavHint = ref(true)
const carouselContainer = ref(null)
const carouselPosition = ref(0)
let carouselWheelTimeout = null

// Computed
const heroOpacity = computed(() => {
  return Math.max(0, 1 - (parallaxOffset.value / 300))
})

// Data
const heroCards = ref([
  { icon: '⚡', title: 'Cepat & Mudah' },
  { icon: '🔒', title: 'Aman Terpercaya' },
  { icon: '💰', title: 'Harga Terjangkau' }
])

const products = ref([
  {
    icon: '💻',
    name: 'Laptop',
    description: 'Laptop gaming high-performance untuk project multimedia',
    stock: 15
  },
  {
    icon: '📊',
    name: 'Proyektor',
    description: 'Proyektor 4K untuk presentasi profesional',
    stock: 8
  },
  {
    icon: '📷',
    name: 'Kamera DSLR',
    description: 'Kamera profesional dengan lensa berkualitas',
    stock: 12
  },
  {
    icon: '🎙️',
    name: 'Microphone Set',
    description: 'Studio microphone dengan soundcard profesional',
    stock: 6
  },
  {
    icon: '🖥️',
    name: 'Monitor 4K',
    description: 'Monitor ultra-wide untuk editing dan design',
    stock: 10
  },
  {
    icon: '⌨️',
    name: 'Keyboard Mekanik',
    description: 'Keyboard RGB mechanical para gamer',
    stock: 20
  }
])

const ctaFeatures = ref([
  'Proses registrasi hanya 2 menit',
  'Akses 500+ alat premium',
  'Jaminan uang kembali 100%',
  'Support 24/7 terhadap masalah'
])

// Methods
const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value
  localStorage.setItem('trustequip_darkmode', isDarkMode.value)
  document.documentElement.setAttribute('data-theme', isDarkMode.value ? 'dark' : 'light')
}

const scrollToSection = (index) => {
  currentSection.value = index
  isMobileMenuOpen.value = false
  const section = document.querySelectorAll('.section')[index]
  if (section) {
    section.scrollIntoView({ behavior: 'smooth' })
  }
}

const scrollToSectionAndCloseMobile = (index) => {
  scrollToSection(index)
  isMobileMenuOpen.value = false
}

const handleScroll = () => {
  const scrolled = window.scrollY
  parallaxOffset.value = scrolled
  scrollProgress.value = (scrolled / (document.documentElement.scrollHeight - window.innerHeight)) * 100
  isScrolled.value = scrolled > 50

  // Detect current section
  const sections = document.querySelectorAll('.section')
  sections.forEach((section, index) => {
    const rect = section.getBoundingClientRect()
    if (rect.top <= window.innerHeight / 2 && rect.bottom >= window.innerHeight / 2) {
      currentSection.value = index
    }
  })
}

const scrollCarousel = (direction) => {
  const newPosition = carouselPosition.value + direction
  if (newPosition >= 0 && newPosition < products.value.length) {
    carouselPosition.value = newPosition
    if (carouselContainer.value) {
      const cardElement = carouselContainer.value.querySelector('.product-card')
      if (cardElement) {
        const cardWidth = cardElement.offsetWidth
        const gap = 30
        carouselContainer.value.scrollLeft = newPosition * (cardWidth + gap)
      }
    }
  }
}

const scrollCarouselToIndex = (index) => {
  carouselPosition.value = index
  if (carouselContainer.value) {
    const cardElement = carouselContainer.value.querySelector('.product-card')
    if (cardElement) {
      const cardWidth = cardElement.offsetWidth
      const gap = 30
      carouselContainer.value.scrollLeft = index * (cardWidth + gap)
    }
  }
}

const handleCarouselWheel = (e) => {
  if (!carouselContainer.value) return
  
  // Debounce wheel events to prevent rapid multiple scrolls
  if (carouselWheelTimeout !== null) return
  
  e.preventDefault()
  
  // Check if user is scrolling vertically significantly more than horizontally
  if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
    // Only prevent default if deltaY is significant
    if (Math.abs(e.deltaY) > 10) {
      const direction = e.deltaY > 0 ? 1 : -1
      scrollCarousel(direction)
      
      // Set debounce timeout (300ms prevents rapid scrolls)
      carouselWheelTimeout = setTimeout(() => {
        carouselWheelTimeout = null
      }, 300)
    }
  } else {
    // Horizontal scroll
    const direction = e.deltaX > 0 ? 1 : -1
    scrollCarousel(direction)
    
    // Set debounce timeout
    carouselWheelTimeout = setTimeout(() => {
      carouselWheelTimeout = null
    }, 300)
  }
}

const handleKeydown = (e) => {
  const maxSection = 3 // Footer is the last section (index 3)
  
  // Handle carousel navigation when in products section
  if (currentSection.value === 1) {
    if (e.key === 'ArrowRight') {
      e.preventDefault()
      scrollCarousel(1)
      return
    } else if (e.key === 'ArrowLeft') {
      e.preventDefault()
      scrollCarousel(-1)
      return
    }
  }
  
  // Handle section navigation - Up/Down for sections, not left/right
  if (e.key === 'ArrowDown') {
    e.preventDefault()
    if (currentSection.value < maxSection) {
      scrollToSection(currentSection.value + 1)
    }
  } else if (e.key === 'ArrowUp') {
    e.preventDefault()
    if (currentSection.value > 0) {
      scrollToSection(currentSection.value - 1)
    }
  }
}

// Lifecycle
onMounted(() => {
  window.addEventListener('scroll', handleScroll)
  window.addEventListener('keydown', handleKeydown)

  // Load dark mode preference
  const savedDarkMode = localStorage.getItem('trustequip_darkmode')
  if (savedDarkMode === 'true') {
    isDarkMode.value = true
    document.documentElement.setAttribute('data-theme', 'dark')
  }

  // Hide hint after 5 seconds
  setTimeout(() => {
    showNavHint.value = false
  }, 5000)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
  window.removeEventListener('keydown', handleKeydown)
  
  // Cleanup carousel wheel debounce timeout
  if (carouselWheelTimeout !== null) {
    clearTimeout(carouselWheelTimeout)
  }
})
</script>
