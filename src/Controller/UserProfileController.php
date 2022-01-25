<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\FileManagerService;
use App\Service\UserProfileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('api/user/profile/update', name: 'user_profile_update')]
    public function userProfileUpdate(Request $request, FileManagerService $fileManagerService):Response
    {
        /*$user = new User();
        if ($request->files->count()>0){
            $image = $request->files->get('image')->getData();
        }


        if (isset($image))
        {
            $fileManagerService->avatarUpload($image);
        }
        */
        return $this->json($this->userProfileService->updateUserProfile($request->getContent()));
    }
}
