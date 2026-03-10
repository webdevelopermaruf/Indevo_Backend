<?php

namespace App\Traits;

use App\Http\DTO\ApiResponseData;
use App\Http\Constants\HttpStatus;

trait ApiResponse
{
    protected function success(string $message, array  $data = null, int $code = HttpStatus::OK): ApiResponseData
    {
        return new ApiResponseData(
            success: true,
            message: $message,
            data:    $data,
            errors:  null,
            code:    $code,
        );
    }

    protected function error(string  $message, array $errors = null, int $code = HttpStatus::BAD_REQUEST): ApiResponseData
    {
        return new ApiResponseData(
            success: false,
            message: $message,
            data:    null,
            errors:  $errors,
            code:    $code,
        );
    }
}
