# DOKUMENTASI FITUR: KODE VERIFIKASI STRUK

**Tanggal dibuat:** 30 Maret 2026  
**Status:** ✅ Implementation Complete  
**Versi:** 1.0

---

## 📋 OVERVIEW

Fitur kode verifikasi struk adalah sistem keamanan untuk peminjaman alat dengan menggunakan kode unik yang: - ✅ Generated otomatis saat pemesanan disetujui
- ✅ Berlaku 1 jam dari waktu dibuat
- ✅ Bisa di-regenerate oleh customer
- ✅ Di-verify oleh staff saat checkout
- ✅ Format: Struk/Receipt yang printable
- ✅ Audit trail lengkap

---

## 🔄 ALUR PEMINJAMAN DENGAN KODE VERIFIKASI

```
┌─────────────────────────────────────────────────────────────────┐
│                    ALUR PEMINJAMAN LENGKAP                       │
└─────────────────────────────────────────────────────────────────┘

1. CUSTOMER: Klik "Pesan Sekarang"
   └─→ Status: pending
       └─→ POST /api/peminjaman
           └─→ Respons: ID peminjaman

2. STAFF/ADMIN: Review permintaan
   └─→ Status: pending → booked
       └─→ POST /api/peminjaman/{id}/approve-and-generate-code
           ├─→ Generate kode unik (misal: ABC123DEF456)
           ├─→ Set expired_at = sekarang + 1 jam
           ├─→ Log activity: APPROVE_WITH_CODE
           └─→ Respons: Kode verifikasi + expired_at

3. CUSTOMER: Lihat struk dan download
   ├─→ GET /api/peminjaman/{id}/verification-struk
   │   └─→ Return: Data struk terformat
   ├─→ GET /api/peminjaman/{id}/struk-html (optional)
   │   └─→ Return: HTML struk yang bisa didownload/print
   └─→ Customer bisa regenerate kode
       └─→ POST /api/peminjaman/{id}/regenerate-code
           ├─→ Generate kode baru
           ├─→ Reset expired_at
           ├─→ Increment regenerasi_count
           └─→ Log activity: REGENERATE_CODE

4. CUSTOMER: Datang dengan struk ke staff
   ├─→ Tunjukkan kode di struk
   ├─→ Staff verify kode
   │   └─→ POST /api/peminjaman/{id}/verify-checkout
   │       ├─→ Input: kode_verifikasi (dari struk)
   │       ├─→ Validate kode vs database
   │       ├─→ Check ekspiry (max 1 jam)
   │       ├─→ Update status: booked → in_use
   │       ├─→ Set kode_diverifikasi_at
   │       ├─→ Log activity: VERIFY_CHECKOUT
   │       └─→ Respons: Status updated
   └─→ Alat ready untuk dipinjam

5. CUSTOMER: Kembalikan alat
   └─→ Status: in_use → returned
       └─→ Proses return normal (tidak melibatkan kode)
```

---

## 🔌 API ENDPOINTS

### 1. Approve & Generate Kode
```
POST /api/peminjaman/{id_peminjaman}/approve-and-generate-code

Authorization: Bearer {token}
Role Required: petugas, admin, owner

Response 200 (Success):
{
  "success": true,
  "message": "Peminjaman disetujui dan kode verifikasi dibuat",
  "data": {
    "id_peminjaman": 1,
    "id_user": 5,
    "id_alat": 3,
    "tgl_peminjaman": "2026-03-30",
    "tgl_kembali": "2026-04-02",
    "status": "booked",
    "kode_verifikasi": "ABC123DEF456",
    "kode_dibuat_at": "2026-03-30 10:30:00",
    "kode_expired_at": "2026-03-30 11:30:00",
    "kode_regenerasi_count": 0
  },
  "kode_verifikasi": "ABC123DEF456",
  "expired_at": "2026-03-30 11:30:00"
}

Response 400 (Error):
{
  "success": false,
  "message": "Hanya peminjaman pending yang bisa diapprove"
}
```

### 2. Regenerate Kode
```
POST /api/peminjaman/{id_peminjaman}/regenerate-code

Authorization: Bearer {token}
Role: Bisa customer (pemesan) atau admin/owner

Request Body:
{
  // Tidak ada body yang diperlukan
}

Response 200 (Success):
{
  "success": true,
  "message": "Kode verifikasi berhasil diregenerate",
  "kode_verifikasi": "XYZ789ABC123",
  "expired_at": "2026-03-30 13:45:00",
  "regenerasi_count": 1
}

Response 400 (Error):
{
  "success": false,
  "message": "Anda tidak berhak meregenerasi kode ini"
}
```

### 3. Get Struk Verifikasi
```
GET /api/peminjaman/{id_peminjaman}/verification-struk

Authorization: Bearer {token}
Role: Semua (customer bisa lihat milik sendiri, staff/admin/owner bisa lihat semua)

Response 200 (Success):
{
  "success": true,
  "data": {
    "id_peminjaman": 1,
    "nomor_struk": "STR-000001",
    "kode_verifikasi": "ABC123DEF456",
    "kode_expired_at": "2026-03-30 11:30",
    "status_kode": "VALID",
    "pemesan": {
      "nama": "Muhammad Rian",
      "email": "rian@example.com",
      "telepon": "081234567890",
      "alamat": "Jl. Merdeka No. 123, Surabaya"
    },
    "alat": {
      "nama_alat": "Proyektor Sony VPL-FX30",
      "kategori": "Audio Visual",
      "kondisi": "tersedia"
    },
    "peminjaman": {
      "tgl_peminjaman": "30-03-2026",
      "tgl_kembali": "02-04-2026",
      "durasi_hari": 3,
      "status": "Sudah Dipesan",
      "tanpa_biaya": "Ya",
      "catatan": "Sistem peminjaman kelompok (kantor/sekolah)"
    },
    "created_at": "30-03-2026 10:30",
    "verified_at": null
  },
  "is_expired": false
}
```

### 4. Detail Peminjaman + Info Pemesan (Dashboard Staff)
```
GET /api/peminjaman/{id_peminjaman}/detail-with-borrower

Authorization: Bearer {token}
Role Required: petugas, admin, owner

Response 200 (Success):
{
  "success": true,
  "peminjaman": {
    "id_peminjaman": 1,
    "id_user": 5,
    "id_alat": 3,
    "status": "booked",
    "kode_verifikasi": "ABC123DEF456",
    "kode_dibuat_at": "2026-03-30 10:30:00",
    "kode_expired_at": "2026-03-30 11:30:00",
    ...
  },
  "pemesan_detail": {
    "id_user": 5,
    "nama": "Muhammad Rian",
    "email": "rian@example.com",
    "telepon": "081234567890",
    "alamat": "Jl. Merdeka No. 123, Surabaya",
    "role": "customer"
  },
  "kode_status": {
    "kode": "ABC123DEF456",
    "dibuat_at": "2026-03-30 10:30:00",
    "expired_at": "2026-03-30 11:30:00",
    "is_expired": false,
    "diverifikasi_at": null,
    "regenerasi_count": 0
  },
  "struk_summary": { ... }
}
```

### 5. Verify & Checkout (Staff Process)
```
POST /api/peminjaman/{id_peminjaman}/verify-checkout

Authorization: Bearer {token}
Role Required: petugas, admin, owner

Request Body:
{
  "kode_verifikasi": "ABC123DEF456"  // Kode dari struk customer
}

Response 200 (Success):
{
  "success": true,
  "message": "Kode terverifikasi, peminjaman dimulai",
  "data": {
    "id_peminjaman": 1,
    "status": "in_use",
    "kode_diverifikasi_at": "2026-03-30 10:35:00",
    ...
  },
  "status": "in_use",
  "verified_at": "2026-03-30 10:35:00"
}

Response 400 (Error):
{
  "success": false,
  "message": "Kode verifikasi tidak valid atau sudah expired"
}
```

---

## 📊 DATABASE SCHEMA

### Kolom Baru di Tabel `peminjaman`

```sql
ALTER TABLE peminjaman ADD (
  `kode_verifikasi` VARCHAR(50) UNIQUE NULL AFTER qr_code,
  `kode_dibuat_at` TIMESTAMP NULL AFTER kode_verifikasi,
  `kode_expired_at` TIMESTAMP NULL AFTER kode_dibuat_at,
  `kode_regenerasi_count` INT DEFAULT 0 AFTER kode_expired_at,
  `kode_diverifikasi_at` TIMESTAMP NULL AFTER kode_regenerasi_count,
  INDEX idx_kode_verifikasi (kode_verifikasi)
);
```

### Informasi Field

| Field | Type | Nullable | Default | Keterangan |
|-------|------|----------|---------|------------|
| `kode_verifikasi` | VARCHAR(50) | YES | NULL | Kode unik (misal: ABC123DEF456) |
| `kode_dibuat_at` | TIMESTAMP | YES | NULL | Waktu kode dibuat (saat approval) |
| `kode_expired_at` | TIMESTAMP | YES | NULL | Waktu kode expired (dibuat + 1 jam) |
| `kode_regenerasi_count` | INT | NO | 0 | Jumlah regenerasi yang dilakukan |
| `kode_diverifikasi_at` | TIMESTAMP | YES | NULL | Waktu kode diverifikasi oleh staff |

---

## 🎫 FORMAT STRUK

### Struktur Data Struk

```json
{
  "nomor_struk": "STR-000001",
  "kode_verifikasi": "ABC123DEF456",
  "kode_expired_at": "2026-03-30 11:30",
  "status_kode": "VALID",
  
  "pemesan": {
    "nama": "Muhammad Rian",
    "email": "rian@example.com",
    "telepon": "081234567890",
    "alamat": "Jl. Merdeka No. 123"
  },
  
  "alat": {
    "nama_alat": "Proyektor Sony VPL-FX30",
    "kategori": "Audio Visual",
    "kondisi": "tersedia"
  },
  
  "peminjaman": {
    "tgl_peminjaman": "30-03-2026",
    "tgl_kembali": "02-04-2026",
    "durasi_hari": 3,
    "status": "Sudah Dipesan",
    "tanpa_biaya": "Ya"
  }
}
```

### HTML Struk (untuk print)

Template HTML struk sudah di-generate oleh `VerificationCodeService::generateStrekHTML()` dengan:
- ✅ Format profesional A4
- ✅ Kode verification tercetak besar
- ✅ Informasi pemesan lengkap
- ✅ Detail alat dan tanggal
- ✅ Instruksi untuk customer

---

## 🔐 AUTHORIZATION & BUSINESS RULES

### Endpoint Authorization

| Endpoint | Customer | Petugas | Admin | Owner |
|----------|----------|---------|-------|-------|
| POST approve-and-generate-code | ❌ | ✅ | ✅ | ✅ |
| POST regenerate-code | ✅* | ✅ | ✅ | ✅ |
| GET verification-struk | ✅* | ✅ | ✅ | ✅ |
| GET detail-with-borrower | ❌ | ✅ | ✅ | ✅ |
| POST verify-checkout | ❌ | ✅ | ✅ | ✅ |

*Customer hanya bisa akses milik sendiri

### Business Rules

1. **Generation**
   - ✅ Kode hanya bisa di-generate saat approval (pending → booked)
   - ✅ Setiap kode unik (tidak pernah ada duplikat)
   - ✅ Format: 12 karakter (6 huruf + 6 angka) = ABC123DEF456

2. **Expiry**
   - ✅ Kode berlaku 1 jam
   - ✅ Expired jika sudah melewati `kode_expired_at`
   - ✅ Tidak bisa di-verify jika expired

3. **Regeneration**
   - ✅ Bisa regenerate berkali-kali
   - ✅ Reset timer ke +1 jam dari saat regenerate
   - ✅ Increment `kode_regenerasi_count`
   - ✅ Hanya customer/admin/owner yang bisa

4. **Verification**
   - ✅ Staff input kode dari struk customer
   - ✅ Kode harus exactly match (case-sensitive)
   - ✅ Harus dalam periode berlaku (tidak expired)
   - ✅ Saat verify berhasil → status in_use + set `kode_diverifikasi_at`

5. **Audit Trail**
   - ✅ Setiap aksi di-log ke `activity_logs`
   - ✅ Actions: APPROVE_WITH_CODE, REGENERATE_CODE, VERIFY_CHECKOUT
   - ✅ Owner bisa melihat semua log

---

## 📱 FRONTEND INTEGRATION GUIDE

### Customer Side

#### 1. Dashboard Peminjaman (Setelah Approval)
```html
<!-- Tampilkan card dengan kode verifikasi -->
<div class="card">
  <h3>Struk Peminjaman #STR-000001</h3>
  
  <!-- Kode dengan styling prominent -->
  <div class="kode-box">
    <strong>KODE VERIFIKASI</strong>
    <h2>ABC123DEF456</h2>
    <small>Berlaku hingga: 30 Maret 10:30</small>
  </div>
  
  <!-- Tombol aksi -->
  <button @click="downloadStruk()">📥 Download Struk</button>
  <button @click="printStruk()">🖨️ Print Struk</button>
  <button @click="regenerateCode()">🔄 Regenerate Kode</button>
</div>
```

#### 2. API Call untuk Struk
```javascript
// Get struk
async function getStruk(id_peminjaman) {
  const response = await fetch(
    `/api/peminjaman/${id_peminjaman}/verification-struk`,
    {
      headers: { 'Authorization': `Bearer ${token}` }
    }
  );
  return response.json();
}

// Regenerate kode
async function regenerateCode(id_peminjaman) {
  const response = await fetch(
    `/api/peminjaman/${id_peminjaman}/regenerate-code`,
    {
      method: 'POST',
      headers: { 'Authorization': `Bearer ${token}` }
    }
  );
  return response.json();
}
```

### Staff Dashboard Side

#### 1. Tabel Peminjaman dengan Tombol Detail
```html
<!-- Di tabel peminjaman, tambahkan kolom aksi -->
<table>
  <thead>
    <tr>
      <th>No. Peminjaman</th>
      <th>Pemesan</th>
      <th>Alat</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>STR-000001</td>
      <td>Muhammad Rian</td>
      <td>Proyektor Sony</td>
      <td><span class="badge-booked">Sudah Dipesan</span></td>
      <td>
        <button @click="showDetail(1)">👁️ Detail</button>
      </td>
    </tr>
  </tbody>
</table>
```

#### 2. Modal Detail Peminjaman + Verify Kode
```html
<!-- Modal Detail -->
<modal v-if="showDetailModal">
  <div class="modal-content">
    <!-- Informasi Pemesan -->
    <section>
      <h3>Informasi Pemesan</h3>
      <p><strong>Nama:</strong> {{ donor.nama }}</p>
      <p><strong>Email:</strong> {{ donor.email }}</p>
      <p><strong>Telepon:</strong> {{ donor.telepon }}</p>
      <p><strong>Alamat:</strong> {{ donor.alamat }}</p>
    </section>

    <!-- Kode Verifikasi -->
    <section class="kode-display">
      <h3>Kode Verifikasi</h3>
      <div class="kode-box">
        <h2>{{ detail.kode_status.kode }}</h2>
        <small v-if="!detail.kode_status.is_expired" class="text-success">
          ✓ BERLAKU
        </small>
        <small v-else class="text-danger">
          ✗ EXPIRED
        </small>
      </div>
    </section>

    <!-- Input Verify -->
    <section>
      <h3>Proses Verifikasi</h3>
      <p>Masukkan kode dari struk yang ditunjukkan customer</p>
      <input 
        v-model="inputKode" 
        placeholder="Masukkan kode verifikasi"
        maxlength="12"
      />
      <button @click="verifyCheckout()">✓ Verify & Checkout</button>
    </section>
  </div>
</modal>
```

#### 3. API Call untuk Staff
```javascript
// Get detail dengan info pemesan
async function getDetailWithBorrower(id_peminjaman) {
  const response = await fetch(
    `/api/peminjaman/${id_peminjaman}/detail-with-borrower`,
    {
      headers: { 'Authorization': `Bearer ${token}` }
    }
  );
  return response.json();
}

// Verify & Checkout
async function verifyCheckout(id_peminjaman, kode_verifikasi) {
  const response = await fetch(
    `/api/peminjaman/${id_peminjaman}/verify-checkout`,
    {
      method: 'POST',
      headers: { 
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ kode_verifikasi })
    }
  );
  return response.json();
}
```

---

## 🧪 TESTING

### Test Case 1: Generate Kode
```
Given: Peminjaman dengan status pending
When: Staff call POST /api/peminjaman/1/approve-and-generate-code
Then:
  ✓ Status berubah dari pending → booked
  ✓ Kode verifikasi di-generate (12 karakter)
  ✓ kode_dibuat_at = sekarang
  ✓ kode_expired_at = sekarang + 1 jam
  ✓ Activity log di-buat
```

### Test Case 2: Regenerate Kode
```
Given: Peminjaman booked dengan kode ABC123DEF456 (dibuat 10.00)
When: Customer call POST /api/peminjaman/1/regenerate-code pada 10:25
Then:
  ✓ Kode baru di-generate (misal: XYZ789ABC123)
  ✓ kode_dibuat_at = 10:25
  ✓ kode_expired_at = 11:25 (10:25 + 1 jam)
  ✓ kode_regenerasi_count = 1
  ✓ Activity log di-buat
```

### Test Case 3: Verify Expired Kode
```
Given: Peminjaman dengan kode expired (lebih dari 1 jam)
When: Staff call POST /api/peminjaman/1/verify-checkout dengan kode lama
Then:
  ✓ Respons error: "Kode verifikasi tidak valid atau sudah expired"
  ✓ Status masih tetap booked (tidak berubah)
```

### Test Case 4: Verify Valid Kode
```
Given: Peminjaman booked dengan kode ABC123DEF456 (valid, belum expired)
When: Staff call POST /api/peminjaman/1/verify-checkout {"kode_verifikasi": "ABC123DEF456"}
Then:
  ✓ Kode dicocokkan dan match
  ✓ Status berubah: booked → in_use
  ✓ kode_diverifikasi_at = sekarang
  ✓ Activity log di-buat (VERIFY_CHECKOUT)
  ✓ Alat ready untuk dipinjam
```

---

## 📝 ACTIVITY LOG ENTRIES

Setiap aksi tercatat di `activity_logs` dengan format:

```
Action: APPROVE_WITH_CODE
Model: Peminjaman
Description: Peminjaman disetujui dan kode verifikasi ABC123DEF456 dibuat
Timestamp: 2026-03-30 10:30:00
User: Staff ID

Action: REGENERATE_CODE
Model: Peminjaman
Description: Kode verifikasi diregenerate menjadi XYZ789ABC123
Timestamp: 2026-03-30 10:35:00
User: Customer ID

Action: VERIFY_CHECKOUT
Model: Peminjaman
Description: Kode verifikasi diverifikasi dan peminjaman dimulai (status: in_use)
Timestamp: 2026-03-30 10:40:00
User: Staff ID
```

---

## ⚠️ ERROR HANDLING

### Possible Error Responses

```json
// Error 1: Peminjaman bukan pending
{
  "success": false,
  "message": "Hanya peminjaman pending yang bisa diapprove"
}

// Error 2: Tidak punya akses
{
  "success": false,
  "message": "Anda tidak berhak meregenerasi kode ini"
}

// Error 3: Kode expired
{
  "success": false,
  "message": "Kode verifikasi tidak valid atau sudah expired"
}

// Error 4: Kode tidak match
{
  "success": false,
  "message": "Kode verifikasi tidak valid atau sudah expired"
}

// Error 5: Not found
{
  "success": false,
  "message": "Peminjaman tidak ditemukan"
}
```

---

## 🚀 IMPLEMENTATION CHECKLIST

### Backend ✅
- [x] Migration: Add verification code fields
- [x] Model: Update Peminjaman model + helpers
- [x] Service: VerificationCodeService dengan semua method
- [x] Controller: 5 endpoint baru di PeminjamanController
- [x] Routes: Register 5 route baru
- [x] Activity Logging: Integrate untuk 3 actions
- [x] Database: Run migration

### Frontend (TODO)
- [ ] Dashboard customer: Tampil kode verifikasi + download/print
- [ ] Button regenerate kode dengan confirmation
- [ ] Dashboard staff: Tambah tombol Detail di tabel
- [ ] Modal detail dengan info pemesan
- [ ] Form verify kode dengan input validation
- [ ] UI untuk expired kode handling

### Testing (TODO)
- [ ] Unit tests untuk VerificationCodeService
- [ ] Integration tests untuk endpoints
- [ ] E2E tests untuk full flow
- [ ] Performance test untuk code generation

---

## 📞 SUPPORT & MAINTENANCE

### Known Limitations
- Kode tidak pernah dihapus dari database (untuk audit trail)
- Tidak ada limit jumlah regenerasi (bisa di-add nanti)

### Future Enhancements
- [ ] Add dompdf untuk proper PDF download
- [ ] SMS/Email notification saat approval
- [ ] QR code display sebagai alternatif
- [ ] Bulk approve dengan multiple kode generation
- [ ] Kode expiry configuration per admin

---

**Generated:** 30 Maret 2026  
**Last Updated:** Maret 30, 2026  
**Implementation Time:** ~2 jam 30 menit
