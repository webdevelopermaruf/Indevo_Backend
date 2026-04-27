<?php

use App\Models\ScheduledNotification;
use App\Services\FcmService;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function (FcmService $fcm) {
    $notifications = ScheduledNotification::query()
        ->whereNull('sent_at')
        ->where('send_at', '<=', now())
        ->with('user.deviceTokens')
        ->get();

    \Illuminate\Support\Facades\Log::info($notifications);

    foreach ($notifications as $notification) {
        $tokens = $notification->user
            ? $notification->user->deviceTokens->pluck('token')->toArray()
            : [];

        if (!empty($tokens)) {
            $fcm->sendToMany(
                $tokens,
                $notification->title,
                $notification->body,
                $notification->data ?? []
            );
        }

        // Always mark as sent — even if no tokens, so we don't keep checking it
        $notification->update(['sent_at' => now()]);
    }
})->everyFiveSeconds()
    ->name('send-due-notifications')
    ->withoutOverlapping();
