<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserProfileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{

    public function __construct(private UserProfileService $userProfileService)
    {
    }

    #[Route('api/user/profile', name: 'user_profile')]
    public function userProfile(): Response
    {
        $user = new User();
        /*return $this->render('user_profile/index.html.twig', [
            'controller_name' => 'UserProfileController',
        ]);*/
        return $this->json($this->userProfileService->getUserProfile($user));
    }
}
