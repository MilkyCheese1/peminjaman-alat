<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui'
import { BarChart3, Printer } from 'lucide-vue-next'
import axios from 'axios'
import PrintReports from './PrintReports.vue'

const loading = ref(false)
const statistics = ref<any>(null)
const error = ref('')
const currentUser = ref<any>(null)
const reportNumber = ref('')

onMounted(async () => {
  await loadReports()
  loadCurrentUser()
  generateReportNumber()
})

const loadCurrentUser = () => {
  try {
    const userStr = localStorage.getItem('user')
    if (userStr) {
      currentUser.value = JSON.parse(userStr)
    }
  } catch (err) {
    console.error('Failed to load user:', err)
  }
}

const generateReportNumber = () => {
  const now = new Date()
  const year = now.getFullYear()
  const month = String(now.getMonth() + 1).padStart(2, '0')
  const day = String(now.getDate()).padStart(2, '0')
  const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0')
  reportNumber.value = `TR-${year}${month}${day}-${random}`
}

const loadReports = async () => {
  try {
    loading.value = true
    const response = await axios.get('/api/statistics/detailed')
    const data = response.data.data || {}
    
    // Flatten and calculate compliance rate
    statistics.value = {
      total_borrowings: data.borrowings?.total || 0,
      overdue_count: data.borrowings?.overdue || 0,
      compliance_rate: data.borrowings?.total > 0 
        ? ((data.borrowings.total - data.borrowings.overdue) / data.borrowings.total)
        : 1,
      active_borrowings: data.borrowings?.active || 0,
      completed_borrowings: data.borrowings?.completed || 0,
      total_equipment: data.equipment?.total || 0,
      total_active_users: data.users?.active || 0,
      total_categories: data.equipment?.categories || 0,
    }
  } catch (err: any) {
    error.value = err.message
    console.error('Failed to load reports:', err)
  } finally {
    loading.value = false
  }
}

const handlePrint = () => {
  window.print()
}

const formatPrintDate = () => {
  const now = new Date()
  return now.toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const formatLongDate = () => {
  const now = new Date()
  return now.toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}
</script>

<template>
  <div>
    <!-- Header with Print Button (Screen view) -->
    <div class="mb-8 flex items-center justify-between no-print">
      <div>
        <h1 class="text-3xl font-bold text-foreground flex items-center gap-2">
          <BarChart3 :size="32" />
          Laporan & Statistik
        </h1>
        <p class="text-muted-foreground mt-1">Lihat laporan detail dan statistik sistem</p>
      </div>
      <button
        @click="handlePrint"
        class="flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90 transition-colors font-medium"
      >
        <Printer :size="20" />
        Cetak Laporan
      </button>
    </div>

    <!-- Error Message -->
    <div v-if="error" class="bg-destructive/10 border border-destructive text-destructive px-4 py-3 rounded-lg mb-6 no-print">
      {{ error }}
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <p class="text-muted-foreground">Memuat laporan...</p>
    </div>

    <!-- Screen View Statistics (Hidden on Print) -->
    <template v-if="!loading && statistics">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 no-print">
        <Card>
          <CardHeader class="pb-2">
            <CardTitle class="text-sm font-medium text-muted-foreground">Total Peminjaman</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ statistics.total_borrowings || 0 }}</div>
            <p class="text-xs text-muted-foreground mt-1">Sepanjang waktu</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader class="pb-2">
            <CardTitle class="text-sm font-medium text-muted-foreground">Terlambat</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold text-destructive">{{ statistics.overdue_count || 0 }}</div>
            <p class="text-xs text-muted-foreground mt-1">Peminjaman terlambat</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader class="pb-2">
            <CardTitle class="text-sm font-medium text-muted-foreground">Tingkat Kepatuhan</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">
              {{ statistics.compliance_rate ? (statistics.compliance_rate * 100).toFixed(1) : 0 }}%
            </div>
            <p class="text-xs text-muted-foreground mt-1">Pengguna tepat waktu</p>
          </CardContent>
        </Card>
      </div>

      <div class="grid grid-cols-1 gap-6 no-print">
        <Card>
          <CardHeader>
            <CardTitle>Ringkasan Aktivitas</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="space-y-4">
              <div v-if="statistics" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-4 bg-background rounded-lg">
                  <p class="text-sm text-muted-foreground">Total Alat</p>
                  <p class="text-xl font-bold">{{ statistics.total_equipment || 0 }}</p>
                </div>
                <div class="p-4 bg-background rounded-lg">
                  <p class="text-sm text-muted-foreground">Pengguna Aktif</p>
                  <p class="text-xl font-bold">{{ statistics.total_active_users || 0 }}</p>
                </div>
                <div class="p-4 bg-background rounded-lg">
                  <p class="text-sm text-muted-foreground">Kategori</p>
                  <p class="text-xl font-bold">{{ statistics.total_categories || 0 }}</p>
                </div>
                <div class="p-4 bg-background rounded-lg">
                  <p class="text-sm text-muted-foreground">Aktif Hari Ini</p>
                  <p class="text-xl font-bold">{{ statistics.active_borrowings || 0 }}</p>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </template>

    <!-- Empty State -->
    <div v-if="!loading && statistics && statistics.total_borrowings === 0" class="text-center py-12 no-print">
      <BarChart3 class="mx-auto mb-4 text-muted-foreground" :size="48" />
      <p class="text-muted-foreground">Tidak ada data laporan</p>
    </div>

    <!-- Hidden Print Document (Only rendered when printing) -->
    <div class="print-document">
      <PrintReports />
    </div>
  </div>
</template>

<style scoped>
/* Hidden on screen, visible when printing */
.print-document {
  display: none;
}

@media print {
  /* Hide all screen content during print */
  .no-print {
    display: none !important;
  }

  /* Show only print document */
  .print-document {
    display: block !important;
    margin: 0 !important;
    padding: 0 !important;
    background: white !important;
  }

  body {
    margin: 0 !important;
    padding: 0 !important;
    background: white !important;
  }

  /* A4 Print Settings */
  @page {
    size: A4 portrait;
    margin: 15mm;
  }
}
</style>
