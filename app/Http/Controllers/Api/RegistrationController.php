<?php

namespace App\Http\Controllers\Api;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RegistrationController
{
    /**
     * Get current user's registrations
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $registrations = Registration::with('competition')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Your registrations',
            'data' => $registrations->map(function ($reg) {
                return [
                    'id' => $reg->id,
                    'competition_id' => $reg->competition_id,
                    'user_id' => $reg->user_id,
                    'status' => $reg->status,
                    'competition' => [
                        'id' => $reg->competition->id,
                        'title' => $reg->competition->title,
                    ],
                    'created_at' => $reg->created_at,
                ];
            }),
        ], 200);
    }

    /**
     * Get registration detail
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $registration = Registration::with(['user', 'competition'])
                ->findOrFail($id);

            // Check authorization - user can only view their own registrations (unless admin)
            if ($registration->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'Registration detail',
                'data' => [
                    'id' => $registration->id,
                    'user' => [
                        'id' => $registration->user->id,
                        'name' => $registration->user->name,
                        'email' => $registration->user->email,
                    ],
                    'competition' => [
                        'id' => $registration->competition->id,
                        'title' => $registration->competition->title,
                        'description' => $registration->competition->description,
                    ],
                    'status' => $registration->status,
                    'created_at' => $registration->created_at,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration not found',
            ], 404);
        }
    }

    /**
     * Create new registration (Register to competition)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'competition_id' => 'required|exists:competitions,id',
            ]);

            // Check if already registered
            $existingRegistration = Registration::where('user_id', $request->user()->id)
                ->where('competition_id', $validated['competition_id'])
                ->first();

            if ($existingRegistration) {
                return response()->json([
                    'success' => false,
                    'error' => 'ALREADY_REGISTERED',
                    'message' => 'You already registered to this competition',
                ], 409);
            }

            $registration = Registration::create([
                'user_id' => $request->user()->id,
                'competition_id' => $validated['competition_id'],
                'status' => 'submitted',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Successfully registered to competition',
                'data' => [
                    'id' => $registration->id,
                    'competition_id' => $registration->competition_id,
                    'status' => $registration->status,
                    'created_at' => $registration->created_at,
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
     * Cancel registration
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $registration = Registration::findOrFail($id);

            // Check authorization
            if ($registration->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            $registration->delete();

            return response()->json([
                'success' => true,
                'message' => 'Registration cancelled',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration not found',
            ], 404);
        }
    }

    /**
     * Get all registrations (Admin only)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $competitionId = $request->get('competition_id');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 15);

        $query = Registration::with(['user', 'competition']);

        if ($competitionId) {
            $query->where('competition_id', $competitionId);
        }

        $registrations = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'All registrations',
            'data' => $registrations->items(),
            'pagination' => [
                'total' => $registrations->total(),
                'per_page' => $registrations->perPage(),
                'current_page' => $registrations->currentPage(),
                'last_page' => $registrations->lastPage(),
            ],
        ], 200);
    }
}
