<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;

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

// Authentication Routes (Public)
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

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


