<?php

namespace App\Http\Controllers\Api;

use App\Models\SubmissionDocument;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionDocumentController
{
    /**
     * Get documents for a registration
     */
    public function index(Request $request, $registrationId)
    {
        $registration = Registration::where('user_id', $request->user()->id)
            ->findOrFail($registrationId);

        $documents = SubmissionDocument::with('documentTemplate')
            ->where('registration_id', $registrationId)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar dokumen',
            'data' => $documents,
        ]);
    }

    /**
     * Upload document
     */
    public function store(Request $request, $registrationId)
    {
        $registration = Registration::where('user_id', $request->user()->id)
            ->findOrFail($registrationId);

        $request->validate([
            'document_template_id' => 'required|exists:document_templates,id',
            'file' => 'required|mimes:pdf,doc,docx|max:10240',
        ]);

        // Check if document template belongs to this competition
        $template = $registration->competition->documentTemplates()
            ->findOrFail($request->document_template_id);

        $filePath = $request->file('file')->store('submissions', 'public');

        $document = SubmissionDocument::create([
            'registration_id' => $registrationId,
            'document_template_id' => $request->document_template_id,
            'file_path' => $filePath,
            'status' => 'submitted',
        ]);

        $document->load('documentTemplate');

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil diupload',
            'data' => $document,
        ], 201);
    }

    /**
     * Download document
     */
    public function download(Request $request, $documentId)
    {
        $document = SubmissionDocument::findOrFail($documentId);

        // Check authorization
        if ($document->registration->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses',
            ], 403);
        }

        return response()->download(Storage::disk('public')->path($document->file_path));
    }

    /**
     * Delete document
     */
    public function destroy(Request $request, $documentId)
    {
        $document = SubmissionDocument::findOrFail($documentId);

        // Check authorization
        if ($document->registration->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses',
            ], 403);
        }

        // Delete file from storage
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil dihapus',
        ]);
    }

    /**
     * Review document (Admin only)
     */
    public function review(Request $request, $documentId)
    {
        $request->validate([
            'status' => 'required|in:submitted,accepted,needs_revision',
            'reviewer_notes' => 'nullable|string',
        ]);

        $document = SubmissionDocument::findOrFail($documentId);
        $document->status = $request->status;
        if ($request->has('reviewer_notes')) {
            $document->reviewer_notes = $request->reviewer_notes;
        }
        $document->save();

        return response()->json([
            'success' => true,
            'message' => 'Review dokumen berhasil',
            'data' => $document,
        ]);
    }
}
