<?php

namespace App\Controller\Api\V1;

use App\Controller\JsonResponseTrait;
use App\Modules\Syncer\SyncerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SyncController extends AbstractController
{
    use JsonResponseTrait;

    #[Route('/sync', name: 'api_v1_app_sync')]
    public function sync(Request $request, SyncerService $syncerService, NormalizerInterface $normalizer): JsonResponse
    {
        $status = $request->query->get('status');
        $sessionTimestamp = $request->query->get('syncSessionDate');
        if (isset($status) && isset($sessionTimestamp)) {
            if ($status == "syncResponseSuccess") {
                $syncerService->commitSyncSession($this->getUser()->getUserIdentifier(),$sessionTimestamp);
            } elseif ($status == "syncResponseRejected") {
                $syncerService->failedSyncTry($this->getUser()->getUserIdentifier(),$sessionTimestamp);
            }

            $data = $normalizer->normalize($syncerService->getSyncerResponse($request->query->get('sessionID')), 'json');
            return $this->successResponse($data);
        }

        $json = json_decode($request->getContent(),true);
        $syncerService->processRequestObjectsArray($json['items']);

        $data = $normalizer->normalize($syncerService->getSyncResponse($this->getUser()->getUserIdentifier()), 'json');
        return $this->successResponse($data);
    }
/*
    private function getAuthToken(Request $request): ?string
    {
        if ($request->headers->get('auth') != null) {
            $auth = explode(" ",$request->headers->get('auth'));
            return $auth[1];
        }
        return null;
    }*/
}
