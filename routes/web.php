<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DomainUrlController;
use App\Http\Controllers\ContactDetailController;
use App\Http\Controllers\VersionController;
use App\Http\Controllers\ApiController;

// ─── Auth routes ────────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Public API routes (backward compatible with old Android app paths) ──────
Route::prefix('api')->group(function () {
    // Generic (maps to king4k) — old paths: /api/domain_Url etc.
    Route::get('domain_Url',           [ApiController::class, 'genericDomainUrl']);
    Route::get('contact_detail',       [ApiController::class, 'genericContactDetail']);
    Route::get('apk/version/{version}',[ApiController::class, 'genericApkVersion']);

    // Per-product — old paths: /api/iboActiveCode/domain_Url etc.
    Route::get('{slug}/domain_Url',            [ApiController::class, 'domainUrl']);
    Route::get('{slug}/contact_detail',        [ApiController::class, 'contactDetail']);
    Route::get('{slug}/apk/version/{version}', [ApiController::class, 'apkVersion']);
});

// ─── Protected admin routes ───────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/', fn () => redirect()->route('domain-urls.index'));

    // Products CRUD
    Route::resource('products', ProductController::class)->except(['show']);

    // Domain URLs
    Route::get('/domain-urls', [DomainUrlController::class, 'index'])->name('domain-urls.index');
    Route::put('/domain-urls/{product}', [DomainUrlController::class, 'update'])->name('domain-urls.update');

    // Contact Details
    Route::get('/contact-details', [ContactDetailController::class, 'index'])->name('contact-details.index');
    Route::put('/contact-details/{product}', [ContactDetailController::class, 'update'])->name('contact-details.update');

    // Versions
    Route::get('/versions', [VersionController::class, 'index'])->name('versions.index');
    Route::post('/versions/{product}', [VersionController::class, 'update'])->name('versions.update');
});
