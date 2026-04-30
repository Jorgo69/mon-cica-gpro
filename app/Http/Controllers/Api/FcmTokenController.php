<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FcmTokenController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string|max:500',
            'device_name' => 'nullable|string|max:100',
            'platform' => 'nullable|string|in:web,android,ios,desktop',
        ]);

        $user = $request->user();

        // Upsert: if same token exists for this user, update it
        FcmToken::updateOrCreate(
            ['user_id' => $user->id, 'token' => $request->input('token')],
            [
                'device_name' => $request->input('device_name', 'Navigateur'),
                'platform' => $request->input('platform', 'web'),
            ]
        );

        return response()->json(['ok' => true]);
    }

    public function destroy(Request $request, string $tokenValue): JsonResponse
    {
        $request->user()->fcmTokens()
            ->where('token', $tokenValue)
            ->delete();

        return response()->json(['ok' => true]);
    }
}
