<?php

namespace App\Controller\Api\V1;

use App\Controller\JsonResponseTrait;
use App\Helpers\FileManager\FileManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileManagerController extends AbstractController
{
    use JsonResponseTrait;

    public function __construct(private FileManagerService $fileManagerService)
    {
    }

    #[Route('/image/{img}', name: 'api_v1_get_image')]
    public function getImage($img): Response
    {
        $filename = $this->fileManagerService->getAvatarImagesDir().$img;
        //print ($filename);
        if (file_exists($filename)){
            return new BinaryFileResponse($filename);
        } else {
            return $this->errorResponse(statusCode: Response::HTTP_NOT_FOUND);
        }
    }
}
