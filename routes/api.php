<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\StatusUpdateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth API (No authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Protected routes
Route::middleware('auth')->group(function () {
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);
    
    // Get user photo (public)
    Route::get('/users/{userId}/photo', [ProfileController::class, 'getPhoto']);

    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);

    // ===== ALAT MANAGEMENT =====
    // Public endpoints for all authenticated users
    Route::get('/alat', [AlatController::class, 'index']);
    Route::get('/alat/{id}', [AlatController::class, 'show']);
    Route::get('/alat/{id}/stock-info', [AlatController::class, 'getStockInfo']);
    Route::get('/alat/{id}/available-dates', [AlatController::class, 'getAvailableDates']);
    Route::get('/kategoris', [KategoriController::class, 'index']);
    Route::get('/kategoris/{id}', [KategoriController::class, 'show']);

    // Admin & Owner - Manage Categories
    Route::middleware('role:admin,owner')->group(function () {
        Route::post('/kategoris', [KategoriController::class, 'store']);
        Route::put('/kategoris/{id}', [KategoriController::class, 'update']);
        Route::delete('/kategoris/{id}', [KategoriController::class, 'destroy']);
    });

    // Admin & Owner - Create, Update, Delete Alat, Upload Gambar
    Route::middleware('role:admin,owner')->group(function () {
        Route::post('/alat', [AlatController::class, 'store']);
        Route::put('/alat/{id}', [AlatController::class, 'update']);
        Route::delete('/alat/{id}', [AlatController::class, 'destroy']);
        Route::post('/alat/{id}/maintenance', [AlatController::class, 'setMaintenance']);
        
        // File upload
        Route::post('/alat/{id}/upload-image', [FileUploadController::class, 'uploadAlatImage']);
        Route::delete('/alat/{id}/delete-image', [FileUploadController::class, 'deleteAlatImage']);
    });

    // Get alat image (public)
    Route::get('/alat/{id}/image', [FileUploadController::class, 'getAlatImage']);

    // ===== PEMINJAMAN MANAGEMENT =====
    // User - Create borrowing request and view own history
    Route::post('/peminjaman', [PeminjamanController::class, 'store']);
    Route::get('/my-borrowings', [PeminjamanController::class, 'getMyBorrowings']);
    Route::get('/borrow-history', [PeminjamanController::class, 'getBorrowHistory']);
    Route::post('/peminjaman/check-availability/{id_alat}', [PeminjamanController::class, 'checkAvailability']);

    // ===== STATUS UPDATE - Petugas & Admin & Owner =====
    Route::middleware('role:petugas,admin,owner')->group(function () {
        Route::put('/peminjaman/{id_peminjaman}/status', [StatusUpdateController::class, 'updateStatus']);
        Route::post('/peminjaman/bulk-status-update', [StatusUpdateController::class, 'bulkUpdateStatus']);
        Route::get('/peminjaman/{id_peminjaman}/valid-transitions', [StatusUpdateController::class, 'getValidTransitions']);
    });

    // Admin & Owner & Petugas - Manage all borrowings
    Route::middleware('role:petugas,admin,owner')->group(function () {
        Route::get('/peminjaman', [PeminjamanController::class, 'index']);
        Route::get('/peminjaman/schedule/{id_alat}', [PeminjamanController::class, 'getSchedule']);
    });

    // ===== QR CODE & APPROVAL =====
    // Get QR code (peminjam, petugas, admin, owner)
    Route::get('/peminjaman/{id_peminjaman}/qr-code', [QrCodeController::class, 'getQrCode']);
    
    // Scan QR code
    Route::post('/qr-code/scan', [QrCodeController::class, 'scanQrCode']);
    
    // Approve via QR scan (hanya petugas, admin, owner)
    Route::post('/qr-code/approve', [QrCodeController::class, 'approveViaScan'])
        ->middleware('role:petugas,admin,owner');

    // ===== TRASH & RESTORE =====
    // View trash
    Route::get('/trash', [TrashController::class, 'getTrashedItems']);
    
    // Restore (hanya owner)
    Route::middleware('role:owner')->group(function () {
        Route::post('/trash/restore', [TrashController::class, 'restore']);
        Route::post('/trash/permanent-delete', [TrashController::class, 'permanentlyDelete']);
        Route::post('/trash/empty', [TrashController::class, 'emptyTrash']);
    });

    // ===== ACTIVITY LOGS =====
    // View activity logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index']);
    Route::get('/activity-logs/model/{modelType}/{modelId}', [ActivityLogController::class, 'getModelLogs']);
    Route::get('/activity-logs/user/{id_user}', [ActivityLogController::class, 'getUserLogs']);
    Route::get('/activity-logs/summary', [ActivityLogController::class, 'getSummary']);
    
    // Clear old logs (hanya owner)
    Route::middleware('role:owner')->group(function () {
        Route::delete('/activity-logs/clear-old', [ActivityLogController::class, 'clearOldLogs']);
    });

    // ===== USERS MANAGEMENT =====
    Route::middleware('role:admin,owner')->group(function () {
        Route::get('/users', [DashboardController::class, 'getUsers']);
    });
});


