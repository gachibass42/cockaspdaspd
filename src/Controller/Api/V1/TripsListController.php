<?php

namespace App\Controller\Api\V1;

use App\Controller\JsonResponseTrait;
use App\Modules\TripsList\TripsListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TripsListController extends AbstractController
{
    use JsonResponseTrait;

    #[Route('/trips/list', name: 'api_v1_trips_list')]
    public function index(Request $request, TripsListService $tripsListService, NormalizerInterface $normalizer): JsonResponse
    {
        $locationID = $request->query->get('location');
        $userID = $request->query->get('user');
        $response['items'] = array();
        if (isset($locationID)) {
            $response = $tripsListService->getTripsListWithLocation ($locationID);
            $response = $normalizer->normalize($response, 'json');
        } elseif (isset($userID)) {
            $response = $tripsListService->getUserTripsList ($userID);
            $response = $normalizer->normalize($response, 'json');
        }

        return $this->successResponse($response);
    }
}
