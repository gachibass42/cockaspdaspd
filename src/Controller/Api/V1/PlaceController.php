<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Service\Geo\LocationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PlaceController extends AbstractController
{
    #[Route('/place/search', name: 'api_v1_place_search')]
    public function search(Request $request, LocationManager $locationManager, NormalizerInterface $normalizer): JsonResponse
    {
        $text = $request->query->get('query');
        if (!$text) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Text is required');
        }

        $locations = $locationManager->getLocationsSearchText($text);
        $data = $normalizer->normalize($locations, 'json', ['groups' => 'location_details']);

        return new JsonResponse(['items' => $data]);
    }
}
