<?php

namespace App\Http\DTO;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Responsable;

class ApiResponseData implements Responsable
{
    public function __construct(
        public readonly bool    $success,
        public readonly string  $message,
        public readonly ?array  $data   = null,
        public readonly ?array  $errors = null,
        public readonly int     $code   = 200,
    ) {}

    public function toResponse($request): JsonResponse
    {
        $payload = $this->success
            ? ['success' => $this->success, 'message' => $this->message, 'data' => $this->sanitizedData()]
            : ['success' => $this->success, 'message' => $this->message, 'errors' => $this->errors];

        $response = response()->json($payload, $this->code);

        // If a refresh token is present, strip it from the body and set as HttpOnly cookie
        if ($this->success && isset($this->data['refresh_token'])) {
            $response->cookie(
                'refresh_token',            // cookie name
                $this->data['refresh_token'], // value
                60 * 24 * 30,                // expiry in minutes (30 days)
                '/',                        // path
                null,                       // domain (null = current)
                true,                       // secure (HTTPS only)
                true,                       // httpOnly (JS cannot access)
                false,                      // raw
                'Strict'                    // sameSite
            );
        }

        return $response;
    }

    /**
     * Strip refresh_token from the JSON body — it lives in the cookie only.
     */
    private function sanitizedData(): ?array
    {
        if (!$this->data) return null;

        return array_diff_key($this->data, ['refresh_token' => '']);
    }
}
