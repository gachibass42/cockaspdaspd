<?php

namespace App\Controller\Api\V1;

use App\Controller\JsonResponseTrait;
use App\Modules\Comments\CommentsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{
    use JsonResponseTrait;

    #[Route('/comments/linked', name: 'api_v1_linked')]
    public function linked(Request $request, CommentsService $commentsService): JsonResponse
    {
        $linkedObjID = $request->get('id');
        if (isset($linkedObjID)) {
            return $this->successResponse($commentsService->getCommentsForObject($linkedObjID));
        }

        return $this->successResponse([]);
    }

    #[Route('/comments/list', name: 'api_v1_list')]
    public function list(Request $request, CommentsService $commentsService): JsonResponse
    {
        $json = json_decode($request->getContent(),true);
        if (isset($json['items'])) {
            return $this->successResponse($commentsService->getCommentsForObjectsArray($json['items']));
        }
        return $this->successResponse([]);
    }
}
