<?php

namespace App\Controller;

use App\Modules\Syncer\SyncerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SyncController extends AbstractController
{
    #[Route('api/sync', name: 'app_sync')]
    public function sync(Request $request, SyncerService $syncerService): JsonResponse
    {
        $json = json_decode($request->getContent(),true);
        //dump ($json);
        $syncerService->processRequestObjectsArray($json['items']);

        return $this->json($syncerService->getSyncResponse(),200,[],[]);
    }
}
