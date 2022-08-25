<?php

namespace App\Controller\Api\V1;

use App\Controller\JsonResponseTrait;
use App\Entity\User;
use App\Modules\UserDestructor\UserDestructorService;
use App\Modules\UserProfile\UserProfileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserProfileController extends AbstractController
{
    use JsonResponseTrait;

    public function __construct(private UserProfileService $userProfileService)
    {
    }

    #[Route('/user/profile', name: 'api_v1_user_profile')]
    public function userProfile(Request $request, NormalizerInterface $normalizer): Response
    {
        /*$user = new User();
        $user->setId($request->query->getInt('id'));
        $user->setApiToken($this->getAuthToken($request));*/ //TODO: use another way to pass the token
        $userID = $request->query->getInt('id');
        $data = $normalizer->normalize($this->userProfileService->getUserProfile($this->getUser()->getUserIdentifier(), $userID), 'json');

        return $this->successResponse($data);
    }

    #[Route('/user/profile/recover', name: 'api_v1_user_profile_recover')]
    public function userProfileRecover(Request $request, NormalizerInterface $normalizer):Response
    {
        $phoneNumber = $request->query->get('phone');
        //dump($phoneNumber);
        if (isset($phoneNumber)){
            return $this->successResponse($this->userProfileService->recoverUser($phoneNumber));
        } else {
            return $this->errorResponse('Phone number is required');
        }
    }

    #[Route('/user/profile/check', name: 'api_v1_user_profile_check')]
    public function userProfileCheck(Request $request, NormalizerInterface $normalizer):Response
    {
        $phoneNumber = $request->query->get('phone');
        if (isset($phoneNumber)){
            return $this->successResponse($this->userProfileService->checkPhone($phoneNumber));
        } else {
            return $this->errorResponse('Phone number is required');
        }
    }

    #[Route('/user/profile/update', name: 'api_v1_user_profile_update')]
    public function userProfileUpdate(Request $request, NormalizerInterface $normalizer):Response
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
         //TODO: refactor auth and getting token
        $data = $normalizer->normalize($this->userProfileService->updateUserProfile($request->getContent(),$this->getUser()->getUserIdentifier()), 'json');

        return $this->successResponse($data);
    }

    #[Route('/user/profile/update/avatar', name: 'api_v1_user_profile_update_avatar')]
    public function userProfileUpdateAvatar(Request $request, NormalizerInterface $normalizer):Response
    {
        $data = $normalizer->normalize($this->userProfileService->updateUserProfile($request->getContent(),$this->getUser()->getUserIdentifier()), 'json');

        return $this->successResponse($data);
    }

    #[Route('/user/profile/delete', name: 'api_v1_user_delete')]
    public function deleteUser (Request $request, UserDestructorService $destructorService):Response
    {
        $data = $destructorService->deleteUser($this->getUser()->getUserIdentifier());

        return $this->successResponse([$data]);
    }
}
