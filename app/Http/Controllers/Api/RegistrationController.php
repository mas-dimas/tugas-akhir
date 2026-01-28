<?php

namespace App\Http\Controllers\Api;

use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController
{
    /**
     * Get user's registrations
     */
    public function userRegistrations(Request $request)
    {
        $registrations = Registration::with(['competition', 'submissionDocuments'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pendaftaran Anda',
            'data' => $registrations,
        ]);
    }

    /**
     * Get registration detail
     */
    public function show(Request $request, $id)
    {
        $registration = Registration::with(['competition', 'submissionDocuments'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail pendaftaran',
            'data' => $registration,
        ]);
    }

    /**
     * Create new registration
     */
    public function store(Request $request)
    {
        $request->validate([
            'competition_id' => 'required|exists:competitions,id',
        ]);

        // Check if already registered
        $existingRegistration = Registration::where('user_id', $request->user()->id)
            ->where('competition_id', $request->competition_id)
            ->first();

        if ($existingRegistration) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah terdaftar untuk perlombaan ini',
            ], 422);
        }

        $registration = Registration::create([
            'user_id' => $request->user()->id,
            'competition_id' => $request->competition_id,
            'status' => 'pending',
        ]);

        $registration->load('competition');

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil',
            'data' => $registration,
        ], 201);
    }

    /**
     * Get all registrations (Admin only)
     */
    public function allRegistrations()
    {
        $registrations = Registration::with(['user', 'competition', 'submissionDocuments'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Semua pendaftaran',
            'data' => $registrations,
        ]);
    }

    /**
     * Update registration status (Admin only)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string',
        ]);

        $registration = Registration::findOrFail($id);
        $registration->status = $request->status;
        if ($request->has('notes')) {
            $registration->notes = $request->notes;
        }
        $registration->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pendaftaran berhasil diperbarui',
            'data' => $registration,
        ]);
    }
}
