<?php

namespace App\Controller\Api\V1;

use App\Controller\JsonResponseTrait;
use App\Modules\TripDetails\TripDetailsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TripDetailsController extends AbstractController
{
    use JsonResponseTrait;

    #[Route('/trip/details', name: 'api_v1_trip_details')]
    public function index(Request $request, TripDetailsService $tripDetailsService, NormalizerInterface $normalizer): JsonResponse
    {
        $tripID = $request->query->get('id');
        $data = $normalizer->normalize($tripDetailsService->getTripDetailsByObjectID($tripID), 'json');

        return $this->successResponse($data);
    }
}
