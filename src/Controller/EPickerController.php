<?php

namespace App\Controller;

use App\Modules\EPicker\EPickerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EPickerController extends AbstractController
{
    #[Route('/api/epicker', name: 'app_e_picker')]
    public function index(Request $request, EPickerService $service): JsonResponse
    {
        $item = array_pop(json_decode($request->getContent(),true)['items']);
        $name = $item['name'];
        $description = $item['description'];
        $additional = $item['additional'];
        if (isset($name)) {
            $service->saveMessage($name, $description, $additional);
        }
        $stub['items'] = array();
        return $this->json($stub);
    }
}
