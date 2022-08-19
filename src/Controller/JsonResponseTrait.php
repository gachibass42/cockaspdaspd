<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait JsonResponseTrait
{
    protected function errorResponse(?string $error = null, $data = [], $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return new JsonResponse([
            'items' => ($error !== null ? ['error' => $error] : []) + [
                'data' => $data,
            ]
        ], $statusCode);
    }

    protected function successResponse(array $dataArray = []/*array $data = []*/): JsonResponse
    {
        $response = new JsonResponse(['items' => $dataArray], Response::HTTP_OK);
        $responseHeaders = [
            'Content-Length' => mb_strlen($response->getContent()),
        ];

        $response->headers->add($responseHeaders);

        return $response;
    }

    protected function getDataFromRequest(Request $request): ?array
    {
        try {
            return $request->isMethod(Request::METHOD_POST) || $request->isMethod(Request::METHOD_PUT) || $request->isMethod(Request::METHOD_DELETE) ? json_decode($request->getContent(), true, 512, \JSON_THROW_ON_ERROR) : $request->query->all();
        } catch (\Exception) {
        }

        return [];
    }

    protected function getPaginationStructure(int $currentPage, int $size, ?int $total = null): array
    {
        return [
            'currentPage' => $currentPage,
            'size' => $size,
            'total' => $total,
        ];
    }
}
