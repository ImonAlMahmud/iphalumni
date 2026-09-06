<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\DirectoryController;
use App\Http\Controllers\Api\v1\EventController;
use App\Http\Controllers\Api\v1\InnovationController;
use App\Http\Controllers\Api\v1\MemberController;
use App\Http\Controllers\Api\v1\NoticeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile & External REST API Routes (Version 1)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // 1. Authentication
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('api.v1.auth.login');
        Route::post('/register', [AuthController::class, 'register'])->name('api.v1.auth.register');

        Route::middleware('auth.api')->group(function () {
            Route::get('/me', [AuthController::class, 'me'])->name('api.v1.auth.me');
            Route::post('/logout', [AuthController::class, 'logout'])->name('api.v1.auth.logout');
        });
    });

    // 2. Member Digital ID & Smart Passes (Authenticated)
    Route::prefix('member')->middleware('auth.api')->group(function () {
        Route::get('/profile', [MemberController::class, 'profile'])->name('api.v1.member.profile');
        Route::get('/id-card', [MemberController::class, 'idCard'])->name('api.v1.member.id_card');
        Route::get('/smart-pass', [MemberController::class, 'smartPass'])->name('api.v1.member.smart_pass');
    });

    // 3. Alumni Directory
    Route::prefix('directory')->group(function () {
        Route::get('/', [DirectoryController::class, 'index'])->name('api.v1.directory.index');
        Route::get('/{id}', [DirectoryController::class, 'show'])->name('api.v1.directory.show');
    });

    // 4. Official Notices & Circulars
    Route::prefix('notices')->group(function () {
        Route::get('/', [NoticeController::class, 'index'])->name('api.v1.notices.index');
        Route::get('/{idOrRef}', [NoticeController::class, 'show'])->name('api.v1.notices.show');
    });

    // 5. Events & Reunion
    Route::prefix('events')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('api.v1.events.index');
        Route::get('/{id}', [EventController::class, 'show'])->name('api.v1.events.show');
    });

    // 6. Innovation Features
    // Gate Pass QR Scanner Verification (Instant Attendance Check-in)
    Route::post('/verify/scan', [InnovationController::class, 'scanVerify'])->middleware('auth.api')->name('api.v1.verify.scan');

    // Emergency Blood Donors Search
    Route::get('/blood-donors', [InnovationController::class, 'bloodDonors'])->name('api.v1.blood_donors');

    // Remote App Configuration & Branding
    Route::get('/config', [InnovationController::class, 'appConfig'])->name('api.v1.config');
});
