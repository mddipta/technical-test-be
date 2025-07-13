<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    /**
     * Build a success response.
     *
     * @param  mixed  $data
     * @param  string|null  $message
     * @param  int  $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($data, ?string $message = null, int $code = Response::HTTP_OK): JsonResponse
    {
        if ($data instanceof LengthAwarePaginator) {
            return $this->successWithPagination($data, $message, $code);
        }

        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];

        return response()->json($response, $code);
    }

    /**
     * Build a success response with pagination metadata separated.
     *
     * @param  \Illuminate\Pagination\LengthAwarePaginator  $paginator
     * @param  string|null  $message
     * @param  int  $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successWithPagination(LengthAwarePaginator $paginator, ?string $message = null, int $code = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $paginator->items(), // Only the actual data items
            'meta'    => [
                'current_page'   => $paginator->currentPage(),
                'first_page_url' => $paginator->url(1),
                'from'           => $paginator->firstItem(),
                'last_page'      => $paginator->lastPage(),
                'last_page_url'  => $paginator->url($paginator->lastPage()),
                'next_page_url'  => $paginator->nextPageUrl(),
                'path'           => $paginator->path(),
                'per_page'       => $paginator->perPage(),
                'prev_page_url'  => $paginator->previousPageUrl(),
                'to'             => $paginator->lastItem(),
                'total'          => $paginator->total(),
            ],
            'links'   => $paginator->linkCollection()->toArray(),
        ];

        return response()->json($response, $code);
    }

    /**
     * Build a success response with paginated resource collection.
     *
     * @param  \Illuminate\Pagination\LengthAwarePaginator  $paginator
     * @param  string  $resourceClass
     * @param  string|null  $message
     * @param  int  $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successWithPaginatedResource(LengthAwarePaginator $paginator, string $resourceClass, ?string $message = null, int $code = Response::HTTP_OK): JsonResponse
    {
        $paginator->getCollection()->transform(function ($item) use ($resourceClass) {
            return (new $resourceClass($item))->toArray(request());
        });

        return $this->successWithPagination($paginator, $message, $code);
    }

    /**
     * Build an error response.
     *
     * @param  string|null  $message
     * @param  int  $code
     * @param  mixed|null  $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error(?string $message = null, int $code, $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }
}
