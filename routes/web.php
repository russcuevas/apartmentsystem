<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\tenant\TenantDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// --- ADMIN AUTH ROUTES ---
Route::get('/admin/login', [AuthController::class, 'AdminLoginPage'])->name('admin.login.page');
Route::post('/admin/login', [AuthController::class, 'AdminLogin'])->name('admin.login');
Route::post('/admin/logout', [AuthController::class, 'AdminLogout'])->name('admin.logout');

// --- ADMIN PROTECTED ROUTES ---
Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard.page');
});

// --- TENANT AUTH ROUTES ---
Route::get('/tenant/login', [AuthController::class, 'TenantLoginPage'])->name('tenant.login.page');
Route::post('/tenant/login', [AuthController::class, 'TenantLogin'])->name('tenant.login');
Route::post('/tenant/logout', [AuthController::class, 'TenantLogout'])->name('tenant.logout');

// --- TENANT PROTECTED ROUTES ---
Route::middleware(['tenant'])->prefix('tenant')->group(function () {
    Route::get('/dashboard', [TenantDashboardController::class, 'TenantDashboardPage'])->name('tenant.dashboard.page');
});
