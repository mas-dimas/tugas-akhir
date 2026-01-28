<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompetitionController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\SubmissionDocumentController;

// ============================================
// PUBLIC ROUTES (Tidak perlu auth)
// ============================================

// Authentication Routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Public Competition Routes
Route::get('/competitions', [CompetitionController::class, 'index']);
Route::get('/competitions/{id}', [CompetitionController::class, 'show']);

// ============================================
// PROTECTED ROUTES (Perlu auth)
// ============================================

Route::middleware('auth:sanctum')->group(function () {
    // User Info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User Registrations
    Route::get('/registrations', [RegistrationController::class, 'userRegistrations']);
    Route::post('/registrations', [RegistrationController::class, 'store']);
    Route::get('/registrations/{id}', [RegistrationController::class, 'show']);

    // Submission Documents
    Route::get('/registrations/{registrationId}/documents', [SubmissionDocumentController::class, 'index']);
    Route::post('/registrations/{registrationId}/documents', [SubmissionDocumentController::class, 'store']);
    Route::delete('/documents/{documentId}', [SubmissionDocumentController::class, 'destroy']);
    
    // Download Document
    Route::get('/documents/{documentId}/download', [SubmissionDocumentController::class, 'download']);
});

// ============================================
// ADMIN ROUTES (Admin only)
// ============================================

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Admin Competition Management
    Route::post('/admin/competitions', [CompetitionController::class, 'store']);
    Route::put('/admin/competitions/{id}', [CompetitionController::class, 'update']);
    Route::delete('/admin/competitions/{id}', [CompetitionController::class, 'destroy']);

    // Admin Registration Review
    Route::get('/admin/registrations', [RegistrationController::class, 'allRegistrations']);
    Route::put('/admin/registrations/{id}/status', [RegistrationController::class, 'updateStatus']);

    // Admin Document Review
    Route::put('/admin/documents/{id}/review', [SubmissionDocumentController::class, 'review']);
});
