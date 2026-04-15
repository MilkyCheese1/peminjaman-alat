<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui'
import { BarChart3, Printer } from 'lucide-vue-next'
import axios from 'axios'

const loading = ref(false)
const statistics = ref<any>(null)
const error = ref('')

onMounted(async () => {
  await loadReports()
})

const loadReports = async () => {
  try {
    loading.value = true
    const response = await axios.get('/api/statistics/detailed')
    statistics.value = response.data.data || {}
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
</script>

<template>
  <div>
    <!-- Header with Print Button -->
    <div class="mb-8 flex items-center justify-between">
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
        Cetak
      </button>
    </div>

    <!-- Error Message -->
    <div v-if="error" class="bg-destructive/10 border border-destructive text-destructive px-4 py-3 rounded-lg mb-6">
      {{ error }}
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <p class="text-muted-foreground">Memuat laporan...</p>
    </div>

    <!-- Reports Content -->
    <template v-else>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <Card v-if="statistics">
          <CardHeader class="pb-2">
            <CardTitle class="text-sm font-medium text-muted-foreground">Total Peminjaman</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ statistics.total_borrowings || 0 }}</div>
            <p class="text-xs text-muted-foreground mt-1">Sepanjang waktu</p>
          </CardContent>
        </Card>

        <Card v-if="statistics">
          <CardHeader class="pb-2">
            <CardTitle class="text-sm font-medium text-muted-foreground">Terlambat</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold text-destructive">{{ statistics.overdue_count || 0 }}</div>
            <p class="text-xs text-muted-foreground mt-1">Peminjaman terlambat</p>
          </CardContent>
        </Card>

        <Card v-if="statistics">
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

      <!-- Detailed Reports -->
      <div class="grid grid-cols-1 gap-6">
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
  </div>
</template>

<style scoped>
/* Print styles */
@media print {
  .no-print {
    display: none !important;
  }

  :deep(*) {
    background: white !important;
    color: black !important;
    border-color: #ccc !important;
  }

  :deep(button),
  :deep(input[type="checkbox"]) {
    display: none !important;
  }

  h1, h2, h3 {
    page-break-after: avoid;
    color: black !important;
  }

  :deep(.card) {
    page-break-inside: avoid;
    border: 1px solid #ccc !important;
  }

  @page {
    margin: 1cm;
  }
}
</style>
