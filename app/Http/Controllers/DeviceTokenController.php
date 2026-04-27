<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string|max:500',
            'platform' => 'nullable|string|in:web,ios,android',
        ]);

        $deviceToken = DeviceToken::updateOrCreate(
            ['token' => $validated['token']],
            [
                'user_id' => $request->user()->id,
                'platform' => $validated['platform'] ?? 'web',
            ]
        );

        return response()->json(['message' => 'Token registered', 'id' => $deviceToken->id]);
    }

    public function destroy(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        DeviceToken::where('token', $request->token)
            ->where('user_id', $request->user()->id)
            ->delete();

        return response()->json(['message' => 'Token removed']);
    }
}
