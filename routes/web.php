<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
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

// Admin routes
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
    Route::post('/banner/delete', [BannerController::class, 'destroy'])->name('admin.banner.delete');

    // Users
    Route::get('/user', [UserController::class, 'index'])->name('admin.user');
    Route::get('/user/show/{id}', [UserController::class, 'show'])->name('admin.user.show');
    Route::get('/user/filter', [UserController::class, 'filter'])->name('admin.user.filter');
    Route::post('/user/get', [UserController::class, 'get'])->name('admin.user.get');
    Route::post('/user', [UserController::class, 'store'])->name('admin.user.store');
    Route::post('/user/update', [UserController::class, 'update'])->name('admin.user.update');
    Route::post('/user/update-status', [UserController::class, 'updateStatus'])->name('admin.user.update.status');
    Route::post('/user/delete', [UserController::class, 'destroy'])->name('admin.user.delete');

    // Roles
    Route::get('/role', [RoleController::class, 'index'])->name('admin.role');
    Route::get('/role/filter', [RoleController::class, 'filter'])->name('admin.role.filter');
    Route::post('/role/get', [RoleController::class, 'get'])->name('admin.role.get');
    Route::post('/role', [RoleController::class, 'store'])->name('admin.role.store');
    Route::post('/role/update', [RoleController::class, 'update'])->name('admin.role.update');
    Route::post('/role/update-status', [RoleController::class, 'updateStatus'])->name('admin.role.update.status');
    Route::post('/role/delete', [RoleController::class, 'destroy'])->name('admin.role.delete');

    // Navbars
    Route::get('/navbar', [NavbarController::class, 'index'])->name('admin.navbar');
    Route::get('/navbar/show/{id}', [NavbarController::class, 'show'])->name('admin.navbar.show');
    Route::get('/navbar/filter', [NavbarController::class, 'filter'])->name('admin.navbar.filter');
    Route::post('/navbar/get', [NavbarController::class, 'get'])->name('admin.navbar.get');
    Route::post('/navbar', [NavbarController::class, 'store'])->name('admin.navbar.store');
    Route::post('/navbar/update', [NavbarController::class, 'update'])->name('admin.navbar.update');
    Route::post('/navbar/update-status', [NavbarController::class, 'updateStatus'])->name('admin.navbar.update.status');
    Route::post('/navbar/delete', [NavbarController::class, 'destroy'])->name('admin.navbar.delete');
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

// Website routes
Route::get('/', function() {
    return redirect()->route('admin.dashboard');
});