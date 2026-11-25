<?php

namespace App\Traits;
use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    protected function successResponse($data = null, $message = 'Success', $code = 200): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function errorResponse($message = 'Error', $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }
}