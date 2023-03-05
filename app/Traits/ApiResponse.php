<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Send success response for current request in json format
     *
     * @param array|mixed $data
     * @param $message
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse($data, $message = null, int $code = 200): JsonResponse
    {
        return response()->json($data, $code);
    }

    /**
     * Send error response for current request in json format
     *
     * @param $message
     * @param int $code
     * @return JsonResponse
     */
    protected function errorResponse($message = null, int $code): JsonResponse
    {
        return response()->json([
            'data'    => null,
            'message' => $message,
        ], $code);
    }
}
