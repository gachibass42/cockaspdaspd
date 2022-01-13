<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlindController extends AbstractController
{
    #[Route('/', name: 'blind')]
    public function index(): Response
    {
        return $this->render('blind.html.twig');
        /*return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/BlindController.php',
        ]);*/
    }
}
