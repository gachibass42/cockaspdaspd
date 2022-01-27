<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileManagerController extends AbstractController
{
    #[Route('/api/image/{img}', name: 'get_image')]
    public function getImage($img): Response
    {
        $filename = $this->getParameter('kernel.project_dir').'/uploads/images/'.$img;
        print ($filename);
        if (file_exists($filename)){
            return new BinaryFileResponse($filename);
        } else {
            return new JsonResponse(null, 404);
        }
    }
}
