# 🇮🇩 Panduan Bahasa Indonesia Sistem

## Konfigurasi Locale

Sistem sudah dikonfigurasi untuk menggunakan Bahasa Indonesia secara default:

```php
// config/app.php
'locale' => 'id',           // Locale utama
'fallback_locale' => 'id',  // Locale cadangan
'timezone' => 'Asia/Jakarta' // Timezone Indonesia
```

## Menggunakan Validation Messages Indonesia

Validasi pesan otomatis dalam Bahasa Indonesia karena locale sudah di-set ke 'id':

```php
$validated = $request->validate([
    'email' => 'required|email',
    'password' => 'required|min:6',
    'nama_alat' => 'required|string|max:100'
]);

// Error messages akan otomatis dalam Bahasa Indonesia:
// "email wajib diisi."
// "password minimal 6 karakter."
// "nama_alat wajib diisi."
```

## Menggunakan Pesan Konfigurasi

Gunakan centralized message config:

```php
use App\Config\messages;

// Di controller
return response()->json([
    'success' => true,
    'message' => config('messages.borrowing.created')
]);

// Output: "Permohonan peminjaman berhasil dibuat"
```

## Mapping Kolom Database ke Indonesia

Untuk API responses yang menggunakan nama Indonesia:

```php
use App\Helpers\ColumnMapper;

$data = [
    'id_user' => 1,
    'nama_lengkap' => 'Budi Santoso',
    'email' => 'budi@example.com'
];

// Transform ke nama Indonesia
$transformed = ColumnMapper::transform($data);
// Output:
// [
//     'id_pengguna' => 1,
//     'nama_lengkap' => 'Budi Santoso',
//     'email' => 'budi@example.com'
// ]
```

## Custom Attributes dalam Validasi

Custom attributes sudah dikonfigurasi di `resources/lang/id/validation.php`:

```php
'attributes' => [
    'email' => 'email',
    'password' => 'password',
    'nama_lengkap' => 'nama lengkap',
    'nama_alat' => 'nama alat',
    // etc...
]
```

Jika perlu menambah:
```php
$request->validate([
    'custom_field' => 'required'
], [], [
    'custom_field' => 'nama field saya'
]);
```

## Translation Helper

```php
// Untuk translation dinamis
trans('validation.required', ['attribute' => 'Email'])
// Output: "Email wajib diisi."

trans_choice('messages.count', 5)
```

## Event Listener Error Handling

Semua listeners sekarang memiliki error handling:

```php
public function handle(BorrowingCreated $event)
{
    try {
        $this->notificationService->notifyBorrowingCreated($event->borrowing);
    } catch (\Exception $e) {
        \Log::error('Error sending borrowing created notification: ' . $e->getMessage(), [
            'borrowing_id' => $event->borrowing->id_borrowing ?? null,
            'error' => $e
        ]);
        // Continue silently - don't crash the event
    }
}
```

## API Response Format

Standard response format (semua dalam Bahasa Indonesia):

```json
{
    "success": true,
    "data": { ... },
    "message": "Operasi berhasil"
}
```

Error response:

```json
{
    "success": false,
    "message": "Gagal melakukan operasi",
    "errors": {
        "field": ["Error message"]
    }
}
```

## Database Tables

### Activity Logs
```sql
SELECT * FROM activity_logs WHERE id_user = 1;
-- Mencatat: create, update, delete actions
```

### Borrowing Returns
```sql
SELECT * FROM borrowing_returns WHERE id_borrowing = 5;
-- Detail pengembalian: condition, damage, fine
```

### Notification Logs
```sql
SELECT * FROM notification_logs WHERE id_user = 1;
-- Track: sent, read, archived, deleted actions
```

## Testing dengan Tinker

```bash
php artisan tinker

# Check locale
>>> config('app.locale')
=> "id"

# Check timezone
>>> config('app.timezone')
=> "Asia/Jakarta"

# Test validation message
>>> trans('validation.required', ['attribute' => 'Email'])
=> "Email wajib diisi."
```

## Timezone Handling

Sistem menggunakan Asia/Jakarta. Untuk timestamps:

```php
// Semua timestamps otomatis dalam Asia/Jakarta
now()  // Waktu sekarang dalam Asia/Jakarta
today() // Hari ini dalam Asia/Jakarta

// Carbon dengan explicit timezone
\Carbon\Carbon::now('Asia/Jakarta')
```

## Menambah Pesan Baru

1. **Validation Messages:** Edit `resources/lang/id/validation.php`
2. **System Messages:** Edit `config/messages.php`
3. **Listener Error Messages:** Sudah ada logging otomatis

Contoh menambah validation:

```php
// resources/lang/id/validation.php
'custom_validation_rule' => ':attribute harus memenuhi kondisi tertentu.',
```

---

**Semua pesan sistem sudah dalam Bahasa Indonesia!** 🇮🇩
