<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

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
  <div class="print-report">
    <!-- Loading State -->
    <div v-if="loading || !statistics" class="text-center py-12">
      <p class="text-muted-foreground">Memuat laporan...</p>
    </div>

    <!-- Formal Print Document -->
    <template v-else>
      <!-- Print Header -->
      <div class="print-header">
        <!-- Company Header -->
        <div class="text-center mb-8 border-b-2 border-black pb-6">
          <div class="mb-3">
            <div class="inline-block text-3xl font-bold text-black tracking-tight">TRUSTEQUIP</div>
          </div>
          <p class="text-sm text-gray-700 font-medium">Sistem Manajemen Peminjaman Alat Profesional</p>
          <p class="text-xs text-gray-600 mt-1">Laporan Resmi Kegiatan Peminjaman dan Pengembalian</p>
        </div>

        <!-- Report Title and Metadata -->
        <div class="mb-6">
          <h2 class="text-center text-xl font-bold uppercase tracking-wide text-black mb-4">LAPORAN STATISTIK SISTEM PEMINJAMAN ALAT</h2>
          
          <div class="grid grid-cols-2 gap-8 text-sm">
            <div class="font-serif">
              <p class="text-gray-700"><span class="font-bold">Nomor Laporan:</span> {{ reportNumber }}</p>
              <p class="text-gray-700 mt-2"><span class="font-bold">Tanggal Laporan:</span> {{ formatLongDate() }}</p>
              <p class="text-gray-700 mt-2"><span class="font-bold">Waktu Cetak:</span> {{ formatPrintDate() }}</p>
            </div>
            <div class="font-serif text-right">
              <p class="text-gray-700"><span class="font-bold">Dicetak oleh:</span> {{ currentUser?.username || 'Administrator' }}</p>
              <p class="text-gray-700 mt-2"><span class="font-bold">Status:</span> Laporan Resmi</p>
              <p class="text-gray-700 mt-2"><span class="font-bold">Periode:</span> Sepanjang Masa</p>
            </div>
          </div>
        </div>

        <div class="border-t-2 border-b-2 border-black py-3 my-6"></div>
      </div>

      <!-- Main Statistics Table -->
      <div class="mb-8" v-if="statistics">
        <h3 class="text-lg font-bold text-black mb-4 uppercase tracking-wide">1. RINGKASAN UTAMA STATISTIK</h3>
        
        <table class="w-full border-collapse mb-6">
          <thead>
            <tr class="bg-gray-200 border-2 border-black">
              <th class="border border-black px-4 py-3 text-left text-sm font-bold text-black">No.</th>
              <th class="border border-black px-4 py-3 text-left text-sm font-bold text-black">Deskripsi</th>
              <th class="border border-black px-4 py-3 text-right text-sm font-bold text-black">Jumlah</th>
              <th class="border border-black px-4 py-3 text-left text-sm font-bold text-black">Keterangan</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-2 border-black">
              <td class="border border-black px-4 py-3 text-sm font-medium text-black">1</td>
              <td class="border border-black px-4 py-3 text-sm text-black">Total Peminjaman Sepanjang Masa</td>
              <td class="border border-black px-4 py-3 text-right text-sm font-bold text-black">{{ statistics.total_borrowings }}</td>
              <td class="border border-black px-4 py-3 text-sm text-gray-700">Seluruh transaksi peminjaman</td>
            </tr>
            <tr class="border-2 border-black bg-red-50">
              <td class="border border-black px-4 py-3 text-sm font-medium text-black">2</td>
              <td class="border border-black px-4 py-3 text-sm text-black">Peminjaman Terlambat</td>
              <td class="border border-black px-4 py-3 text-right text-sm font-bold text-red-700">{{ statistics.overdue_count }}</td>
              <td class="border border-black px-4 py-3 text-sm text-gray-700">Peminjaman belum dikembalikan tepat waktu</td>
            </tr>
            <tr class="border-2 border-black">
              <td class="border border-black px-4 py-3 text-sm font-medium text-black">3</td>
              <td class="border border-black px-4 py-3 text-sm text-black">Peminjaman Aktif</td>
              <td class="border border-black px-4 py-3 text-right text-sm font-bold text-black">{{ statistics.active_borrowings }}</td>
              <td class="border border-black px-4 py-3 text-sm text-gray-700">Peminjaman sedang berjalan</td>
            </tr>
            <tr class="border-2 border-black">
              <td class="border border-black px-4 py-3 text-sm font-medium text-black">4</td>
              <td class="border border-black px-4 py-3 text-sm text-black">Peminjaman Selesai</td>
              <td class="border border-black px-4 py-3 text-right text-sm font-bold text-black">{{ statistics.completed_borrowings }}</td>
              <td class="border border-black px-4 py-3 text-sm text-gray-700">Peminjaman telah dikembalikan</td>
            </tr>
            <tr class="border-2 border-black bg-green-50">
              <td class="border border-black px-4 py-3 text-sm font-medium text-black">5</td>
              <td class="border border-black px-4 py-3 text-sm text-black font-bold">Tingkat Kepatuhan</td>
              <td class="border border-black px-4 py-3 text-right text-sm font-bold text-green-700">{{ (statistics.compliance_rate * 100).toFixed(1) }}%</td>
              <td class="border border-black px-4 py-3 text-sm text-gray-700">Tingkat pengembalian tepat waktu</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Equipment and System Statistics -->
      <div class="mb-8" v-if="statistics">
        <h3 class="text-lg font-bold text-black mb-4 uppercase tracking-wide">2. STATISTIK ALAT DAN SISTEM</h3>
        
        <table class="w-full border-collapse">
          <thead>
            <tr class="bg-gray-200 border-2 border-black">
              <th class="border border-black px-4 py-3 text-left text-sm font-bold text-black">No.</th>
              <th class="border border-black px-4 py-3 text-left text-sm font-bold text-black">Aspek Sistem</th>
              <th class="border border-black px-4 py-3 text-right text-sm font-bold text-black">Jumlah</th>
              <th class="border border-black px-4 py-3 text-left text-sm font-bold text-black">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-2 border-black">
              <td class="border border-black px-4 py-3 text-sm font-medium text-black">1</td>
              <td class="border border-black px-4 py-3 text-sm text-black">Total Alat Terdaftar</td>
              <td class="border border-black px-4 py-3 text-right text-sm font-bold text-black">{{ statistics.total_equipment }}</td>
              <td class="border border-black px-4 py-3 text-sm text-gray-700">Operasional</td>
            </tr>
            <tr class="border-2 border-black">
              <td class="border border-black px-4 py-3 text-sm font-medium text-black">2</td>
              <td class="border border-black px-4 py-3 text-sm text-black">Total Kategori Alat</td>
              <td class="border border-black px-4 py-3 text-right text-sm font-bold text-black">{{ statistics.total_categories }}</td>
              <td class="border border-black px-4 py-3 text-sm text-gray-700">Terdaftar dalam sistem</td>
            </tr>
            <tr class="border-2 border-black">
              <td class="border border-black px-4 py-3 text-sm font-medium text-black">3</td>
              <td class="border border-black px-4 py-3 text-sm text-black">Total Pengguna Aktif</td>
              <td class="border border-black px-4 py-3 text-right text-sm font-bold text-black">{{ statistics.total_active_users }}</td>
              <td class="border border-black px-4 py-3 text-sm text-gray-700">Pengguna sistem</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Notes and Remarks -->
      <div class="mb-12 border-2 border-black p-4 bg-gray-50">
        <h3 class="text-sm font-bold text-black mb-3 uppercase">CATATAN DAN KETERANGAN</h3>
        <ul class="text-xs text-gray-700 space-y-2 list-disc pl-5">
          <li>Laporan ini merupakan ringkasan statistik sistem peminjaman alat TrustEquip yang dihasilkan secara otomatis.</li>
          <li>Data yang ditampilkan adalah data akumulatif sepanjang periode pengoperasian sistem.</li>
          <li>Tingkat Kepatuhan dihitung dari perbandingan peminjaman tepat waktu terhadap total peminjaman.</li>
          <li>Untuk informasi detail lebih lanjut, silakan hubungi administrator sistem.</li>
          <li>Dokumen ini adalah laporan resmi dan berlaku sesuai dengan tanggal cetak yang tercantum.</li>
        </ul>
      </div>

      <!-- Signature Section -->
      <div class="grid grid-cols-3 gap-8 mt-16 pt-8">
        <div class="text-center border-t-2 border-black pt-3">
          <p class="text-xs font-medium text-black mb-12">Dibuat oleh</p>
          <p class="text-xs font-bold text-black">{{ currentUser?.username || '________________' }}</p>
          <p class="text-xs text-gray-700">Admin Sistem</p>
        </div>
        
        <div class="text-center">
          <p class="text-xs font-medium text-black mb-4">Tanggal Laporan</p>
          <p class="text-lg font-bold text-black">{{ formatLongDate() }}</p>
        </div>
        
        <div class="text-center border-t-2 border-black pt-3">
          <p class="text-xs font-medium text-black mb-12">Diketahui oleh</p>
          <p class="text-xs font-bold text-black">________________</p>
          <p class="text-xs text-gray-700">Kepala Unit / Manager</p>
        </div>
      </div>

      <!-- Footer -->
      <div class="mt-16 pt-8 border-t-2 border-black text-center text-xs text-gray-600 print-footer">
        <p class="font-medium text-black mb-2">SISTEM MANAJEMEN PEMINJAMAN ALAT TRUSTEQUIP</p>
        <p>Dokumen Resmi © 2026 | Nomor Laporan: {{ reportNumber }} | Halaman <span class="page-number">1</span></p>
        <p class="mt-1 text-gray-600">Laporan ini dicetak secara digital dan berlaku tanpa tanda tangan asli</p>
      </div>
    </template>
  </div>
</template>

<style scoped>
body {
  margin: 0;
  padding: 0;
  background: white;
  font-family: Arial, sans-serif;
}

.print-report {
  width: 100%;
  max-width: 100%;
  margin: 0;
  padding: 0;
  background: white;
  color: black;
  font-family: Arial, sans-serif;
}

/* Print styles - optimized for printing */
@media print {
  body, html {
    margin: 0 !important;
    padding: 0 !important;
    background: white !important;
    width: 100% !important;
    height: 100% !important;
    overflow: hidden !important;
  }

  .print-report {
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    background: white !important;
    color: black !important;
    display: block !important;
    overflow: hidden !important;
  }

  @page {
    size: A4 portrait;
    margin: 15mm 15mm 20mm 15mm;
  }

  /* Typography */
  h1, h2, h3, h4, h5, h6 {
    color: black !important;
    margin: 0.5rem 0 !important;
    page-break-after: avoid;
    font-weight: bold;
    font-family: Arial, sans-serif;
  }

  h2 {
    font-size: 18px !important;
  }

  h3 {
    font-size: 14px !important;
    margin-top: 1rem !important;
  }

  p {
    color: black !important;
    margin: 0.25rem 0 !important;
    line-height: 1.4 !important;
  }

  /* Table styles */
  table {
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 1.5rem;
    page-break-inside: avoid;
    border: 2px solid black;
  }

  thead {
    background-color: #e8e8e8 !important;
    color: black !important;
  }

  thead tr {
    border: 2px solid black !important;
  }

  th {
    border: 1px solid black !important;
    padding: 12px 10px !important;
    text-align: left !important;
    font-weight: bold !important;
    color: black !important;
    font-size: 12px !important;
    background-color: #e8e8e8 !important;
  }

  tbody tr {
    page-break-inside: avoid;
    border: 2px solid black !important;
  }

  td {
    border: 1px solid black !important;
    padding: 10px !important;
    color: black !important;
    font-size: 11px !important;
  }

  tr.bg-red-50 {
    background-color: #fff0f0 !important;
  }

  tr.bg-green-50 {
    background-color: #f0fff0 !important;
  }

  /* Text alignment */
  td:nth-child(3),
  th:nth-child(3) {
    text-align: right !important;
  }

  /* Notes section */
  .border-2.border-black {
    border: 2px solid black !important;
    padding: 12px !important;
    background-color: white !important;
    page-break-inside: avoid;
  }

  /* List styles */
  ul {
    margin: 8px 0 !important;
    padding-left: 20px !important;
  }

  li {
    margin: 4px 0 !important;
    color: black !important;
    font-size: 11px !important;
  }

  /* Signature section */
  .grid {
    page-break-inside: avoid;
    margin-top: 2rem !important;
  }

  .text-center {
    text-align: center;
  }

  .border-t-2.border-black {
    border-top: 2px solid black !important;
    padding-top: 12px !important;
  }

  /* Footer */
  .print-footer {
    margin-top: 1.5rem !important;
    padding-top: 1rem !important;
    border-top: 2px solid black !important;
    font-size: 10px !important;
    color: black !important;
  }

  .print-footer p {
    margin: 4px 0 !important;
    font-size: 10px !important;
  }

  /* Spacing */
  .mb-8 { margin-bottom: 1.2rem !important; }
  .mb-6 { margin-bottom: 1rem !important; }
  .mb-4 { margin-bottom: 0.8rem !important; }
  .mb-3 { margin-bottom: 0.6rem !important; }
  .mt-16 { margin-top: 2rem !important; }
  .pt-8 { padding-top: 1.5rem !important; }
  .pt-3 { padding-top: 0.5rem !important; }
  .mb-12 { margin-bottom: 1.5rem !important; }

  /* Text sizes */
  .text-xs { font-size: 10px !important; }
  .text-sm { font-size: 12px !important; }
  .text-lg { font-size: 16px !important; }
  .text-xl { font-size: 18px !important; }

  .font-bold { font-weight: bold !important; }
  .font-medium { font-weight: 500 !important; }

  /* Colors */
  .text-black { color: black !important; }
  .text-gray-600,
  .text-gray-700 { color: #333333 !important; }
  .text-red-700 { color: #d32f2f !important; }
  .text-green-700 { color: #2e7d32 !important; }

  /* Prevent breaks */
  .print-header,
  table,
  .border-2 {
    page-break-inside: avoid;
  }

  /* Grid layout for signatures */
  .grid.grid-cols-3 {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 2rem;
  }
}
</style>
