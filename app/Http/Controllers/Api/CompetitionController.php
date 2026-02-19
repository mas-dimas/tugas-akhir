<?php

namespace App\Http\Controllers\Api;

use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CompetitionController
{
    /**
     * Get all competitions with pagination (public)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $competitions = Competition::paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'List of competitions',
            'data' => $competitions->items(),
            'pagination' => [
                'total' => $competitions->total(),
                'per_page' => $competitions->perPage(),
                'current_page' => $competitions->currentPage(),
                'last_page' => $competitions->lastPage(),
            ],
        ], 200);
    }

    /**
     * Get single competition detail (public)
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $competition = Competition::with('registrations')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Competition detail',
                'data' => [
                    'id' => $competition->id,
                    'title' => $competition->title,
                    'description' => $competition->description,
                    'poster_url' => $competition->poster_path ? url('storage/' . $competition->poster_path) : null,
                    'registration_count' => $competition->registrations->count(),
                    'created_at' => $competition->created_at,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Competition not found',
            ], 404);
        }
    }

    /**
     * Create new competition (Admin only)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $competition = new Competition();
            $competition->title = $validated['title'];
            $competition->description = $validated['description'];

            // Handle poster upload
            if ($request->hasFile('poster')) {
                $posterPath = $request->file('poster')->store('competitions/posters', 'public');
                $competition->poster_path = $posterPath;
            }

            $competition->save();

            return response()->json([
                'success' => true,
                'message' => 'Competition created successfully',
                'data' => [
                    'id' => $competition->id,
                    'title' => $competition->title,
                    'description' => $competition->description,
                    'poster_url' => $competition->poster_path ? url('storage/' . $competition->poster_path) : null,
                ],
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Update competition (Admin only)
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $competition = Competition::findOrFail($id);

            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->has('title')) {
                $competition->title = $validated['title'];
            }
            if ($request->has('description')) {
                $competition->description = $validated['description'];
            }

            // Handle poster upload
            if ($request->hasFile('poster')) {
                $posterPath = $request->file('poster')->store('competitions/posters', 'public');
                $competition->poster_path = $posterPath;
            }

            $competition->save();

            return response()->json([
                'success' => true,
                'message' => 'Competition updated successfully',
                'data' => [
                    'id' => $competition->id,
                    'title' => $competition->title,
                    'description' => $competition->description,
                    'poster_url' => $competition->poster_path ? url('storage/' . $competition->poster_path) : null,
                ],
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Competition not found',
            ], 404);
        }
    }

    /**
     * Delete competition (Admin only)
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $competition = Competition::findOrFail($id);
            $competition->delete();

            return response()->json([
                'success' => true,
                'message' => 'Competition deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Competition not found',
            ], 404);
        }
    }
}
