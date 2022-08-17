<?php

namespace App\Controller;

use App\Modules\Comments\CommentsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{
    #[Route('api/comments/linked', name: 'app_comments')]
    public function index(Request $request, CommentsService $commentsService): JsonResponse
    {
        $linkedObjID = $request->get('id');
        if (isset($linkedObjID)) {
            return $this->json($commentsService->getCommentsForObject($linkedObjID));
        }
        return $this->json(['items'=> []]);
    }
}
