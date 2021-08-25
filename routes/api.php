<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BannerApi;
use App\Http\Controllers\Api\TestimonialApi;
use App\Http\Controllers\Api\AuthApi;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Authenticate routes
Route::post('/login', [AuthApi::class, 'login']);
Route::post('/signup', [AuthApi::class, 'signup']);

// APIs
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/banners', [BannerApi::class, 'index']);
    Route::get('/testimonials', [TestimonialApi::class, 'index']);

    // Logout
    Route::post('/logout', [AuthApi::class, 'logout']);
});
