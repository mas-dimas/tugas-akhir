<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\SubmissionDocument;
use Illuminate\Http\Request;

class RegistrationReviewController extends Controller
{
    /**
     * Menampilkan semua peserta yang mendaftar ke lomba tertentu
     */
    public function index(Competition $competition)
    {
        $registrations = Registration::with(['user', 'submissionDocuments'])
            ->where('competition_id', $competition->id)
            ->latest()
            ->get();

        return view('admin.registrations.index', compact('competition', 'registrations'));
    }

    /**
     * Menampilkan detail pendaftaran peserta beserta dokumen-dokumennya
     */
    public function show(Registration $registration)
    {
        $registration->load(['user', 'competition', 'submissionDocuments']);
        
        return view('admin.registrations.show', compact('registration'));
    }

    /**
     * Update status review dokumen peserta
     */
    public function updateStatus(Request $request, SubmissionDocument $document)
    {
        $validated = $request->validate([
            'status_review' => 'required|in:belum_dikoreksi,terdapat_koreksi,tidak_ada_koreksi',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $document->update($validated);

        return redirect()->back()->with('success', 'Status dokumen berhasil diperbarui.');
    }
}
