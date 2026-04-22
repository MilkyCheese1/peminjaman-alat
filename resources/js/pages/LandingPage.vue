<template>
  <div class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    <main>
      <LandingHeroSection
        :is-dark-mode="isDarkMode"
        :mobile-menu-open="mobileMenuOpen"
        :stats-loading="statsLoading"
        :availability-percent="availabilityPercent"
        :trust-score-label="trustScoreLabel"
        :total-borrowings-label="totalBorrowingsLabel"
        :shape1-style="shape1Style"
        :shape2-style="shape2Style"
        :shape3-style="shape3Style"
        @toggle-dark-mode="toggleDarkMode"
        @toggle-mobile-menu="mobileMenuOpen = !mobileMenuOpen"
      />

      <LandingFeatureSection />

      <LandingWorkflowSection />

      <LandingToolsSection
        :tool-cards="toolCards"
        :tool-carousel-items="toolCarouselItems"
        :current-slide="currentSlide"
        :visible-slides="visibleSlides"
        @prev="prevSlide"
        @next="nextSlide"
        @go-to="goToSlide"
      />

      <LandingTestimonialsSection
        :testimonials-loading="testimonialsLoading"
        :testimonials="testimonials"
        :current-testimonial-slide="currentTestimonialSlide"
        :testimonial-visible-slides="testimonialVisibleSlides"
        :rating="rating"
        @prev="prevTestimonialSlide"
        @next="nextTestimonialSlide"
        @go-to="goToTestimonialSlide"
        @submit="submitRating"
      />
      <LandingContactSection />
      <LandingFooterSection />
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import LandingHeroSection from '../components/landing/LandingHeroSection.vue'
import LandingFeatureSection from '../components/landing/LandingFeatureSection.vue'
import LandingWorkflowSection from '../components/landing/LandingWorkflowSection.vue'
import LandingToolsSection from '../components/landing/LandingToolsSection.vue'
import LandingTestimonialsSection from '../components/landing/LandingTestimonialsSection.vue'
import LandingContactSection from '../components/landing/LandingContactSection.vue'
import LandingFooterSection from '../components/landing/LandingFooterSection.vue'
import { apiRequest } from '../lib/api'
import { useDarkMode } from '../composables/useDarkMode'

const { isDarkMode, toggleDarkMode, initDarkMode } = useDarkMode()

const scrollY = ref(0)
const mobileMenuOpen = ref(false)
const currentSlide = ref(0)
const currentTestimonialSlide = ref(0)
const statsLoading = ref(true)
const testimonialsLoading = ref(true)
const landingStats = ref({
  tools: [],
  borrowings: [],
})
const rating = ref({
  name: '',
  email: '',
  stars: 0,
  message: ''
})
const defaultTestimonials = [
  {
    name: 'Ahmad',
    role: 'Pengguna',
    initial: 'A',
    avatarClass: 'bg-cyan-500/10 text-cyan-700 dark:text-cyan-200',
    stars: 5,
    message: 'Sangat mudah meminjam alat di sini. Prosesnya cepat, alurnya jelas, dan alatnya selalu siap ketika saya butuhkan untuk pekerjaan yang cukup padat.',
  },
  {
    name: 'Budi',
    role: 'Kontraktor',
    initial: 'B',
    avatarClass: 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-200',
    stars: 5,
    message: 'Koleksi alat lengkap, harga terjangkau, dan petugasnya responsif saat saya butuh alat tambahan di tengah pekerjaan.',
  },
  {
    name: 'Citra',
    role: 'Mahasiswa',
    initial: 'C',
    avatarClass: 'bg-sky-500/10 text-sky-700 dark:text-sky-200',
    stars: 5,
    message: 'Interface yang user-friendly. Mudah digunakan bahkan untuk pemula, dan detail informasinya cukup membantu saat saya memilih alat.',
  },
  {
    name: 'Dewi',
    role: 'Admin Kantor',
    initial: 'D',
    avatarClass: 'bg-violet-500/10 text-violet-700 dark:text-violet-200',
    stars: 5,
    message: 'Pencatatan peminjaman jadi jauh lebih rapi. Saya bisa memantau status alat dan riwayat transaksi tanpa ribet.',
  },
  {
    name: 'Eka',
    role: 'Staff Operasional',
    initial: 'E',
    avatarClass: 'bg-amber-500/10 text-amber-700 dark:text-amber-200',
    stars: 5,
    message: 'Saya suka karena status alat dan informasi peminjaman terlihat jelas. Sangat membantu saat jam kerja sedang padat.',
  },
  {
    name: 'Fajar',
    role: 'Tim Proyek',
    initial: 'F',
    avatarClass: 'bg-rose-500/10 text-rose-700 dark:text-rose-200',
    stars: 5,
    message: 'Alurnya terasa sederhana, dan respon staff cepat. Kami jadi lebih efisien saat butuh alat mendadak untuk meeting atau presentasi.',
  },
]
const testimonials = ref([...defaultTestimonials])
const toolFallbackImage = '/trustequip-logo.png'

const toolStatusMap = {
  tersedia: 'tersedia',
  dipinjam: 'dipinjam',
  maintenance: 'maintenance',
  '1': 'tersedia',
  '2': 'dipinjam',
  '3': 'maintenance',
}

const borrowingStatusMap = {
  pending: 'pending',
  disetujui: 'disetujui',
  ditolak: 'ditolak',
  dipinjam: 'dipinjam',
  dikembalikan: 'dikembalikan',
  selesai: 'selesai',
  '1': 'pending',
  '2': 'disetujui',
  '3': 'ditolak',
  '4': 'dipinjam',
  '5': 'dikembalikan',
  '6': 'selesai',
}

const testimonialAvatarClasses = [
  'bg-cyan-500/10 text-cyan-700 dark:text-cyan-200',
  'bg-emerald-500/10 text-emerald-700 dark:text-emerald-200',
  'bg-sky-500/10 text-sky-700 dark:text-sky-200',
  'bg-violet-500/10 text-violet-700 dark:text-violet-200',
  'bg-amber-500/10 text-amber-700 dark:text-amber-200',
  'bg-rose-500/10 text-rose-700 dark:text-rose-200',
]

function mapToolToLandingCard(tool, index = 0) {
  const name = String(tool?.namaAlat ?? tool?.nama_alat ?? 'Peralatan').trim() || 'Peralatan'
  const category = String(tool?.kategori ?? tool?.category?.nama_kategori ?? 'Peralatan').trim() || 'Peralatan'
  const stok = Number(tool?.stok ?? 0)
  const status = normalizeStatus(tool?.status, toolStatusMap)
  const statusLabel = status === 'maintenance' ? 'Maintenance' : status === 'dipinjam' ? 'Dipinjam' : 'Tersedia'

  const description = summarizeToolDescription(tool?.deskripsi, stok, status)

  return {
    id: tool?.id ?? index,
    nama: name,
    kategori: category,
    deskripsi: description,
    stok,
    statusLabel,
    gambar: String(tool?.gambar ?? '').trim() || toolFallbackImage,
  }
}

function summarizeToolDescription(value, stok, status) {
  const raw = String(value ?? '').trim()
  const fallback = [
    `${stok} stok tersedia`,
    status === 'maintenance' ? 'Sedang maintenance' : status === 'dipinjam' ? 'Sedang dipinjam' : 'Siap dipinjam',
  ].filter(Boolean).join(' | ')

  const source = raw || fallback
  const normalized = source.replace(/\s+/g, ' ').trim()
  const sentenceMatch = normalized.match(/^.*?[.!?](?:\s|$)/)
  const firstSentence = sentenceMatch ? sentenceMatch[0].trim() : normalized

  if (firstSentence.length <= 88) return firstSentence
  return `${firstSentence.slice(0, 85).trimEnd()}...`
}

function normalizeStatus(value, map = {}) {
  const key = String(value ?? '').trim().toLowerCase()
  return map[key] ?? key
}

function makeTestimonialInitial(name) {
  const value = String(name ?? '').trim()
  return value ? value.charAt(0).toUpperCase() : 'U'
}

function mapFeedbackToTestimonial(entry, index = 0) {
  const name = String(entry?.nama ?? 'Pengguna').trim() || 'Pengguna'
  const stars = Number(entry?.stars ?? 5)

  return {
    id: entry?.id ?? `${Date.now()}-${index}`,
    name,
    role: 'Pengguna',
    initial: makeTestimonialInitial(name),
    avatarClass: testimonialAvatarClasses[index % testimonialAvatarClasses.length],
    stars: Number.isFinite(stars) && stars > 0 ? Math.min(5, Math.max(1, stars)) : 5,
    message: String(entry?.pesan ?? '').trim() || 'Pengalaman penggunaan TrustEquip.id sangat membantu.',
    createdAt: entry?.createdAt ?? null,
  }
}

function getFallbackTestimonials() {
  return defaultTestimonials.map((item, index) => ({
    ...item,
    id: index + 1,
  }))
}

function isToolAvailable(tool) {
  const status = normalizeStatus(tool?.status, toolStatusMap)
  const stok = Number(tool?.stok ?? 0)
  return status === 'tersedia' && stok > 0
}

function isBorrowingCompleted(item) {
  const status = normalizeStatus(item?.status, borrowingStatusMap)
  return status === 'dikembalikan' || status === 'selesai'
}

function formatCompactNumber(value) {
  const amount = Number(value || 0)

  if (amount >= 1000000) {
    return `${(amount / 1000000).toFixed(1)} jt`
  }

  if (amount >= 1000) {
    const scaled = amount / 1000
    return `${scaled >= 10 ? Math.round(scaled) : scaled.toFixed(1)} rb`
  }

  return `${amount}`
}

const totalToolsCount = computed(() => (Array.isArray(landingStats.value.tools) ? landingStats.value.tools.length : 0))
const availableToolsCount = computed(() => (Array.isArray(landingStats.value.tools) ? landingStats.value.tools.filter(isToolAvailable).length : 0))
const availabilityPercent = computed(() => {
  const total = totalToolsCount.value
  if (!total) return 0
  return Math.round((availableToolsCount.value / total) * 100)
})

const totalBorrowingsCount = computed(() => (Array.isArray(landingStats.value.borrowings) ? landingStats.value.borrowings.length : 0))
const completedBorrowingsCount = computed(() => (Array.isArray(landingStats.value.borrowings) ? landingStats.value.borrowings.filter(isBorrowingCompleted).length : 0))
const trustScore = computed(() => {
  const total = totalBorrowingsCount.value
  if (!total) return 0
  return Math.min(10, Math.max(0, (completedBorrowingsCount.value / total) * 10))
})
const totalBorrowingsLabel = computed(() => formatCompactNumber(totalBorrowingsCount.value))
const trustScoreLabel = computed(() => `${trustScore.value.toFixed(1)}/10`)
const toolCards = computed(() => (Array.isArray(landingStats.value.tools) ? landingStats.value.tools.map(mapToolToLandingCard) : []))
const toolCarouselItems = computed(() => {
  if (!toolCards.value.length) return []
  return [...toolCards.value, ...toolCards.value, ...toolCards.value]
})

const shape1Style = computed(() => ({
  transform: `translateY(${scrollY.value * 0.08}px)`,
}))

const shape2Style = computed(() => ({
  transform: `translateY(${scrollY.value * 0.05}px)`,
}))

const shape3Style = computed(() => ({
  transform: `translateY(${scrollY.value * 0.12}px)`,
}))

const visibleSlides = computed(() => {
  if (typeof window !== 'undefined') {
    if (window.innerWidth >= 1280) return 4 // xl
    if (window.innerWidth >= 1024) return 3 // lg
    if (window.innerWidth >= 768) return 2 // md
    return 1 // mobile
  }
  return 1
})

const testimonialVisibleSlides = visibleSlides

async function loadTestimonials() {
  testimonialsLoading.value = true

  try {
    const data = await apiRequest('/api/feedback')
    const entries = Array.isArray(data) ? data : []
    testimonials.value = entries.length
      ? entries.map((entry, index) => mapFeedbackToTestimonial(entry, index))
      : getFallbackTestimonials()
    currentTestimonialSlide.value = 0
  } catch (error) {
    testimonials.value = getFallbackTestimonials()
    currentTestimonialSlide.value = 0
  } finally {
    testimonialsLoading.value = false
  }
}

const submitRating = async () => {
  if (rating.value.stars === 0) {
    alert('Silakan berikan rating bintang terlebih dahulu')
    return
  }

  try {
    const created = await apiRequest('/api/feedback', {
      method: 'POST',
      body: {
        nama: rating.value.name,
        email: rating.value.email,
        stars: rating.value.stars,
        pesan: rating.value.message,
      },
    })

    testimonials.value = [
      mapFeedbackToTestimonial(created, 0),
      ...testimonials.value,
    ]
    currentTestimonialSlide.value = 0
    rating.value = {
      name: '',
      email: '',
      stars: 0,
      message: '',
    }
    alert('Terima kasih! Review Anda langsung ditampilkan di testimoni.')
  } catch (error) {
    alert(error?.message || 'Terjadi kesalahan saat mengirim review.')
  }
}

const nextSlide = () => {
  if (!toolCards.value.length) return
  currentSlide.value = (currentSlide.value + 1) % toolCards.value.length
}

const prevSlide = () => {
  if (!toolCards.value.length) return
  currentSlide.value = currentSlide.value === 0 ? toolCards.value.length - 1 : currentSlide.value - 1
}

const goToSlide = (index) => {
  if (!toolCards.value.length) return
  currentSlide.value = index
}

const nextTestimonialSlide = () => {
  if (!testimonials.value.length) return
  currentTestimonialSlide.value = (currentTestimonialSlide.value + 1) % testimonials.value.length
}

const prevTestimonialSlide = () => {
  if (!testimonials.value.length) return
  currentTestimonialSlide.value = currentTestimonialSlide.value === 0
    ? testimonials.value.length - 1
    : currentTestimonialSlide.value - 1
}

const goToTestimonialSlide = (index) => {
  if (!testimonials.value.length) return
  currentTestimonialSlide.value = index
}

const handleScroll = () => {
  scrollY.value = window.scrollY
}

async function loadLandingStats() {
  statsLoading.value = true

  try {
    const [toolsResult, borrowingsResult] = await Promise.allSettled([
      apiRequest('/api/tools'),
      apiRequest('/api/borrowings'),
    ])

    landingStats.value = {
      tools: toolsResult.status === 'fulfilled' && Array.isArray(toolsResult.value) ? toolsResult.value : [],
      borrowings: borrowingsResult.status === 'fulfilled' && Array.isArray(borrowingsResult.value) ? borrowingsResult.value : [],
    }
    currentSlide.value = 0
  } catch (error) {
    landingStats.value = {
      tools: [],
      borrowings: [],
    }
    currentSlide.value = 0
  } finally {
    statsLoading.value = false
  }
}

onMounted(() => {
  initDarkMode()
  loadLandingStats()
  loadTestimonials()
  window.addEventListener('scroll', handleScroll, { passive: true })
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>

<style scoped>
.bento-card {
  transition: transform 0.3s ease, border-color 0.3s ease, background-color 0.3s ease;
}
.bento-card:hover {
  transform: translateY(-6px);
  border-color: rgba(56, 189, 248, 0.35);
  background-color: rgba(240, 249, 255, 0.98);
}

:global(.dark) .bento-card:hover,
:global([data-theme='dark']) .bento-card:hover {
  background-color: rgba(15, 23, 42, 0.98);
}

</style>
