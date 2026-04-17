<script setup lang="ts">
import { ref, computed } from 'vue'
import { ChevronDown, Search, MessageSquare, Mail, Phone } from 'lucide-vue-next'

const searchQuery = ref('')
const expandedFAQ = ref<number | null>(null)

const faqs = [
  {
    id: 1,
    category: 'Umum',
    question: 'Apa itu TrustEquip?',
    answer: 'TrustEquip adalah platform penyewaan alat terpercaya untuk komunitas sekolah. Kami menyediakan layanan peminjaman alat berkualitas dengan sistem verifikasi yang transparan.'
  },
  {
    id: 2,
    category: 'Umum',
    question: 'Bagaimana cara mendaftar?',
    answer: 'Klik tombol "Daftar" di halaman login, isi data pribadi Anda, dan tunggu verifikasi dari admin. Setelah disetujui, Anda dapat langsung menggunakan platform.'
  },
  {
    id: 3,
    category: 'Peminjaman',
    question: 'Berapa lama durasi peminjaman alat?',
    answer: 'Durasi peminjaman tergantung jenis alat. Umumnya berkisar 1-7 hari. Untuk durasi lebih lama, Anda dapat membuat permintaan khusus kepada pemilik alat.'
  },
  {
    id: 4,
    category: 'Peminjaman',
    question: 'Apa yang harus dilakukan jika alat rusak?',
    answer: 'Segera laporkan ke admin melalui menu "Lapor Kerusakan" dengan foto dokumentasi. Anda akan diminta membayar biaya perbaikan sesuai dengan tingkat kerusakannya.'
  },
  {
    id: 5,
    category: 'Peminjaman',
    question: 'Apakah ada biaya tambahan untuk peminjaman?',
    answer: 'Biaya peminjaman sudah termasuk dalam harga yang ditampilkan. Biaya tambahan hanya akan dikenakan jika ada keterlambatan pengembalian atau kerusakan alat.'
  },
  {
    id: 6,
    category: 'Pembayaran',
    question: 'Metode pembayaran apa yang tersedia?',
    answer: 'Kami menerima berbagai metode pembayaran: transfer bank, e-wallet, dan kartu kredit. Semua transaksi dienkripsi untuk keamanan Anda.'
  },
  {
    id: 7,
    category: 'Pembayaran',
    question: 'Apakah pembayaran aman?',
    answer: 'Ya, semua transaksi dilindungi dengan enkripsi SSL 256-bit. Kami juga tidak menyimpan data kartu kredit Anda di server kami.'
  },
  {
    id: 8,
    category: 'Akun',
    question: 'Bagaimana cara mengubah password?',
    answer: 'Buka menu Profil → Edit Profil → Keamanan, kemudian klik "Ubah Password". Masukkan password lama Anda dan password baru dua kali.'
  },
  {
    id: 9,
    category: 'Akun',
    question: 'Bagaimana jika saya lupa password?',
    answer: 'Klik "Lupa Password" di halaman login, masukkan email Anda, dan kami akan mengirimkan link untuk mereset password.'
  }
]

const categories = computed(() => {
  const cats = new Set(faqs.map(f => f.category))
  return Array.from(cats)
})

const selectedCategory = ref<string | null>(null)

const filteredFAQs = computed(() => {
  let results = faqs

  if (selectedCategory.value) {
    results = results.filter(f => f.category === selectedCategory.value)
  }

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    results = results.filter(f =>
      f.question.toLowerCase().includes(query) ||
      f.answer.toLowerCase().includes(query)
    )
  }

  return results
})

const toggleFAQ = (id: number) => {
  expandedFAQ.value = expandedFAQ.value === id ? null : id
}
</script>

<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div>
      <h1 class="text-3xl font-bold">Bantuan & FAQ</h1>
      <p class="text-muted-foreground mt-2">Cari jawaban atau hubungi tim support kami</p>
    </div>

    <!-- Search Bar -->
    <div class="relative">
      <Search :size="20" class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground" />
      <input
        v-model="searchQuery"
        type="search"
        placeholder="Cari pertanyaan..."
        class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background"
      />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      <!-- Categories Sidebar -->
      <div class="lg:col-span-1">
        <h3 class="font-semibold mb-4">Kategori</h3>
        <div class="space-y-2">
          <button
            @click="selectedCategory = null"
            :class="[
              'w-full text-left px-3 py-2 rounded-lg transition-colors text-sm',
              !selectedCategory
                ? 'bg-primary text-primary-foreground'
                : 'hover:bg-accent'
            ]"
          >
            Semua ({{ faqs.length }})
          </button>
          <button
            v-for="cat in categories"
            :key="cat"
            @click="selectedCategory = selectedCategory === cat ? null : cat"
            :class="[
              'w-full text-left px-3 py-2 rounded-lg transition-colors text-sm',
              selectedCategory === cat
                ? 'bg-primary text-primary-foreground'
                : 'hover:bg-accent'
            ]"
          >
            {{ cat }} ({{ faqs.filter(f => f.category === cat).length }})
          </button>
        </div>
      </div>

      <!-- FAQ List -->
      <div class="lg:col-span-3 space-y-3">
        <div v-for="faq in filteredFAQs" :key="faq.id" class="border rounded-lg overflow-hidden bg-card hover:border-primary/50 transition-colors">
          <button
            @click="toggleFAQ(faq.id)"
            class="w-full p-4 flex items-start justify-between gap-4 hover:bg-accent/50 transition-colors"
          >
            <div class="text-left">
              <p class="text-xs text-primary font-semibold mb-1">{{ faq.category }}</p>
              <h4 class="font-semibold text-sm">{{ faq.question }}</h4>
            </div>
            <ChevronDown
              :size="20"
              :class="expandedFAQ === faq.id ? 'rotate-180' : ''"
              class="flex-shrink-0 transition-transform"
            />
          </button>

          <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="max-h-0 opacity-0"
            enter-to-class="max-h-96 opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="max-h-96 opacity-100"
            leave-to-class="max-h-0 opacity-0"
          >
            <div v-if="expandedFAQ === faq.id" class="px-4 pb-4 border-t bg-background/50">
              <p class="text-sm text-muted-foreground">{{ faq.answer }}</p>
            </div>
          </transition>
        </div>

        <div v-if="filteredFAQs.length === 0" class="text-center py-12 bg-card rounded-lg border">
          <p class="text-muted-foreground">Pertanyaan tidak ditemukan</p>
        </div>
      </div>
    </div>

    <!-- Support Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t">
      <div class="bg-card border rounded-lg p-6 text-center">
        <div class="w-12 h-12 rounded-full bg-primary/20 text-primary flex items-center justify-center mx-auto mb-4">
          <MessageSquare :size="24" />
        </div>
        <h3 class="font-semibold mb-2">Live Chat</h3>
        <p class="text-sm text-muted-foreground mb-4">Chat dengan tim support kami secara real-time</p>
        <button class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors text-sm">
          Mulai Chat
        </button>
      </div>

      <div class="bg-card border rounded-lg p-6 text-center">
        <div class="w-12 h-12 rounded-full bg-primary/20 text-primary flex items-center justify-center mx-auto mb-4">
          <Mail :size="24" />
        </div>
        <h3 class="font-semibold mb-2">Email</h3>
        <p class="text-sm text-muted-foreground mb-4">Kirim pertanyaan Anda via email</p>
        <a href="mailto:support@trusequip.id" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors text-sm inline-block">
          Email Support
        </a>
      </div>

      <div class="bg-card border rounded-lg p-6 text-center">
        <div class="w-12 h-12 rounded-full bg-primary/20 text-primary flex items-center justify-center mx-auto mb-4">
          <Phone :size="24" />
        </div>
        <h3 class="font-semibold mb-2">Telepon</h3>
        <p class="text-sm text-muted-foreground mb-4">Hubungi kami via telepon (09:00-17:00)</p>
        <a href="tel:+6221234567" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors text-sm inline-block">
          +62-21-234567
        </a>
      </div>
    </div>
  </div>
</template>
