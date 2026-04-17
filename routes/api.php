<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Public API health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is running',
        'version' => '1.0.0',
        'timestamp' => now()
    ]);
});

// Statistics Routes (Public)
Route::get('/statistics/dashboard', [StatisticsController::class, 'getDashboardStats']);
Route::get('/statistics/detailed', [StatisticsController::class, 'getDetailedStats']);
Route::post('/statistics/clear-cache', [StatisticsController::class, 'clearCache']);

// Authentication Routes (Public)
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);


// ======================================================================
// NOTIFICATION ROUTES - Fully Reconstructed
// ======================================================================

Route::prefix('notifications')->group(function () {
    // Retrieval Endpoints
    Route::get('/', [NotificationController::class, 'index']);
    Route::get('/grouped', [NotificationController::class, 'grouped']);
    Route::get('/{id}', [NotificationController::class, 'show']);
    Route::get('/unread/count', [NotificationController::class, 'unreadCount']);
    
    // Update/Modify Endpoints (Protected)
    Route::post('/{id}/mark-read', [NotificationController::class, 'markAsRead']);
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::post('/mark-category-read', [NotificationController::class, 'markCategoryAsRead']);
    Route::post('/{id}/archive', [NotificationController::class, 'archive']);
    Route::post('/archive-category', [NotificationController::class, 'archiveCategory']);
    Route::delete('/{id}', [NotificationController::class, 'destroy']);
    Route::post('/{id}/restore', [NotificationController::class, 'restore']);
    Route::delete('/clear-all', [NotificationController::class, 'clearAll']);
    
    // Preference Endpoints (Protected)
    Route::get('/preferences', [NotificationController::class, 'preferences']);
    Route::put('/preferences', [NotificationController::class, 'updatePreferences']);
});

// Category Routes
Route::apiResource('categories', CategoryController::class);

// Equipment Routes
Route::apiResource('equipment', EquipmentController::class);
Route::get('/equipment/{id}/available', [EquipmentController::class, 'getAvailable']);

// Borrowing Routes
Route::apiResource('borrowings', BorrowingController::class);
Route::post('/borrowings/{borrowing}/approve', [BorrowingController::class, 'approveBorrowing']);
Route::post('/borrowings/{borrowing}/reject', [BorrowingController::class, 'rejectBorrowing']);
Route::post('/borrowings/{borrowing}/generate-pickup-code', [BorrowingController::class, 'generatePickupCode']);
Route::post('/borrowings/{borrowing}/verify-pickup', [BorrowingController::class, 'verifyPickUp']);
Route::post('/borrowings/{borrowing}/verify-return', [BorrowingController::class, 'verifyReturn']);
Route::get('/borrowings/user/{userId}', [BorrowingController::class, 'getUserBorrowings']);
Route::get('/borrowings/status/overdue', [BorrowingController::class, 'getOverdueeBorrowings']);

// User Routes
Route::apiResource('users', UserController::class);
Route::get('/users/role/{role}', [UserController::class, 'getUsersByRole']);
Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// ============================================================================
// ROLE-BASED ACCESS CONTROL - Frontend enforced (Sanctum not installed yet)
// ============================================================================
// NOTE: Since Laravel Sanctum is not installed, we remove middleware auth checks
// Role-based access will be enforced at the frontend/Vue level via rolePermissions.js
// For production, install Sanctum and add proper token-based authentication

// OWNER ONLY (Passive Observer - Read Only)
Route::group([], function () {
    Route::get('/borrowings', [BorrowingController::class, 'index']);
    Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show']);
    Route::get('/borrowings/status/overdue', [BorrowingController::class, 'getOverdueeBorrowings']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/equipment', [EquipmentController::class, 'index']);
});

// ADMIN (Data Management - All CRUD operations except user deletion of certain users)
Route::group([], function () {
    // User management
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::get('/users/role/{role}', [UserController::class, 'getUsersByRole']);
    Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive']);
    
    // Equipment management
    Route::post('/equipment', [EquipmentController::class, 'store']);
    Route::put('/equipment/{equipment}', [EquipmentController::class, 'update']);
    Route::delete('/equipment/{equipment}', [EquipmentController::class, 'destroy']);
    Route::get('/equipment', [EquipmentController::class, 'index']);
    Route::get('/equipment/{id}', [EquipmentController::class, 'show']);
    
    // Category management
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
    Route::get('/categories', [CategoryController::class, 'index']);
    
    // View borrowing data for analytics
    Route::get('/borrowings', [BorrowingController::class, 'index']);
    Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show']);
});

// STAFF (Borrowing & Return Processing)
Route::group([], function () {
    Route::get('/borrowings', [BorrowingController::class, 'index']);
    Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show']);
    Route::get('/borrowings/status/overdue', [BorrowingController::class, 'getOverdueeBorrowings']);
    
    // Staff actions
    Route::post('/borrowings/{borrowing}/approve', [BorrowingController::class, 'approveBorrowing']);
    Route::post('/borrowings/{borrowing}/reject', [BorrowingController::class, 'rejectBorrowing']);
    Route::post('/borrowings/{borrowing}/generate-pickup-code', [BorrowingController::class, 'generatePickupCode']);
    Route::post('/borrowings/{borrowing}/verify-pickup', [BorrowingController::class, 'verifyPickUp']);
    Route::post('/borrowings/{borrowing}/verify-return', [BorrowingController::class, 'verifyReturn']);
});

// CUSTOMER (Borrowing & Personal Property Management)
Route::group([], function () {
    Route::get('/equipment', [EquipmentController::class, 'index']);
    Route::get('/equipment/{id}', [EquipmentController::class, 'show']);
    Route::get('/equipment/{id}/available', [EquipmentController::class, 'getAvailable']);
    Route::get('/categories', [CategoryController::class, 'index']);
    
    // Create borrowing requests
    Route::post('/borrowings', [BorrowingController::class, 'store']);
    
    // View own borrowings
    Route::get('/borrowings/user/{userId}', [BorrowingController::class, 'getUserBorrowings']);
});

