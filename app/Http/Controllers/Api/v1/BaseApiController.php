<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseApiController extends Controller
{
    /**
     * Send a successful JSON response.
     */
    protected function successResponse(mixed $data = null, string $message = 'Success', int $code = 200, array $meta = []): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $code, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Get request payload safely from json body or form params.
     */
    protected function getPayload(\Illuminate\Http\Request $request): array
    {
        $all = $request->all();
        if (!empty($all)) {
            return $all;
        }
        $raw = json_decode((string)$request->getContent(), true);
        if (is_array($raw)) {
            $request->merge($raw);
            return $raw;
        }
        return [];
    }

    /**
     * Send an error JSON response.
     */
    protected function errorResponse(string $message = 'Error', int $code = 400, mixed $errors = null, ?string $errorCode = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errorCode) {
            $response['error_code'] = $errorCode;
        }

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code, [
            'Content-Type' => 'application/json',
        ]);
    }
}
