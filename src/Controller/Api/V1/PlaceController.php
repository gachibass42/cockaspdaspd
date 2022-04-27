<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Service\Geo\GooglePlacesApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends AbstractController
{
    #[Route('/place/search', name: 'api_v1_place_search')]
    public function search(Request $request, GooglePlacesApi $googlePlacesApi): JsonResponse
    {
        $text = $request->query->get('query');
        if (!$text) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Text is required');
        }

        return new JsonResponse($googlePlacesApi->getPlacesBySearchText($text));
    }
}
