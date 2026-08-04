<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\LocationsController;
use App\Http\Controllers\admin\TenantsController;
use App\Http\Controllers\admin\AdminBillingsController;
use App\Http\Controllers\tenant\TenantDashboardController;
use App\Http\Controllers\tenant\TenantPaymentsController;
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
Route::post('/admin/login', [AuthController::class, 'AdminLoginRequest'])->name('admin.login.request');
Route::post('/admin/logout', [AuthController::class, 'AdminLogoutRequest'])->name('admin.logout.request');

// --- ADMIN PROTECTED ROUTES ---
Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'AdminDashboardPage'])->name('admin.dashboard.page');
    Route::get('/admin/locations/{id}', [LocationsController::class, 'LocationsPage'])->name('admin.locations.page');
    Route::get('/admin/tenants', [TenantsController::class, 'TenantPage'])->name('admin.tenants.page');
    Route::post('/admin/tenants', [TenantsController::class, 'store'])->name('admin.tenants.store');
    Route::get('/admin/billings', [AdminBillingsController::class, 'index'])->name('admin.billings.index');
    Route::post('/admin/billings', [AdminBillingsController::class, 'store'])->name('admin.billings.store');
});

// --- TENANT AUTH ROUTES ---
Route::get('/tenant/login', [AuthController::class, 'TenantLoginPage'])->name('tenant.login.page');
Route::post('/tenant/login', [AuthController::class, 'TenantLogin'])->name('tenant.login');
Route::post('/tenant/logout', [AuthController::class, 'TenantLogout'])->name('tenant.logout');

// --- TENANT PROTECTED ROUTES ---
Route::middleware(['tenant'])->group(function () {
    Route::get('/tenant/dashboard', [TenantDashboardController::class, 'TenantDashboardPage'])->name('tenant.dashboard.page');
    Route::get('/tenant/payments', [TenantPaymentsController::class, 'index'])->name('tenant.payments.index');
    Route::post('/tenant/payments', [TenantPaymentsController::class, 'store'])->name('tenant.payments.store');
});
