<?php

namespace App\Controller;

use App\Modules\TripsList\TripsListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TripsListController extends AbstractController
{
    #[Route('api/trips/list', name: 'app_trips_list')]
    public function index(Request $request, TripsListService $tripsListService): JsonResponse
    {
        $locationID = $request->query->get('location');
        $userID = $request->query->get('user');
        $response['items'] = array();
        if (isset($locationID)) {
            $response = $tripsListService->getTripsListWithLocation($locationID);
        }
        return $this->json($response);
    }
}
