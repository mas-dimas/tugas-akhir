<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\SubmissionDocument;
use Illuminate\Support\Facades\Storage;


class RegistrationController extends Controller
{
    public function index()
    {
        $registrations = Registration::with('competition')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('peserta.registrations.index', compact('registrations'));
    }

    public function store(Request $request, Competition $competition)
    {
        try {
            Registration::create([
                'user_id' => auth()->id(),
                'competition_id' => $competition->id,
                'status' => 'terdaftar',
            ]);
        } catch (QueryException $e) {
            // kemungkinan unique constraint (sudah pernah daftar)
            return redirect()
                ->route('peserta.competitions.show', $competition)
                ->with('error', 'Kamu sudah terdaftar pada lomba ini.');
        }

        return redirect()
            ->route('peserta.registrations.index')
            ->with('success', 'Berhasil mendaftar lomba!');
    }


public function show(Registration $registration)
{
    abort_unless($registration->user_id === auth()->id(), 403);

    $registration->load('competition', 'submissionDocuments');

    $docs = $registration->submissionDocuments->keyBy('type'); // proposal/rekomendasi

    return view('peserta.registrations.show', compact('registration', 'docs'));
}

public function uploadDocument(Request $request, Registration $registration)
{
    abort_unless($registration->user_id === auth()->id(), 403);

    $data = $request->validate([
        'type' => 'required|in:proposal,rekomendasi',
        'file' => 'required|mimes:pdf,doc,docx|max:5120',
    ]);

    $existing = SubmissionDocument::where('registration_id', $registration->id)
        ->where('type', $data['type'])
        ->first();

    if ($existing) {
        Storage::disk('public')->delete($existing->file_path);
    }

    $path = $request->file('file')->store("submissions/{$registration->id}", 'public');

    SubmissionDocument::updateOrCreate(
        ['registration_id' => $registration->id, 'type' => $data['type']],
        [
            'file_path' => $path,
            'status_review' => 'belum_dikoreksi',
            'admin_note' => null,
        ]
    );

    return redirect()
        ->route('peserta.registrations.show', $registration)
        ->with('success', 'Dokumen berhasil diupload.');
}


}
