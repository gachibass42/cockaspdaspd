<?php

namespace App\Controller\Api\V1;

use App\Controller\JsonResponseTrait;
use App\Modules\CarrierInfo\CarrierInfoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CarrierInfoController extends AbstractController
{
    use JsonResponseTrait;

    public function __construct(private CarrierInfoService $carrierInfoService)
    {
    }

    #[Route('/carrier/list', name: 'api_v1_carriers_list')]
    public function getCarriersList (Request $request): JsonResponse
    {
        return $this->successResponse($this->carrierInfoService->getCarriersList($request->query->get('type')));
    }

    #[Route('/carrier/sync', name: 'api_v1_carriers_sync')]
    public function loadCarriersList (Request $request): JsonResponse
    {
        $items = json_decode($request->getContent(), true);
        //dump ($items);
        $this->carrierInfoService->loadCarriers($items['items']);

        return $this->successResponse(['status' => 'OK']);
    }
}