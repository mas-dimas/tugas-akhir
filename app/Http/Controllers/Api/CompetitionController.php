<?php

namespace App\Http\Controllers\Api;

use App\Models\Competition;
use Illuminate\Http\Request;

class CompetitionController
{
    /**
     * Get all competitions (public)
     */
    public function index()
    {
        $competitions = Competition::with('documentTemplates')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar perlombaan',
            'data' => $competitions,
        ]);
    }

    /**
     * Get single competition detail
     */
    public function show($id)
    {
        $competition = Competition::with(['documentTemplates', 'registrations.submissionDocuments'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail perlombaan',
            'data' => $competition,
        ]);
    }

    /**
     * Create new competition (Admin only)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'nullable|string',
            'max_participants' => 'nullable|integer|min:1',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'guidebook' => 'nullable|mimes:pdf|max:10240',
        ]);

        $competition = new Competition();
        $competition->name = $request->name;
        $competition->description = $request->description;
        $competition->start_date = $request->start_date;
        $competition->end_date = $request->end_date;
        $competition->location = $request->location;
        $competition->max_participants = $request->max_participants;

        // Handle poster upload
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('competitions/posters', 'public');
            $competition->poster = $posterPath;
        }

        // Handle guidebook upload
        if ($request->hasFile('guidebook')) {
            $guidebookPath = $request->file('guidebook')->store('competitions/guidebooks', 'public');
            $competition->guidebook = $guidebookPath;
        }

        $competition->save();

        return response()->json([
            'success' => true,
            'message' => 'Perlombaan berhasil dibuat',
            'data' => $competition,
        ], 201);
    }

    /**
     * Update competition (Admin only)
     */
    public function update(Request $request, $id)
    {
        $competition = Competition::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after:start_date',
            'location' => 'nullable|string',
            'max_participants' => 'nullable|integer|min:1',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'guidebook' => 'nullable|mimes:pdf|max:10240',
        ]);

        if ($request->has('name')) $competition->name = $request->name;
        if ($request->has('description')) $competition->description = $request->description;
        if ($request->has('start_date')) $competition->start_date = $request->start_date;
        if ($request->has('end_date')) $competition->end_date = $request->end_date;
        if ($request->has('location')) $competition->location = $request->location;
        if ($request->has('max_participants')) $competition->max_participants = $request->max_participants;

        // Handle poster upload
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('competitions/posters', 'public');
            $competition->poster = $posterPath;
        }

        // Handle guidebook upload
        if ($request->hasFile('guidebook')) {
            $guidebookPath = $request->file('guidebook')->store('competitions/guidebooks', 'public');
            $competition->guidebook = $guidebookPath;
        }

        $competition->save();

        return response()->json([
            'success' => true,
            'message' => 'Perlombaan berhasil diperbarui',
            'data' => $competition,
        ]);
    }

    /**
     * Delete competition (Admin only)
     */
    public function destroy($id)
    {
        $competition = Competition::findOrFail($id);
        $competition->delete();

        return response()->json([
            'success' => true,
            'message' => 'Perlombaan berhasil dihapus',
        ]);
    }
}
