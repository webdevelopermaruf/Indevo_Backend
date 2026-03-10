<?php

namespace App\Http\DTO;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Responsable;

class ApiResponseData implements Responsable
{
    public function __construct(
        public readonly bool        $success,
        public readonly string      $message,
        public readonly ?array  $data   = null,
        public readonly ?array  $errors = null,
        public readonly int         $code   = 200,
    ) {}

    public function toResponse($request): JsonResponse
    {
        return response()->json(
            $this->success
                ? ['success' => $this->success, 'message' => $this->message, 'data'   => $this->data]
                : ['success' => $this->success, 'message' => $this->message, 'errors' => $this->errors],
            $this->code
        );
    }
}
