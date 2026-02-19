<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompetitionController;
use App\Http\Controllers\Api\RegistrationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// ============================================
// PUBLIC ROUTES (No authentication required)
// ============================================

// Authentication Routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// Public Competition Routes
Route::get('/competitions', [CompetitionController::class, 'index']);
Route::get('/competitions/{id}', [CompetitionController::class, 'show']);

// ============================================
// PROTECTED ROUTES (Authentication required)
// ============================================

Route::middleware('auth:sanctum')->group(function () {
    // Auth Routes
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // User Registrations
    Route::get('/registrations', [RegistrationController::class, 'index']);
    Route::post('/registrations', [RegistrationController::class, 'store']);
    Route::get('/registrations/{id}', [RegistrationController::class, 'show']);
    Route::delete('/registrations/{id}', [RegistrationController::class, 'destroy']);
});

// ============================================
// ADMIN ROUTES (Admin role required)
// ============================================

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Admin Competition Management
    Route::post('/competitions', [CompetitionController::class, 'store']);
    Route::put('/competitions/{id}', [CompetitionController::class, 'update']);
    Route::delete('/competitions/{id}', [CompetitionController::class, 'destroy']);

    // Admin Registration Management
    Route::get('/admin/registrations', [RegistrationController::class, 'adminIndex']);
});
