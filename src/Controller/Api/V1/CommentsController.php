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

    #[Route('/comments/linked', name: 'api_v1_comments')]
    public function index(Request $request, CommentsService $commentsService): JsonResponse
    {
        $linkedObjID = $request->get('id');
        if (isset($linkedObjID)) {
            return $this->successResponse(['comments' => $commentsService->getCommentsForObject($linkedObjID)]);
        }

        return $this->successResponse([]);
    }
}
