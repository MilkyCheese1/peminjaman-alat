<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home  
Route::get('/', function () {
    return view('index');
});

// Auth Pages
Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

// Dashboard Pages
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $viewName = match($user->role) {
            'admin' => 'dashboard-admin',
            'petugas' => 'dashboard-staff',
            default => 'dashboard-user'
        };
        return view($viewName);
    });
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('api')->group(function () {
    // Auth API
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

        // Admin & Staff
        Route::middleware('role:admin')->group(function () {
            Route::get('/users', [DashboardController::class, 'getUsers']);
            Route::post('/alat', [AlatController::class, 'store']);
            Route::put('/alat/{id}', [AlatController::class, 'update']);
            Route::delete('/alat/{id}', [AlatController::class, 'destroy']);
        });

        // Staff & Admin - Peminjaman Management
        Route::middleware('role:petugas')->group(function () {
            Route::get('/peminjaman', [PeminjamanController::class, 'index']);
            Route::put('/peminjaman/{id}/status', [PeminjamanController::class, 'updateStatus']);
        });
    });
});
