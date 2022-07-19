<?php

namespace App\Controller;

use App\Helpers\FileManager\FileManagerService;
use App\Modules\CarrierInfo\CarrierInfoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CarrierInfoController extends AbstractController
{

    public function __construct(private CarrierInfoService $carrierInfoService)
    {
    }

    #[Route('api/carrier/list', name: 'carriers_list')]
    public function getCarriersList (Request $request): JsonResponse
    {
        return $this->json($this->carrierInfoService->getCarriersList($request->query->get('type')));
    }

    #[Route('api/carrier/sync', name: 'carriers_sync')]
    public function loadCarriersList (Request $request): JsonResponse
    {
        $items = json_decode($request->getContent(), true);
        //dump ($items);
        $this->carrierInfoService->loadCarriers($items['items']);
        return $this->json("{\"status\": \"OK\"}");
    }
}