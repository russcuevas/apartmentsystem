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

Route::get('/admin/login', [AuthController::class, 'AdminLoginPage'])->name('admin.login.page');
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard.page');

Route::get('/tenant/login', [AuthController::class, 'TenantLoginPage'])->name('tenant.login.page');


Route::get('/tenant/dashboard', [TenantDashboardController::class, 'TenantDashboardPage'])->name('tenant.dashboard.page');
