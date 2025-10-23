<?php

namespace App\Traits;

use App\Http\Enums\HttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

trait RestResponse
{
    /**
     * Réponse succès
     *
     * @param mixed $data
     * @param string $message
     * @param HttpStatus $statusCode
     * @return JsonResponse
     */
    public function successResponse($data = null, string $message = "Opération réussie", HttpStatus $statusCode = HttpStatus::OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
            'metadata' => [
                'dernièreModification' => now()->toIso8601String(),
                'version' => env('API_VERSION', 'v1')
            ],
            'traceId' => Str::uuid()
        ], $statusCode->value);
    }

    /**
     * Réponse erreur
     *
     * @param string $code
     * @param string $message
     * @param array|null $details
     * @param HttpStatus $statusCode
     * @return JsonResponse
     */
    public function errorResponse(string $code, string $message, ?array $details = null, HttpStatus $statusCode = HttpStatus::BAD_REQUEST): JsonResponse
    {
    return response()->json([
        'success' => false,
        'error' => [
            'code' => $code,
            'message' => $message,
            'details' => $details ?? new \stdClass(),
            'path' => request()->path(),
            'traceId' => Str::uuid()
        ],
        'metadata' => [
            'dernièreModification' => now()->format('Y-m-d\TH:i:s\Z'),
            'version' => env('API_VERSION', 'v1')
        ]
    ], $statusCode->value);
}

    /**
     * Réponse succès avec pagination
     *
     * @param LengthAwarePaginator $paginator
     * @param string $message
     * @param HttpStatus $statusCode
     * @return JsonResponse
     */
    public function paginatedSuccessResponse(LengthAwarePaginator $paginator, string $message = "Opération réussie", HttpStatus $statusCode = HttpStatus::OK): JsonResponse
    {
        $request = request();
        $baseUrl = $request->url();
        $queryParams = $request->query();

        $pagination = [
            'currentPage' => $paginator->currentPage(),
            'totalPages' => $paginator->lastPage(),
            'totalItems' => $paginator->total(),
            'itemsPerPage' => $paginator->perPage(),
            'hasNext' => $paginator->hasMorePages(),
            'hasPrevious' => $paginator->currentPage() > 1,
        ];

        $links = [
            'self' => $this->buildUrl($baseUrl, $queryParams, $paginator->currentPage(), $paginator->perPage()),
            'first' => $this->buildUrl($baseUrl, $queryParams, 1, $paginator->perPage()),
        ];

        if ($paginator->hasMorePages()) {
            $links['next'] = $this->buildUrl($baseUrl, $queryParams, $paginator->currentPage() + 1, $paginator->perPage());
        }

        if ($paginator->currentPage() > 1) {
            $links['previous'] = $this->buildUrl($baseUrl, $queryParams, $paginator->currentPage() - 1, $paginator->perPage());
        }

        $links['last'] = $this->buildUrl($baseUrl, $queryParams, $paginator->lastPage(), $paginator->perPage());

        return response()->json([
            'success' => true,
            'data' => $paginator->items(),
            'pagination' => $pagination,
            'links' => $links,
            'message' => $message,
            'metadata' => [
                'dernièreModification' => now()->toIso8601String(),
                'version' => env('API_VERSION', 'v1')
            ],
            'traceId' => Str::uuid()
        ], $statusCode->value);
    }

    /**
     * Construit une URL avec les paramètres de requête
     *
     * @param string $baseUrl
     * @param array $queryParams
     * @param int $page
     * @param int $limit
     * @return string
     */
    private function buildUrl(string $baseUrl, array $queryParams, int $page, int $limit): string
    {
        $params = array_merge($queryParams, ['page' => $page, 'limit' => $limit]);
        return $baseUrl . '?' . http_build_query($params);
    }
}
