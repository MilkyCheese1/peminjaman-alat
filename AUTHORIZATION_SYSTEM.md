# 📋 Sistem Otoritas Dashboard TrustEquip

Dokumen ini menjelaskan sistem otoritas (authorization) untuk dashboard berdasarkan role pengguna.

## 📊 Ringkasan Fitur per Role

| Fitur | Admin | Petugas | Peminjam |
|-------|:-----:|:-------:|:-------:|
| **Login** | ✓ | ✓ | ✓ |
| **Logout** | ✓ | ✓ | ✓ |
| **CRUD User** | ✓ | ✗ | ✗ |
| **CRUD Alat** | ✓ | ✗ | ✗ |
| **CRUD Kategori** | ✓ | ✗ | ✗ |
| **CRUD Data Peminjaman** | ✓ | ✗ | ✗ |
| **CRUD Pengembalian** | ✓ | ✗ | ✗ |
| **Log Aktivitas** | ✓ | ✗ | ✗ |
| **Menyetujui Peminjaman** | ✗ | ✓ | ✗ |
| **Memeriksa Pengembalian** | ✗ | ✓ | ✗ |
| **Mencetak Laporan** | ✗ | ✓ | ✗ |
| **Melihat Daftar Alat** | ✗ | ✗ | ✓ |
| **Mengajukan Peminjaman** | ✗ | ✗ | ✓ |
| **Mengembalikan Alat** | ✗ | ✗ | ✓ |

## 🔐 Detail Role

### 👨‍💻 Administrator (Admin)
**Deskripsi:** Pengelola sistem keseluruhan dengan akses penuh

**Akses Dashboard Tabs:**
- 📊 Overview (Beranda)
- 👥 Users (Kelola Pengguna)
- 📋 Borrowings (Kelola Peminjaman)
- 📦 Items (Kelola Alat)
- ↩️ Returns (Kelola Pengembalian)
- 📈 Reports (Laporan)
- 📜 Activity Logs (Log Aktivitas)
- ⚙️ Settings (Pengaturan)
- ❓ Help (Bantuan)

**Permissions:**
```javascript
CRUD User Management: ✓
CRUD Equipment Management: ✓
CRUD Category Management: ✓
CRUD Borrowing Management: ✓
CRUD Return Management: ✓
View Activity Logs: ✓
Approve/Reject Borrowing: ✓
Verify Returns: ✓
View & Print Reports: ✓
```

---

### 👨‍🔧 Staff / Petugas
**Deskripsi:** Pekerja operasional untuk memproses peminjaman dan pengembalian

**Akses Dashboard Tabs:**
- 📊 Overview (Beranda)
- ✅ Approvals (Persetujuan Peminjaman)
- 🔍 Verifications (Verifikasi Pengembalian)
- 📋 Reports (Cetak Laporan)
- 👤 Profile (Profil Saya)
- ❓ Help (Bantuan)

**Permissions:**
```javascript
View All Equipment: ✓
Approve Borrowing Requests: ✓
Reject Borrowing Requests: ✓
Verify Equipment Returns: ✓
View & Print Reports: ✓
View Profile: ✓
Edit Profile: ✓
View Borrowing History: ✓
```

---

### 👨‍🎓 Customer / Peminjam
**Deskripsi:** Pengguna umum yang dapat meminjam dan mengembalikan alat

**Akses Dashboard Tabs:**
- 📊 Overview (Beranda)
- 🛍️ Explore (Jelajahi Alat)
- 📦 My Borrowings (Peminjaman Saya)
- ⭐ Recommendations (Rekomendasi)
- 👤 Profile (Profil Saya)
- ❓ Help (Bantuan)

**Permissions:**
```javascript
View All Equipment: ✓
Request Borrowing: ✓
Return Equipment: ✓
Extend Borrowing: ✓
View Profile: ✓
Edit Profile: ✓
View Borrowing History: ✓
```

---

## 🔧 Penggunaan dalam Komponen

### Cek Permission Tunggal
```vue
<script setup>
import { hasPermission } from '@/data/rolePermissions'

const userRole = 'admin'

// Cek izin spesifik
if (hasPermission(userRole, 'crud_user')) {
  console.log('User dapat mengelola user')
}
</script>

<template>
  <!-- Tampilkan hanya jika punya permission -->
  <button v-if="hasPermission(userRole, 'crud_equipment')">
    Tambah Alat
  </button>
</template>
```

### Cek Multiple Permissions
```vue
<script setup>
import { hasAllPermissions, hasAnyPermission } from '@/data/rolePermissions'

const userRole = 'admin'

// AND logic - semua harus true
const canManageAll = hasAllPermissions(userRole, [
  'crud_equipment',
  'crud_category',
  'manage_stock'
])

// OR logic - minimal satu true
const canManageData = hasAnyPermission(userRole, [
  'crud_equipment',
  'crud_borrowing',
  'crud_return'
])
</script>
```

### Conditional Rendering Berdasarkan Role
```vue
<script setup>
import { isAdminOrOwner, isStaff, isCustomer } from '@/data/rolePermissions'

const userRole = 'staff'
</script>

<template>
  <!-- Admin/Owner Section -->
  <section v-if="isAdminOrOwner(userRole)">
    <h2>Admin Panel</h2>
    <!-- Admin content -->
  </section>

  <!-- Staff Section -->
  <section v-if="isStaff(userRole)">
    <h2>Staff Dashboard</h2>
    <!-- Staff content -->
  </section>

  <!-- Customer Section -->
  <section v-if="isCustomer(userRole)">
    <h2>My Borrowings</h2>
    <!-- Customer content -->
  </section>
</template>
```

### Mengakses Role Information
```javascript
import { 
  getRoleLabel, 
  getRoleColor, 
  getRoleIcon,
  getRolePermissions 
} from '@/data/rolePermissions'

// Dapatkan label role
const label = getRoleLabel('admin')  // "Administrator"

// Dapatkan warna role
const color = getRoleColor('staff')  // "#45B7D1"

// Dapatkan icon role
const icon = getRoleIcon('customer') // "👨‍🎓"

// Dapatkan semua permissions
const permissions = getRolePermissions('admin')
/*
{
  login: true,
  logout: true,
  crud_user: true,
  ...
}
*/
```

---

## 📁 File Struktur

```
resources/js/data/
├── rolePermissions.js          # Sistem otoritas utama
├── permissions.js              # Permission constants (opsional)
└── dummyUsers.js              # Data user dengan role

resources/js/pages/
├── DashboardRoleAware.vue      # Dashboard wrapper (role-based)
└── Dashboard.vue               # Default dashboard

resources/js/components/dashboards/
├── AdminDashboard.vue          # Admin-specific components
├── StaffDashboard.vue          # Staff-specific components
├── CustomerDashboard.vue       # Customer-specific components
└── OwnerDashboard.vue          # Owner-specific components
```

---

## 🚀 Implementasi

### 1. Import Permission System
```javascript
import { 
  hasPermission, 
  getAllowedTabs,
  getRoleColor,
  getRoleLabel 
} from '@/data/rolePermissions'
```

### 2. Check Permission di Frontend
```vue
<button v-if="hasPermission(userRole, 'crud_user')">
  Manage Users
</button>
```

### 3. Conditional Display Tabs
```vue
<button v-for="tab in getAllowedTabs(userRole)" :key="tab">
  {{ tab }}
</button>
```

### 4. Style berdasarkan Role
```vue
<div :style="{ color: getRoleColor(userRole) }">
  {{ getRoleLabel(userRole) }}
</div>
```

---

## 📝 Catatan

- **Owner** dikeluarkan dari sistem permission normal (sesuai request)
- Semua role dapat **Login** dan **Logout**
- Permission check dilakukan **di frontend** untuk UX yang lebih baik
- Untuk keamanan backend, harus ada validasi tambahan di API
- Setiap tab dashboard harus check permission sebelum render content

---

## 🔄 Update Permission

Untuk menambah atau mengubah permission:

1. Edit file `resources/js/data/rolePermissions.js`
2. Tambahkan permission baru di section yang sesuai
3. Update tabel di dokumen ini
4. Jalankan validasi di komponen

---

**Dibuat:** April 2, 2026  
**Update Terakhir:** April 2, 2026  
**Status:** Active ✓
