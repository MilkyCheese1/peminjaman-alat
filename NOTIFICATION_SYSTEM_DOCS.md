# NOTIFICATION SYSTEM - DOKUMENTASI LENGKAP

## Ringkasan Sistem Notifikasi Baru

Sistem notifikasi telah **dibangun dari awal** dengan fitur-fitur berikut:

### ✅ Fitur Utama
- **4 Kategori**: Approval, Return, Reminder, System
- **14 Tipe Notifikasi**: Covering semua event di lifecycle peminjaman
- **4 Channel**: In-App, Email, SMS, Push Notification
- **Persistent Database**: Semua notifikasi disimpan dengan audit trail
- **User Preferences**: Kontrol penuh atas channel dan kategori
- **Scheduling**: Support untuk reminder otomatis
- **Grouping & Filtering**: UI-friendly grouping by date dan type
- **Real-time & Manual**: Bisa triggered otomatis atau manual

---

## STRUKTUR DATABASE

### Tabel `notifications`
Master table untuk semua notifications

**Kolom Penting:**
- `id_notification` - Primary key
- `id_user` - User yang menerima notification
- `id_borrowing` - Link ke borrowing record
- `category` - Kategori: approval|return|reminder|system
- `type` - Tipe spesifik (14 variants)
- `title`, `message` - Konten notifikasi
- `is_read`, `read_at` - Status baca
- `is_archived`, `archived_at` - Status arsip
- `is_deleted`, `deleted_at` - Soft delete
- `channels` - JSON array: ['in_app', 'email', 'sms', 'push']
- `channel_status` - JSON: {email: 'sent', sms: 'failed', ...}
- `email_status`, `sms_status`, `push_status` - Individual status
- `metadata` - JSON: equipment_name, quantity, etc.
- `priority` - low|normal|high|urgent
- `created_at`, `updated_at` - Timestamps

### Tabel `notification_logs`
Audit trail untuk setiap action pada notifications

### Tabel `notification_preferences`
User preferences untuk channels dan categories

---

## API ENDPOINTS

### Retrieval
```
GET  /api/notifications                   - Get user notifications (paginated)
GET  /api/notifications/grouped           - Get grouped by date & type
GET  /api/notifications/{id}              - Get detail notification
GET  /api/notifications/unread/count      - Get unread count
```

### Update/Modify
```
POST   /api/notifications/{id}/mark-read       - Mark single as read
POST   /api/notifications/mark-all-read        - Mark all as read
POST   /api/notifications/mark-category-read   - Mark category as read
POST   /api/notifications/{id}/archive         - Archive notification
POST   /api/notifications/archive-category     - Archive category
DELETE /api/notifications/{id}                 - Delete notification
POST   /api/notifications/{id}/restore         - Restore deleted
DELETE /api/notifications/clear-all            - Delete all
```

### Preferences
```
GET  /api/notifications/preferences       - Get user preferences
PUT  /api/notifications/preferences       - Update preferences
```

---

## NOTIFICATION TYPES (14 Total)

### Approval Category (3)
1. **borrowing_created** - Peminjaman baru dibuat
2. **borrowing_approved** - Admin setujui peminjaman
3. **borrowing_rejected** - Admin tolak peminjaman

### Return Category (5)
4. **return_ready** - Item siap untuk diambil
5. **return_submitted** - Pengembalian diajukan
6. **return_verified** - Pengembalian dikonfirmasi
7. **return_overdue** - Pengembalian terlambat
8. **return_not_verified** - Pengembalian tidak terverifikasi

### Reminder Category (3)
9. **return_reminder_1day** - Pengingat 1 hari sebelum deadline
10. **return_reminder_due** - Pengingat hari deadline
11. **return_reminder_overdue** - Pengingat sudah terlambat

### System Category (3)
12. **system_announcement** - Pengumuman sistem
13. **equipment_unavailable** - Alat tidak tersedia
14. **custom** - Custom notification

---

## CARA MENGGUNAKAN

### 1. Menggunakan NotificationHelper (Recommended)

```php
use App\Helpers\NotificationHelper;

// Kirim approval notification
NotificationHelper::sendApproval($borrowing);

// Kirim rejection dengan alasan
NotificationHelper::sendRejection($borrowing, 'Stok tidak cukup');

// Kirim custom notification
NotificationHelper::sendCustom(
    userId: 5,
    title: '📬 Notifikasi Custom',
    message: 'Pesan custom',
    channels: ['in_app', 'email']
);

// Broadcast ke semua user
NotificationHelper::broadcastAll('Maintenance', 'Sistem maintenance...');

// Broadcast ke operators/admins
NotificationHelper::broadcastToOperators('Alert', 'Ada issue...');
```

### 2. Menggunakan Event Triggers (Automatic)

Di Controller BorrowingController:

```php
use App\Events\BorrowingApproved;

public function approveBorrowing(Borrowing $borrowing)
{
    // ... approval logic ...
    $borrowing->status = 'approved';
    $borrowing->save();
    
    // Event automatically trigger notification via listener
    event(new BorrowingApproved($borrowing));
    
    return response()->json(['success' => true]);
}
```

### 3. Menggunakan Trait Di Model

```php
// Di Borrowing model
use App\Traits\TriggerNotifications;

class Borrowing extends Model {
    use TriggerNotifications;
    
    // ... model code ...
}

// Di controller
public function approveBorrowing(Borrowing $borrowing)
{
    $borrowing->status = 'approved';
    $borrowing->save();
    
    // Trigger notification via trait
    $borrowing->triggerApprovedEvent();
    
    return response()->json(['success' => true]);
}
```

### 4. Direct Service Usage

```php
use App\Services\NotificationService;

protected NotificationService $notificationService;

public function __construct(NotificationService $notificationService)
{
    $this->notificationService = $notificationService;
}

public function approveRequest(Borrowing $borrowing)
{
    // Direct service call
    $this->notificationService->notifyBorrowingApproved($borrowing);
    
    return response()->json(['success' => true]);
}
```

---

## CHANNELS CONFIGURATION

### In-App (Default)
- Langsung display di UI
- Tidak perlu konfigurasi
- Best untuk urgent notifications

### Email
**TODO: Integrate dengan Mail service**
```php
// Di NotificationService::sendEmail()
Mail::to($user->email)->queue(new NotificationMail($notification));
```

### SMS
**TODO: Integrate dengan SMS provider (Twilio, AWS SNS, dll)**
```php
// Di NotificationService::sendSMS()
SMSService::send($user->phone_number, $notification->message);
```

### Push
**TODO: Integrate dengan Push service (Firebase, OneSignal, dll)**
```php
// Di NotificationService::sendPush()
PushService::send($user->id, $notification->title, $notification->message);
```

---

## USER PREFERENCES

### Enabled Channels
- `in_app_enabled` - Boolean
- `email_enabled` - Boolean
- `sms_enabled` - Boolean
- `push_enabled` - Boolean

### Category Preferences
- `approval_notifications` - Terima approval notifications
- `return_notifications` - Terima return notifications
- `reminder_notifications` - Terima reminder notifications
- `system_announcements` - Terima pengumuman sistem

### Advanced Settings
- `email_digest` - Dapatkan digest harian bukan individual
- `sms_urgent_only` - SMS hanya untuk priority urgent
- `quiet_hours_enabled` - Jangan kirim di jam tertentu
- `quiet_hours_start` - Jam mulai quiet hours (e.g., 22:00)
- `quiet_hours_end` - Jam selesai quiet hours (e.g., 08:00)

---

## FILTERING & SEARCHING

### By Endpoint Query Parameters
```
GET /api/notifications?limit=20&page=1&category=reminder&unread_only=true&days=30
```

### Available Filters
- `limit` - Items per page (1-100)
- `page` - Page number
- `category` - approval|return|reminder|system
- `type` - Specific type
- `priority` - low|normal|high|urgent
- `unread_only` - Only unread (boolean)
- `archived` - Only archived (boolean)
- `days` - Last N days (1-365)

---

## MAINTENANCE & CLEANUP

### Automatic Cleanup (via Command/Job)
```php
// Di Laravel Command atau Job
$notificationService->cleanupExpired();        // Delete expired
$notificationService->autoArchiveOld(30);     // Archive old read
$notificationService->permanentlyDelete(90);  // Hard delete
$notificationService->cleanupLogs(90);        // Clean logs
```

### Retry Failed Notifications
```php
$notificationService->retryFailed(100); // Retry first 100 failed
```

---

## NOTIFICATION FLOW

```
1. Event Triggered
   ├─ BorrowingCreated
   ├─ BorrowingApproved
   ├─ BorrowingRejected
   └─ ... (7 more events)
   
2. Event Listener invoked
   └─ SendXxxNotification listener
   
3. NotificationService called
   ├─ Create notification record
   ├─ Send via channels (in_app, email, sms, push)
   ├─ Log action in notification_logs
   └─ Update channel_status
   
4. Notification displayed in UI
   ├─ Grouped by date & type
   ├─ Mark as read
   ├─ Archive or delete
   └─ View preferences
```

---

## TESTING & DEVELOPMENT

### Development Mode (config('app.debug') = true)
Endpoints bisa diakses dengan query parameter:
```
GET /api/notifications?user_id=5&limit=20
```

### Production Mode
Harus authenticated via Sanctum:
```
GET /api/notifications (with auth token)
```

---

## TROUBLESHOOTING

### Notifications tidak terkirim?
1. Check `notification_logs` table
2. Verify email/SMS service integration
3. Check user preferences
4. Check quiet hours setting

### Database penuh?
Run cleanup commands:
```php
NotificationService::cleanupExpired();
NotificationService::permanentlyDelete(90);
NotificationService::cleanupLogs(90);
```

### Email/SMS tidak terkirim?
Integration belum dilakukan. TODO di:
- `NotificationService::sendEmail()`
- `NotificationService::sendSMS()`
- `NotificationService::sendPush()`

---

## NEXT STEPS

1. ✅ Database schema completed
2. ✅ Models & relationships created
3. ✅ Services & business logic done
4. ✅ API endpoints ready
5. ✅ Events & listeners setup
6. ⏳ Email integration
7. ⏳ SMS integration
8. ⏳ Push notification integration
9. ⏳ Frontend UI (dropdown, badges, etc)
10. ⏳ Scheduler untuk reminder otomatis

---

## FILES CREATED

Model:
- `app/Models/Notification.php`
- `app/Models/NotificationLog.php`
- `app/Models/NotificationPreference.php`

Service:
- `app/Services/NotificationService.php`

Controller:
- `app/Http/Controllers/NotificationController.php`

Events & Listeners:
- `app/Events/NotificationEvent.php`
- `app/Listeners/NotificationEventListeners.php`

Traits & Helpers:
- `app/Traits/TriggerNotifications.php`
- `app/Helpers/NotificationHelper.php`

Database:
- `database/migrations/2026_04_10_000010_create_notifications_table_new.php`

Routes:
- `routes/api.php` (updated with notification endpoints)

---

## CONTACT & SUPPORT

For questions or issues related to notification system, check:
1. API response messages
2. `storage/logs/laravel.log`
3. `notification_logs` table for action history
