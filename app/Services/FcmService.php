<?php

namespace App\Services;

use App\Models\DeviceToken;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\Messaging\NotFound;

class FcmService
{
    public function __construct(protected Messaging $messaging) {}

    public function sendToToken(string $token, string $title, string $body, array $data = []): bool
    {
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body))
            ->withData($this->stringifyData($data));

        try {
            $this->messaging->send($message);
            return true;
        } catch (NotFound $e) {
            // Token is invalid/expired — clean it up
            DeviceToken::where('token', $token)->delete();
            return false;
        } catch (\Throwable $e) {
            Log::error('FCM send failed', ['error' => $e->getMessage(), 'token' => $token]);
            return false;
        }
    }

    public function sendToMany(array $tokens, string $title, string $body, array $data = []): array
    {
        if (empty($tokens)) {
            return ['success' => 0, 'failure' => 0];
        }

        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData($this->stringifyData($data));

        $report = $this->messaging->sendMulticast($message, $tokens);

        // Clean up invalid tokens
        $invalidTokens = $report->invalidTokens();
        $unknownTokens = $report->unknownTokens();
        $tokensToDelete = array_merge($invalidTokens, $unknownTokens);

        if (!empty($tokensToDelete)) {
            DeviceToken::whereIn('token', $tokensToDelete)->delete();
        }

        return [
            'success' => $report->successes()->count(),
            'failure' => $report->failures()->count(),
        ];
    }

    /**
     * FCM data payload requires all values to be strings.
     */
    protected function stringifyData(array $data): array
    {
        return array_map(fn($value) => is_string($value) ? $value : json_encode($value), $data);
    }
}
