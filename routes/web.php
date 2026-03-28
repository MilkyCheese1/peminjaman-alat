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
            'owner' => 'dashboard-owner',
            default => 'dashboard-user'
        };
        return view($viewName);
    });
});

/*
|--------------------------------------------------------------------------
| API Routes are defined in routes/api.php
|--------------------------------------------------------------------------
*/
