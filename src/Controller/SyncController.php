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
        $status = $request->query->get('status');
        $sessionTimestamp = $request->query->get('syncSessionDate');
        if (isset($status) && isset($sessionTimestamp)) {
            if ($status == "syncResponseSuccess") {
                $syncerService->commitSyncSession($this->getAuthToken($request),$sessionTimestamp);
            } elseif ($status == "syncResponseRejected") {
                $syncerService->failedSyncTry($this->getAuthToken($request),$sessionTimestamp);
            }

            return $this->json($syncerService->getSyncerResponse($request->query->get('sessionID')));
        }

        $json = json_decode($request->getContent(),true);
        //dump ($json);
        $syncerService->processRequestObjectsArray($json['items']);

        return $this->json($syncerService->getSyncResponse($this->getAuthToken($request)),200,[],[]);
    }

    private function getAuthToken(Request $request): ?string
    {
        if ($request->headers->get('auth') != null) {
            $auth = explode(" ",$request->headers->get('auth'));
            return $auth[1];
        }
        return null;
    }
}
