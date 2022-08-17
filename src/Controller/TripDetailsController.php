<?php

namespace App\Controller;

use App\Modules\TripDetails\TripDetailsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TripDetailsController extends AbstractController
{
    #[Route('api/trip/details', name: 'app_trip_details')]
    public function index(Request $request, TripDetailsService $tripDetailsService): JsonResponse
    {
        $tripID = $request->query->get('id');
        return $this->json($tripDetailsService->getTripDetailsByObjectID($tripID));
    }
}
