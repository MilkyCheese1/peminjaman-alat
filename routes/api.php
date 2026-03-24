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

    // Alat
    Route::get('/alat', [AlatController::class, 'index']);
    Route::get('/alat/{id}', [AlatController::class, 'show']);
    Route::get('/kategoris', [AlatController::class, 'getCategories']);

    // Peminjaman (User)
    Route::get('/my-borrowings', [PeminjamanController::class, 'getMyBorrowings']);
    Route::get('/borrow-history', [PeminjamanController::class, 'getBorrowHistory']);
    Route::post('/peminjaman', [PeminjamanController::class, 'store']);

    // Admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [DashboardController::class, 'getUsers']);
        Route::post('/alat', [AlatController::class, 'store']);
        Route::put('/alat/{id}', [AlatController::class, 'update']);
        Route::delete('/alat/{id}', [AlatController::class, 'destroy']);
    });

    // Staff & Admin - Peminjaman Management
    Route::middleware('auth')->group(function () {
        Route::get('/peminjaman', function (Request $request) {
            $user = Auth::user();
            if ($user->role !== 'admin' && $user->role !== 'petugas') {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            $controller = new PeminjamanController();
            return $controller->index($request);
        });
        
        Route::put('/peminjaman/{id}', function (Request $request, $id) {
            $user = Auth::user();
            if ($user->role !== 'admin' && $user->role !== 'petugas') {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            $controller = new PeminjamanController();
            return $controller->updateStatus($request, $id);
        });
    });
});
