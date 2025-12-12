<?php

use Illuminate\Support\Facades\Route;

// Controller untuk Admin

use App\Http\Controllers\Admin\CompetitionController as AdminCompetitionController;
use App\Http\Controllers\Admin\RegistrationReviewController;
use App\Http\Controllers\Admin\DocumentTemplateController;

// Controller untuk Peserta
use App\Http\Controllers\Peserta\CompetitionController as PesertaCompetitionController;
use App\Http\Controllers\Peserta\RegistrationController;

// --------------------------------------
// ROOT ROUTE (redirect ke dashboard)
// --------------------------------------
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// --------------------------------------
// REQUIRE AUTH ROUTES (Breeze)
// --------------------------------------
require __DIR__.'/auth.php';

// --------------------------------------
// DASHBOARD (umum, tapi tampil beda sesuai role)
// --------------------------------------
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if (!$user) {
        return redirect('/login');
    }

    return match ($user->role) {
        'admin'   => redirect()->route('admin.dashboard'),
        'peserta' => redirect()->route('peserta.dashboard'),
        default   => abort(403),
    };
})->middleware(['auth'])->name('dashboard');


// =================================================================
// 🟥 ADMIN AREA
// =================================================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard admin
	Route::get('/dashboard', fn() => redirect()->route('admin.competitions.index'))->name('dashboard');
        Route::resource('competitions', AdminCompetitionController::class);
        
	// CRUD Lomba
        Route::resource('competitions', AdminCompetitionController::class);

        // Melihat semua peserta yang mendaftar lomba tertentu
        Route::get('registrations/{competition}', 
            [RegistrationReviewController::class, 'index'])
            ->name('registrations.index');

        // Melihat detail pendaftaran peserta
        Route::get('registrations/{registration}/show', 
            [RegistrationReviewController::class, 'show'])
            ->name('registrations.show');

        // Update status dokumen peserta
        Route::post('documents/{document}/status', 
            [RegistrationReviewController::class, 'updateStatus'])
            ->name('documents.updateStatus');

	// Route untuk template dokumen
        Route::get('/competitions/{competition}/templates', [DocumentTemplateController::class, 'index'])
            ->name('competitions.templates.index');

        Route::post('/competitions/{competition}/templates', [DocumentTemplateController::class, 'store'])
            ->name('competitions.templates.store');

        Route::delete('/templates/{template}', [DocumentTemplateController::class, 'destroy'])
            ->name('templates.destroy');



    });


// =================================================================
// 🟦 PESERTA AREA
// =================================================================
Route::middleware(['auth', 'role:peserta'])
    ->prefix('peserta')
    ->name('peserta.')
    ->group(function () {

        // Dashboard peserta
	Route::get('/dashboard', fn() => view('peserta.dashboard'))->name('dashboard');

        // Melihat daftar lomba
	Route::get('/competitions', [PesertaCompetitionController::class, 'index'])
            ->name('competitions.index');

        // Melihat detail lomba
	Route::get('/competitions/{competition}', [PesertaCompetitionController::class, 'show'])
            ->name('competitions.show');

        // Mendaftar lomba
        Route::post('/competitions/{competition}/register', 
            [RegistrationController::class, 'store'])
            ->name('competitions.register');

        // Lihat daftar lomba yang sudah didaftarkan user
        Route::get('/registrations', 
            [RegistrationController::class, 'index'])
            ->name('registrations.index');

        // Detail pendaftaran + status dokumen
        Route::get('/registrations/{registration}', 
            [RegistrationController::class, 'show'])
            ->name('registrations.show');

        // Upload dokumen lomba
        Route::post('/registrations/{registration}/upload', 
            [RegistrationController::class, 'uploadDocuments'])
            ->name('registrations.upload');
    });

Route::middleware('auth')->group(function () {
    Route::view('/profile', 'profile.edit')->name('profile.edit');
});
