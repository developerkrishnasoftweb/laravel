<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\NavbarController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('admin')->middleware(['auth'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->middleware(['password.confirm'])->name('admin.profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('admin.profile');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('admin.profile.changePassword');
    Route::get('/confirm-password', [AuthController::class, 'confirmPassword'])->name('password.confirm');
    Route::post('/confirm-password', [AuthController::class, 'verifyConfirmPassword'])->name('password.confirm');
    // Banners
    Route::get('/banner', [BannerController::class, 'index'])->name('admin.banner');
    Route::get('/banner/filter', [BannerController::class, 'filter'])->name('admin.banner.filter');
    Route::post('/banner/get', [BannerController::class, 'get'])->name('admin.banner.get');
    Route::post('/banner', [BannerController::class, 'store'])->name('admin.banner.store');
    Route::post('/banner/update', [BannerController::class, 'update'])->name('admin.banner.update');
    Route::post('/banner/update-status', [BannerController::class, 'updateStatus'])->name('admin.banner.update.status');
    Route::post('/banner/delete', [BannerController::class, 'delete'])->name('admin.banner.delete');
    // Navbar
    Route::get('/navbar', [NavbarController::class, 'index'])->name('admin.navbar');
    Route::get('/navbar/{id}', [NavbarController::class, 'show'])->name('admin.navbar.show');
    Route::get('/navbar/filter', [NavbarController::class, 'filter'])->name('admin.navbar.filter');
    Route::post('/navbar/get', [NavbarController::class, 'get'])->name('admin.navbar.get');
    Route::post('/navbar', [NavbarController::class, 'store'])->name('admin.navbar.store');
    Route::post('/navbar/update', [NavbarController::class, 'update'])->name('admin.navbar.update');
    Route::post('/navbar/update-status', [NavbarController::class, 'updateStatus'])->name('admin.navbar.update.status');
    Route::post('/navbar/delete', [NavbarController::class, 'delete'])->name('admin.navbar.delete');
});

// Auth routes
Route::middleware(['guest'])->group(function() {
    Route::get('/login', [AuthController::class, 'login'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('admin.login.authenticate');
    Route::get('/forgot', [AuthController::class, 'forgotPassword'])->name('admin.forgot');
    Route::post('/forgot', [AuthController::class, 'sendForgotPasswordEmail'])->name('admin.forgot.email');
    Route::get('/reset/{token}', [AuthController::class, 'resetPassword'])->name('admin.password.reset');
    Route::post('/reset', [AuthController::class, 'changePassword'])->name('admin.password.update');
});
