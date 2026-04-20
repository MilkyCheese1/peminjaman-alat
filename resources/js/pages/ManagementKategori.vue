<template>
  <CrudTablePage
    title="Management Kategori"
    subtitle="Susun kategori alat agar pengelolaan inventaris tetap rapi dan mudah dicari."
    entity-label="kategori"
    storage-key="admin-management-categories"
    :fields="fields"
    :columns="columns"
    :summary-cards="summaryCards"
    primary-field="namaKategori"
    :search-keys="['namaKategori', 'kodeKategori', 'status', 'deskripsi']"
  />
</template>

<script setup>
import CrudTablePage from '../components/admin/CrudTablePage.vue'

const fields = [
  {
    key: 'namaKategori',
    label: 'Nama Kategori',
    type: 'text',
    placeholder: 'Contoh: Elektronik',
    required: true,
  },
  {
    key: 'kodeKategori',
    label: 'Kode Kategori',
    type: 'text',
    placeholder: 'Contoh: ELK',
    required: true,
  },
  {
    key: 'status',
    label: 'Status',
    type: 'select',
    options: ['Aktif', 'Nonaktif'],
    required: true,
  },
  {
    key: 'deskripsi',
    label: 'Deskripsi',
    type: 'textarea',
    placeholder: 'Jelaskan isi kategori ini',
    required: true,
    rows: 5,
  },
  {
    key: 'gambar',
    label: 'Gambar Kategori',
    type: 'image',
    required: false,
    help: 'Opsional. Gambar disimpan di browser (localStorage).',
  },
]

const columns = [
  {
    key: 'namaKategori',
    label: 'Nama Kategori',
  },
  {
    key: 'kodeKategori',
    label: 'Kode',
  },
  {
    key: 'status',
    label: 'Status',
    type: 'badge',
    badges: {
      Aktif: 'emerald',
      Nonaktif: 'slate',
    },
  },
  {
    key: 'deskripsi',
    label: 'Deskripsi',
    format: (value) => (value && value.length > 56 ? `${value.slice(0, 56)}...` : value),
  },
]

const summaryCards = [
  {
    label: 'Total Kategori',
    value: (items) => items.length,
    tone: 'cyan',
    caption: 'Jumlah kategori alat yang sedang dikelola.',
  },
  {
    label: 'Kategori Aktif',
    value: (items) => items.filter((item) => item.status === 'Aktif').length,
    tone: 'emerald',
    caption: 'Kategori yang tersedia untuk dipakai oleh data alat.',
  },
  {
    label: 'Kode Unik',
    value: (items) => new Set(items.map((item) => item.kodeKategori)).size,
    tone: 'amber',
    caption: 'Membantu cek konsistensi kode kategori inventaris.',
  },
]
</script>
