# API Documentation - New Features

Dokumentasi lengkap untuk fitur-fitur baru yang ditambahkan pada sistem peminjaman alat.

## 📋 Daftar Fitur Baru

1. **Upload Gambar & Simpan ke Database** ✅
2. **Petugas Bisa Mengganti Status Peminjaman** ✅
3. **Scan QR untuk Persetujuan Peminjaman** ✅
4. **Pengelolaan Data Penuh untuk Admin** ✅
5. **Role Owner dengan Hak Khusus** ✅
6. **Fitur Trash/Soft Delete** ✅
7. **Activity Logging & Tracking** ✅

---

## 1. Upload Gambar untuk Alat

### Endpoint: Upload Gambar

```
POST /api/alat/{id}/upload-image
Authorization: Bearer {token}
Content-Type: multipart/form-data

Parameter:
- gambar (file, required): Image file (jpeg, png, jpg, gif, max 5MB)

Response:
{
    "success": true,
    "message": "Gambar berhasil diupload",
    "data": {
        "id_alat": 1,
        "gambar": "alat/ABC123.jpg",
        "url": "http://localhost/storage/alat/ABC123.jpg"
    }
}
```

### Endpoint: Get Gambar Alat

```
GET /api/alat/{id}/image
```

Mengembalikan file gambar alat. Jika tidak ada, return 404.

### Endpoint: Delete Gambar Alat

```
DELETE /api/alat/{id}/delete-image
Authorization: Bearer {token}

Response:
{
    "success": true,
    "message": "Gambar berhasil dihapus"
}
```

**Otorisasi**: Hanya Admin dan Owner

---

## 2. Status Update oleh Petugas

### Endpoint: Update Status Peminjaman

```
PUT /api/peminjaman/{id_peminjaman}/status
Authorization: Bearer {token}
Content-Type: application/json

Body:
{
    "status": "in_use|booked|returned|rejected|maintenance|pending",
    "keterangan": "Optional notes" (optional)
}

Response:
{
    "success": true,
    "message": "Status peminjaman berhasil diubah dari pending ke booked",
    "data": {
        "id_peminjaman": 1,
        "status": "booked",
        "status_updated_by": 2,
        "status_updated_at": "2026-03-27 10:30:00",
        "user": {...},
        "alat": {...}
    }
}
```

**Status Transitions Valid:**
```
pending   → booked, rejected
booked    → in_use, rejected, pending
in_use    → returned, maintenance
returned  → returned (final state)
rejected  → pending (can revert)
maintenance → tersedia, in_use
```

**Otorisasi**: Petugas, Admin, Owner

### Endpoint: Bulk Update Status

```
POST /api/peminjaman/bulk-status-update
Authorization: Bearer {token}
Content-Type: application/json

Body:
{
    "peminjamans": [
        {
            "id": 1,
            "status": "in_use"
        },
        {
            "id": 2,
            "status": "booked"
        }
    ]
}

Response:
{
    "success": true,
    "message": "Berhasil: 2, Gagal: 0",
    "data": {
        "successful": 2,
        "failed": 0,
        "errors": []
    }
}
```

### Endpoint: Get Valid Transitions

```
GET /api/peminjaman/{id_peminjaman}/valid-transitions
Authorization: Bearer {token}

Response:
{
    "success": true,
    "current_status": "pending",
    "valid_transitions": ["booked", "rejected"]
}
```

---

## 3. QR Code & Scan untuk Persetujuan

### QR Code Format

```
PEMINJAMAN-XXXXXXXXXXXXX-{id_peminjaman}
```

Contoh: `PEMINJAMAN-ABC123XYZ789-5`

### Endpoint: Get QR Code untuk Peminjaman

```
GET /api/peminjaman/{id_peminjaman}/qr-code
Authorization: Bearer {token}

Response:
{
    "success": true,
    "data": {
        "id_peminjaman": 1,
        "qr_code": "PEMINJAMAN-ABC123XYZ789-1",
        "qr_image": "data:image/png;base64,...",
        "peminjaman": {
            "id_peminjaman": 1,
            "status": "pending",
            "tgl_peminjaman": "2026-03-28",
            "tgl_kembali": "2026-04-05",
            "user": {...},
            "alat": {...}
        }
    }
}
```

**Otorisasi**: Peminjam (diri sendiri), Petugas, Admin, Owner

### Endpoint: Scan QR Code

```
POST /api/qr-code/scan
Authorization: Bearer {token}
Content-Type: application/json

Body:
{
    "qr_code": "PEMINJAMAN-ABC123XYZ789-1"
}

Response:
{
    "success": true,
    "message": "QR code berhasil dipindai",
    "data": {
        "id_peminjaman": 1,
        "status": "pending",
        "user": {...},
        "alat": {...}
    }
}
```

### Endpoint: Approve Peminjaman via QR Scan (Petugas)

```
POST /api/qr-code/approve
Authorization: Bearer {token}
Content-Type: application/json

Body:
{
    "qr_code": "PEMINJAMAN-ABC123XYZ789-1"
}

Response:
{
    "success": true,
    "message": "Peminjaman berhasil disetujui oleh username_petugas",
    "data": {
        "id_peminjaman": 1,
        "status": "booked",
        "approved_by": 2,
        "status_updated_by": 2,
        ...
    }
}
```

**Otorisasi**: Hanya Petugas, Admin, Owner
**Status**: Hanya bisa di-approve jika status adalah 'pending'
**Note**: Petugas dapat meng-approve peminjaman dari siapa saja dengan scan QR

---

## 4. Role Owner & Pengelolaan Data

### Role Hierarchy

```
Owner (Level 1) - Tertinggi
├─ Admin (Level 2)
├─ Petugas (Level 3)
└─ Peminjam (Level 4) - Terendah
```

### Hak Akses Owner

- ✅ Melihat semua data peminjaman dan alat
- ✅ Melihat activity logs semua pengguna
- ✅ Mengubah status peminjaman
- ✅ Melihat dan memulihkan data dari trash
- ✅ Menghapus permanen data dari trash
- ✅ Mengosongkan trash
- ✅ Menghapus activity logs yang sudah lama
- ✅ Full CRUD untuk semua resource

### Hak Akses Admin

- ✅ CRUD untuk alat (Create, Read, Update, Delete)
- ✅ Melihat semua peminjaman
- ✅ Mengubah status peminjaman
- ✅ Upload gambar untuk alat
- ✅ Melihat activity logs mereka sendiri

### Hak Akses Petugas

- ✅ Melihat semua peminjaman
- ✅ Mengubah status peminjaman
- ✅ Melihat activity logs mereka sendiri
- ✅ Scan QR code

### Hak Akses Peminjam

- ✅ Membuat permintaan peminjaman
- ✅ Melihat peminjaman mereka sendiri
- ✅ Melihat riwayat peminjaman
- ✅ Approve peminjaman via QR scan
- ✅ Melihat tersedia alat

---

## 5. Trash & Soft Delete

### Endpoint: View Trash Items

```
GET /api/trash?type=all|peminjaman|alat
Authorization: Bearer {token}

Response:
{
    "success": true,
    "data": {
        "alat": [
            {
                "id_alat": 1,
                "nama_alat": "Helm Safety",
                "deleted_at": "2026-03-27 10:00:00",
                ...
            }
        ],
        "peminjaman": [
            {
                "id_peminjaman": 5,
                "status": "returned",
                "deleted_at": "2026-03-27 09:30:00",
                ...
            }
        ]
    }
}
```

**Otorisasi**: 
- Owner → Melihat semua trash
- Admin → Melihat trash mereka
- Peminjam → Melihat trash peminjaman mereka saja

**Query Parameters**:
- `type`: all (default), peminjaman, alat

### Endpoint: Restore Item dari Trash

```
POST /api/trash/restore
Authorization: Bearer {token}
Content-Type: application/json

Body:
{
    "type": "alat|peminjaman",
    "id": 1
}

Response:
{
    "success": true,
    "message": "Alat berhasil dipulihkan",
    "data": {
        "id_alat": 1,
        "nama_alat": "Helm Safety",
        "deleted_at": null,
        ...
    }
}
```

**Otorisasi**: Hanya Owner

### Endpoint: Permanent Delete

```
POST /api/trash/permanent-delete
Authorization: Bearer {token}
Content-Type: application/json

Body:
{
    "type": "alat|peminjaman",
    "id": 1
}

Response:
{
    "success": true,
    "message": "Alat berhasil dihapus permanen"
}
```

**Otorisasi**: Hanya Owner

### Endpoint: Empty Trash

```
POST /api/trash/empty
Authorization: Bearer {token}
Content-Type: application/json

Body:
{
    "type": "all|alat|peminjaman"
}

Response:
{
    "success": true,
    "message": "Sampah berhasil dikosongkan (45 item)"
}
```

**Otorisasi**: Hanya Owner

---

## 6. Activity Logging & Tracking

### Activity Log Data Structure

```json
{
    "id": 1,
    "id_user": 2,
    "action": "create|update|delete|upload_image|restore|scan_qr|approve_via_scan|update_status",
    "model_type": "Alat|Peminjaman|System",
    "model_id": 5,
    "changes": {
        "field_name": "old_value → new_value"
    },
    "ip_address": "192.168.1.1",
    "created_at": "2026-03-27 10:30:00",
    "user": {
        "id_user": 2,
        "username": "budi",
        "role": "admin"
    }
}
```

### Endpoint: Get Activity Logs

```
GET /api/activity-logs?limit=100&page=1
Authorization: Bearer {token}

Response:
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [...],
        "total": 250,
        ...paginate data...
    }
}
```

**Otorisasi**:
- Owner → Melihat semua activity logs
- Lainnya → Melihat activity logs mereka sendiri

**Query Parameters**:
- `limit`: 1-500 (default: 100)
- `page`: Page number (default: 1)

### Endpoint: Get Activity Logs untuk Model Tertentu

```
GET /api/activity-logs/model/{modelType}/{modelId}?limit=100
Authorization: Bearer {token}

modelType: Alat|Peminjaman

Response:
{
    "success": true,
    "data": {
        ...paginated activity logs...
    }
}
```

**Otorisasi**: Admin, Owner

### Endpoint: Get Activity Logs untuk User Tertentu

```
GET /api/activity-logs/user/{id_user}?limit=100
Authorization: Bearer {token}

Response:
{
    "success": true,
    "data": {
        ...paginated activity logs...
    }
}
```

**Otorisasi**:
- Owner → Melihat semua user
- Lainnya → Melihat diri sendiri saja

### Endpoint: Get Activity Summary

```
GET /api/activity-logs/summary?days=7
Authorization: Bearer {token}

Response:
{
    "success": true,
    "data": {
        "period": "7 hari terakhir",
        "total_activities": 156,
        "by_action": [
            {
                "action": "create",
                "count": 45
            },
            {
                "action": "update_status",
                "count": 67
            }
        ],
        "by_user": [
            {
                "id_user": 2,
                "count": 56,
                "user": {...}
            }
        ]
    }
}
```

**Otorisasi**: Admin, Owner

### Endpoint: Clear Old Activity Logs

```
DELETE /api/activity-logs/clear-old
Authorization: Bearer {token}
Content-Type: application/json

Body:
{
    "days": 30
}

Response:
{
    "success": true,
    "message": "Activity logs sebelum 30 hari telah dihapus",
    "deleted_count": 1240
}
```

**Otorisasi**: Hanya Owner

---

## 📊 Tabel Perubahan Database

### Tabel: `users`
**Kolom Baru:**
- `role` ENUM → Ditambah 'owner' (admin, petugas, peminjam, owner)

### Tabel: `alat`
**Kolom Baru:**
- `gambar` VARCHAR(255) NULL - Path ke file gambar
- `deleted_at` TIMESTAMP NULL - Soft delete timestamp

### Tabel: `peminjaman`
**Kolom Baru:**
- `qr_code` VARCHAR(255) NULL UNIQUE - QR code string
- `approved_by` INT UNSIGNED NULL - User ID yang approve
- `status_updated_by` INT UNSIGNED NULL - User ID yang update status
- `status_updated_at` TIMESTAMP NULL - Kapan status diupdate
- `deleted_at` TIMESTAMP NULL - Soft delete timestamp

### Tabel: `activity_logs` (Baru)
```
- id INT AUTO_INCREMENT PRIMARY KEY
- id_user INT UNSIGNED NOT NULL
- action VARCHAR(50) NOT NULL
- model_type VARCHAR(50) NOT NULL
- model_id INT UNSIGNED NOT NULL
- changes JSON NULL
- ip_address VARCHAR(50) NULL
- created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
- Foreign Key: id_user → users.id_user
- Index: (created_at)
- Index: (model_type, model_id)
```

---

## 🔐 Authorization Rules

| Action | Owner | Admin | Petugas | Peminjam |
|--------|-------|-------|---------|----------|
| View All Data | ✅ | ❌ | ❌ | ❌ |
| Upload Image | ✅ | ✅ | ❌ | ❌ |
| CRUD Alat | ✅ | ✅ | ❌ | ❌ |
| Update Status | ✅ | ✅ | ✅ | ❌ |
| View Trash | ✅ | ⚠️ | ❌ | ⚠️ |
| Restore/Delete Trash | ✅ | ❌ | ❌ | ❌ |
| View All Activity Logs | ✅ | ❌ | ❌ | ❌ |
| Scan QR | ✅ | ✅ | ✅ | ✅ |
| Approve via QR | ✅ | ✅ | ❌ | ✅ (own) |

✅ = Full access | ❌ = No access | ⚠️ = Limited (own data)

---

## 📝 Contoh Implementasi

### Contoh 1: Upload Gambar untuk Alat

```bash
curl -X POST http://localhost/api/alat/1/upload-image \
  -H "Authorization: Bearer {token}" \
  -F "gambar=@/path/to/image.jpg"
```

### Contoh 2: Update Status Peminjaman

```bash
curl -X PUT http://localhost/api/peminjaman/5/status \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "in_use",
    "keterangan": "Peminjam sudah mengambil barang"
  }'
```

### Contoh 3: Scan QR Code

```bash
curl -X POST http://localhost/api/qr-code/scan \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "qr_code": "PEMINJAMAN-ABC123XYZ789-5"
  }'
```

### Contoh 4: Restore dari Trash

```bash
curl -X POST http://localhost/api/trash/restore \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "type": "alat",
    "id": 3
  }'
```

---

## 🚀 Testing

### Test Data untuk Owner Creation (Manual SQL)

```sql
INSERT INTO users (username, email, phone, password, role, alamat, email_verified, is_active, created_at, updated_at)
VALUES ('owner_demo', 'owner@example.com', '081234567890', bcrypt('password123'), 'owner', 'Jl. Main', true, true, NOW(), NOW());
```

### Test Upload Image

1. Upload gambar untuk alat ID 1
2. Verify gambar tersimpan di `storage/app/public/alat/`
3. Get image via `/api/alat/1/image`

### Test Soft Delete

1. Delete alat ID 1
2. Verify tidak muncul di `/api/alat`
3. Verify muncul di `/api/trash?type=alat`
4. Restore via `/api/trash/restore`
5. Verify kembali ke `/api/alat`

---

## ⚠️ Catatan Penting

1. **Image Storage**: Pastikan folder `storage/app/public` writable
2. **Symlink**: Jalankan `php artisan storage:link` untuk public akses
3. **QR Code Generation**: Pastikan library `simple-qrcode` sudah terinstall
4. **Soft Delete**: Data yang di-delete masih ada di database, hanya `deleted_at` yang di-set
5. **Permissions**: Middleware `role` akan return 403 jika akses tidak sesuai
6. **Activity Logs**: Setiap CRUD action otomatis tercatat di activity_logs table

---

## 📚 Database Queries Berguna

### Lihat All Alat yang Sudah di-Delete

```sql
SELECT * FROM alat WHERE deleted_at IS NOT NULL;
```

### Lihat Recent Activity Logs

```sql
SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 50;
```

### Lihat Status Changes untuk Peminjaman Tertentu

```sql
SELECT * FROM activity_logs 
WHERE model_type = 'Peminjaman' AND model_id = 5 
ORDER BY created_at DESC;
```

### Delete Semua Old Logs Manually

```sql
DELETE FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);
```

---

## 🆘 Troubleshooting

### QR Code tidak generate

- Pastikan `composer require simple-qrcode` sudah dijalankan
- Check if GD library enabled di PHP

### Gambar tidak upload

- Check folder permissions: `chmod 777 storage/app/public`
- Jalankan: `php artisan storage:link`
- Check file size (max 5MB)

### Role middleware error

- Pastikan user memiliki role yang valid
- Check Kernel.php untuk middleware registration

### Soft delete tidak berfungsi  

- Pastikan migration sudah dijalankan: `php artisan migrate`
- Check Model menggunakan `use SoftDeletes`

---

Generated: 27 March 2026
