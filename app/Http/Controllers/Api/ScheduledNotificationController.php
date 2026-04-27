<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ScheduledNotification;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Messaging;

class ScheduledNotificationController extends Controller
{

    public function test() {
        try {
            ScheduledNotification::create([
                'user_id' => 2,
                'title' => 'Scheduled test',
                'body' => 'This was scheduled 1 minutes ago!',
                'send_at' => now()->addMinutes(2),
            ]);

        } catch (\Throwable $e) {
            return 'Error: ' . $e->getMessage();
        }
    }



    public function index(Request $request)
    {
        $notifications = ScheduledNotification::where('user_id', $request->user()->id)
            ->orderBy('send_at', 'desc')
            ->paginate(20);

        return response()->json($notifications);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'body'    => 'required|string|max:1000',
            'data'    => 'nullable|array',
            'send_at' => 'required|date|after:now',
        ]);

        $notification = ScheduledNotification::create([
            'user_id' => $request->user()->id,
            'title'   => $validated['title'],
            'body'    => $validated['body'],
            'data'    => $validated['data'] ?? null,
            'send_at' => $validated['send_at'],
        ]);

        return response()->json([
            'message' => 'Notification scheduled',
            'data'    => $notification,
        ], 201);
    }

    public function destroy(Request $request, ScheduledNotification $scheduledNotification)
    {
        // Only the owner can delete, and only if not yet sent
        if ($scheduledNotification->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($scheduledNotification->sent_at) {
            return response()->json(['message' => 'Already sent, cannot cancel'], 422);
        }

        $scheduledNotification->delete();

        return response()->json(['message' => 'Notification cancelled']);
    }
}
