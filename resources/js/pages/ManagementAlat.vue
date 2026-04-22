<template>
  <CrudTablePage
    title="Management Alat"
    subtitle="Kelola data alat, stok, kondisi, dan lokasi penyimpanan dari database."
    entity-label="alat"
    storage-key="admin-management-tools"
    :api="{ endpoint: '/api/tools' }"
    :fields="fields"
    :columns="columns"
    :summary-cards="summaryCards"
    primary-field="namaAlat"
    :search-keys="['namaAlat', 'kategori', 'status', 'kondisi', 'lokasi']"
  />
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import CrudTablePage from '../components/admin/CrudTablePage.vue'
import { apiRequest } from '../lib/api'

const categoryOptions = ref([])

onMounted(async () => {
  try {
    const categories = await apiRequest('/api/categories?status=Aktif')

    categoryOptions.value = Array.isArray(categories)
      ? categories.map((category) => ({
          label: category.namaKategori,
          value: category.id,
        }))
      : []
  } catch (error) {
    categoryOptions.value = []
  }
})

const fields = computed(() => {
  return [
    {
      key: 'namaAlat',
      label: 'Nama Alat',
      type: 'text',
      placeholder: 'Contoh: Multimeter Digital',
      required: true,
    },
    {
      key: 'category_id',
      label: 'Kategori',
      type: 'select',
      options: categoryOptions.value.length ? categoryOptions.value : [],
      required: true,
      help: 'Pilihan kategori membaca data aktif dari database.',
    },
    {
      key: 'stok',
      label: 'Stok',
      type: 'number',
      min: 0,
      step: 1,
      placeholder: '0',
      required: true,
    },
    {
      key: 'kondisi',
      label: 'Kondisi',
      type: 'select',
      options: ['Baik', 'Perlu Kalibrasi', 'Rusak Ringan', 'Rusak Berat'],
      required: true,
    },
    {
      key: 'status',
      label: 'Status',
      type: 'select',
      options: ['Tersedia', 'Dipinjam', 'Maintenance'],
      required: true,
    },
    {
      key: 'lokasi',
      label: 'Lokasi Simpan',
      type: 'text',
      placeholder: 'Contoh: Gudang A',
      required: true,
    },
    {
      key: 'gambar',
      label: 'Gambar Alat',
      type: 'image',
      required: false,
      help: 'Opsional. Gambar disimpan di database.',
    },
  ]
})

const columns = [
  {
    key: 'namaAlat',
    label: 'Nama Alat',
  },
  {
    key: 'kategori',
    label: 'Kategori',
  },
  {
    key: 'stok',
    label: 'Stok',
    align: 'right',
    format: (value) => `${value} unit`,
  },
  {
    key: 'kondisi',
    label: 'Kondisi',
    type: 'badge',
    badges: {
      Baik: 'emerald',
      'Perlu Kalibrasi': 'amber',
      'Rusak Ringan': 'amber',
      'Rusak Berat': 'rose',
    },
  },
  {
    key: 'status',
    label: 'Status',
    type: 'badge',
    badges: {
      Tersedia: 'emerald',
      Dipinjam: 'cyan',
      Maintenance: 'rose',
    },
  },
  {
    key: 'lokasi',
    label: 'Lokasi',
  },
]

const summaryCards = [
  {
    label: 'Total Alat',
    value: (items) => items.length,
    tone: 'cyan',
    caption: 'Jumlah item alat yang saat ini tercatat di dashboard.',
  },
  {
    label: 'Stok Tersedia',
    value: (items) => items.filter((item) => item.status === 'Tersedia').reduce((total, item) => total + Number(item.stok || 0), 0),
    tone: 'emerald',
    caption: 'Akumulasi stok dari alat yang statusnya tersedia.',
  },
  {
    label: 'Perlu Perhatian',
    value: (items) => items.filter((item) => item.kondisi !== 'Baik' || item.status === 'Maintenance').length,
    tone: 'rose',
    caption: 'Alat dengan kondisi atau status yang perlu ditindaklanjuti.',
  },
]
</script>
