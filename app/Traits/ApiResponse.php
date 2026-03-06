<?php

namespace App\Traits;

use App\Http\DTO\ApiResponseData;
use App\Http\Constants\HttpStatus;

trait ApiResponse
{
    protected function success(string $message, mixed  $data = null, int    $code = HttpStatus::OK): ApiResponseData
    {
        return new ApiResponseData(
            success: true,
            message: $message,
            data:    $data,
            code:    $code,
        );
    }

    protected function error(string  $message, mixed $errors = null, int $code = HttpStatus::BAD_REQUEST): ApiResponseData
    {
        return new ApiResponseData(
            success: false,
            message: $message,
            errors:  $errors,
            code:    $code,
        );
    }
}
