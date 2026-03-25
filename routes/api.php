<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DashboardController;

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
    // Profile
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    Route::post('/profile/change-password', [AuthController::class, 'changePassword']);

    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);

    // ===== ALAT MANAGEMENT =====
    // Public endpoints for all authenticated users
    Route::get('/alat', [AlatController::class, 'index']);
    Route::get('/alat/{id}', [AlatController::class, 'show']);
    Route::get('/alat/{id}/stock-info', [AlatController::class, 'getStockInfo']);
    Route::get('/alat/{id}/available-dates', [AlatController::class, 'getAvailableDates']);
    Route::get('/kategoris', [AlatController::class, 'getCategories']);

    // Admin only - Create, Update, Delete Alat
    Route::middleware('role:admin')->group(function () {
        Route::post('/alat', [AlatController::class, 'store']);
        Route::put('/alat/{id}', [AlatController::class, 'update']);
        Route::delete('/alat/{id}', [AlatController::class, 'destroy']);
        Route::post('/alat/{id}/maintenance', [AlatController::class, 'setMaintenance']);
    });

    // ===== PEMINJAMAN MANAGEMENT =====
    // User - Create borrowing request and view own history
    Route::post('/peminjaman', [PeminjamanController::class, 'store']);
    Route::get('/my-borrowings', [PeminjamanController::class, 'getMyBorrowings']);
    Route::get('/borrow-history', [PeminjamanController::class, 'getBorrowHistory']);
    Route::post('/peminjaman/check-availability/{id_alat}', [PeminjamanController::class, 'checkAvailability']);

    // Admin & Petugas (Staff) - Manage all borrowings
    Route::middleware('role:admin,petugas')->group(function () {
        Route::get('/peminjaman', [PeminjamanController::class, 'index']);
        Route::put('/peminjaman/{id}', [PeminjamanController::class, 'updateStatus']);
        Route::get('/peminjaman/schedule/{id_alat}', [PeminjamanController::class, 'getSchedule']);
    });

    // Users endoint
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [DashboardController::class, 'getUsers']);
    });
});

