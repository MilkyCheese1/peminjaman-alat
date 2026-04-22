<?php

use App\Http\Controllers\Api\BorrowingController;
use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ToolController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FeedbackController;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response()->json(['ok' => true]));

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::apiResource('categories', CategoryController::class)->except(['show']);
Route::apiResource('tools', ToolController::class)->except(['show']);
Route::apiResource('users', UserController::class)->except(['show']);
Route::put('/users/{user}/password', [UserController::class, 'updatePassword']);
Route::apiResource('borrowings', BorrowingController::class)->except(['show']);
Route::put('/borrowings/{borrowing}/return', [BorrowingController::class, 'confirmReturn']);
Route::apiResource('feedback', FeedbackController::class)->only(['index', 'store']);
Route::apiResource('notifications', NotificationController::class)->only(['index']);
Route::apiResource('activity-logs', ActivityLogController::class)->only(['index']);

