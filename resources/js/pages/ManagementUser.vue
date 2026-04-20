<template>
  <CrudTablePage
    title="Management User"
    subtitle="Kelola akun admin, owner, staff, dan peminjam langsung dari dashboard admin."
    entity-label="user"
    storage-key="admin-management-users"
    :fields="fields"
    :columns="columns"
    :summary-cards="summaryCards"
    primary-field="nama"
    :search-keys="['nama', 'email', 'role', 'status', 'telepon']"
  />
</template>

<script setup>
import CrudTablePage from '../components/admin/CrudTablePage.vue'

const fields = [
  {
    key: 'nama',
    label: 'Nama Lengkap',
    type: 'text',
    placeholder: 'Contoh: Siti Nurhaliza',
    required: true,
  },
  {
    key: 'email',
    label: 'Email',
    type: 'email',
    placeholder: 'nama@email.com',
    required: true,
  },
  {
    key: 'role',
    label: 'Role',
    type: 'select',
    options: ['Admin', 'Owner', 'Staff', 'Peminjam'],
    required: true,
  },
  {
    key: 'status',
    label: 'Status',
    type: 'select',
    options: ['Aktif', 'Nonaktif', 'Ditangguhkan'],
    required: true,
  },
  {
    key: 'telepon',
    label: 'Nomor Telepon',
    type: 'text',
    placeholder: '08xx-xxxx-xxxx',
    required: true,
  },
]

const columns = [
  {
    key: 'nama',
    label: 'Nama',
  },
  {
    key: 'email',
    label: 'Email',
  },
  {
    key: 'role',
    label: 'Role',
    type: 'badge',
    badges: {
      Admin: 'cyan',
      Owner: 'violet',
      Staff: 'emerald',
      Peminjam: 'amber',
    },
  },
  {
    key: 'status',
    label: 'Status',
    type: 'badge',
    badges: {
      Aktif: 'emerald',
      Nonaktif: 'slate',
      Ditangguhkan: 'rose',
    },
  },
  {
    key: 'telepon',
    label: 'Telepon',
  },
]

const summaryCards = [
  {
    label: 'Total User',
    value: (items) => items.length,
    tone: 'cyan',
    caption: 'Seluruh akun yang tersimpan di browser admin.',
  },
  {
    label: 'User Aktif',
    value: (items) => items.filter((item) => item.status === 'Aktif').length,
    tone: 'emerald',
    caption: 'Akun yang bisa langsung menggunakan sistem.',
  },
  {
    label: 'Akses Internal',
    value: (items) => items.filter((item) => ['Admin', 'Owner', 'Staff'].includes(item.role)).length,
    tone: 'violet',
    caption: 'Jumlah user internal untuk operasional dan kontrol.',
  },
]
</script>
