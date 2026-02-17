<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Standard Success Response
     */
    protected function success(string $message = 'Success', mixed $data = null): JsonResponse
    {
        $response = [
            'status'  => 'success',
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, 200);
    }

    /**
     * Standard Error Response
     */
    protected function error(string $message, mixed $errors = null, mixed $code = 500): JsonResponse
    {
        $code = (int) $code;
        $code = ($code >= 100 && $code < 600) ? $code : 500;

        $response = [
            'status'  => 'error',
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}