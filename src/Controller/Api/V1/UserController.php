<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Service\User\Authentication;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/register', name: 'api_v1_user_register', methods: ['POST'])]
    public function register(Authentication $authentication): JsonResponse
    {
        $user = $authentication->register();

        return $this->json($user);
    }

    #[Route('/user/login', name: 'api_v1_user_login', methods: ['POST'])]
    public function login(Request $request, Authentication $authentication): JsonResponse
    {
        $data = $authentication->login(
            $request->request->get('username'),
            $request->request->get('password')
        );

        if ($data === null) {
            return $this->json([
                'error' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'apiToken' => $data['apiToken']
        ]);
    }
}
