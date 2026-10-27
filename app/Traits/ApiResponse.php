<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse(
        string $message,
        mixed $data = [],
        int $status = 200,
        array $errors = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data ?? [],
            'errors' => $errors,
        ], $status);
    }

    protected function errorResponse(
        string $message = 'Something went wrong',
        int $status = 500,
        mixed $data = [],
        array $errors = []
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data ?? [],
            'errors' => $errors,
        ], $status);
    }
}
