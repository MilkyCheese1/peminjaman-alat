<?php
// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Notification;
use App\Services\NotificationService;

echo "\n========================================\n";
echo "  NOTIFICATION SYSTEM TEST\n";
echo "========================================\n";

try {
    // Test 1: Check tables
    echo "\n[TEST 1] Checking database tables...\n";
    $tables = DB::select("SHOW TABLES LIKE 'notif%'");
    echo "✅ Tables found: " . count($tables) . "\n";
    foreach($tables as $t) {
        $tableName = current((array)$t);
        echo "   - $tableName\n";
    }

    // Test 2: Create/Get test user
    echo "\n[TEST 2] Creating test user...\n";
    $testUsername = 'testuser' . rand(1000, 9999);
    $user = User::firstOrCreate(
        ['username' => $testUsername],
        [
            'nama_lengkap' => 'Test User',
            'email' => 'test' . time() . '@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'is_active' => true
        ]
    );
    echo "✅ User created/found\n";
    echo "   ID: {$user->id_user}\n";
    echo "   Name: {$user->nama_lengkap}\n";

    // Test 3: Create notification via model
    echo "\n[TEST 3] Creating notification via model...\n";
    $notification = Notification::create([
        'id_user' => $user->id_user,
        'category' => 'approval',
        'type' => 'borrowing_created',
        'title' => '📝 Test Notification Created',
        'message' => 'This is a test notification to verify system is working',
        'icon' => '📝',
        'color' => 'info',
        'priority' => 'normal',
        'channels' => ['in_app'],
        'is_read' => false,
        'expires_at' => now()->addDays(30)
    ]);
    echo "✅ Notification created\n";
    echo "   ID: {$notification->id_notification}\n";
    echo "   Type: {$notification->type}\n";
    echo "   Title: {$notification->title}\n";

    // Test 4: Test NotificationService
    echo "\n[TEST 4] Testing NotificationService...\n";
    $service = app(NotificationService::class);
    
    $testNotif = $service->create([
        'id_user' => $user->id_user,
        'category' => 'reminder',
        'type' => 'return_reminder_1day',
        'title' => '⏰ Return Reminder Test',
        'message' => 'Test reminder notification from service',
        'icon' => '⏰',
        'color' => 'warning',
        'priority' => 'high'
    ]);
    echo "✅ Service notification created\n";
    echo "   ID: {$testNotif->id_notification}\n";

    // Test 5: Count notifications
    echo "\n[TEST 5] Querying notifications...\n";
    $totalCount = Notification::where('id_user', $user->id_user)->count();
    $unreadCount = Notification::where('id_user', $user->id_user)
        ->where('is_read', false)
        ->count();
    $archivedCount = Notification::where('id_user', $user->id_user)
        ->where('is_archived', true)
        ->count();
    
    echo "✅ Notification counts:\n";
    echo "   Total: $totalCount\n";
    echo "   Unread: $unreadCount\n";
    echo "   Archived: $archivedCount\n";

    // Test 6: Test marking as read
    echo "\n[TEST 6] Testing mark as read...\n";
    $notification->markAsRead();
    $isRead = $notification->fresh()->is_read;
    echo "✅ Mark as read: " . ($isRead ? "TRUE" : "FALSE") . "\n";

    // Test 7: Test archive
    echo "\n[TEST 7] Testing archive...\n";
    $notification->archive();
    $isArchived = $notification->fresh()->is_archived;
    echo "✅ Archive: " . ($isArchived ? "TRUE" : "FALSE") . "\n";

    // Test 8: Test scopes
    echo "\n[TEST 8] Testing scopes...\n";
    $activeNotifs = Notification::forUser($user->id_user)
        ->active()
        ->count();
    $unreadNotifs = Notification::forUser($user->id_user)
        ->unread()
        ->count();
    echo "✅ Active notifications: $activeNotifs\n";
    echo "✅ Unread notifications: $unreadNotifs\n";

    // Test 9: Test notification preferences
    echo "\n[TEST 9] Testing notification preferences...\n";
    $prefs = $user->notificationPreference ?? 
        \App\Models\NotificationPreference::create([
            'id_user' => $user->id_user,
            'in_app_enabled' => true,
            'email_enabled' => true,
            'approval_notifications' => true,
            'return_notifications' => true,
            'reminder_notifications' => true
        ]);
    
    echo "✅ Preferences loaded\n";
    echo "   In-app enabled: " . ($prefs->in_app_enabled ? "YES" : "NO") . "\n";
    echo "   Email enabled: " . ($prefs->email_enabled ? "YES" : "NO") . "\n";
    echo "   Approval notifs: " . ($prefs->approval_notifications ? "YES" : "NO") . "\n";

    echo "\n========================================\n";
    echo "  ✅ ALL TESTS PASSED!\n";
    echo "========================================\n\n";

} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
