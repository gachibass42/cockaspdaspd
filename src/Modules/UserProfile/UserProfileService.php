<?php

namespace App\Modules\UserProfile;

use App\Entity\User;
use App\Helpers\FileManager\FileManagerService;
use App\Modules\UserProfile\Model\UserProfile;
use App\Modules\UserProfile\Model\UserProfileResponse;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

//use Symfony\Component\HttpFoundation\File\File;

class UserProfileService
{
    public string $kernelDir;
    public function __construct(private UserRepository $userRepository, private UrlHelper $urlHelper, private FileManagerService $fm)
    {

    }

    public function getUserProfile(User $user):UserProfileResponse
    {
        if ($user->getId() != null) {
            $profile = $this->userRepository->findOneBy(['id'=>$user->getId()]);
        } else {
            $profile = $this->userRepository->findOneBy(['apiToken'=>$user->getApiToken()]);
        }


        //$users = $this->userRepository->findAll();
        /*do {
            $profile = array_pop($users);//TODO: get user by id
        } while ($profile->getName() == null);*/

        $items[] = new UserProfile(
            (string)$profile->getId(),
            $profile->getName(),
            $profile->getPhone(),
            $profile->getDescription(),
            $profile->getIsGuide(),
            $this->urlHelper->getAbsoluteUrl('/api/image/'.$profile->getAvatar())  //TODO: move images directory path to global
        );
        return new UserProfileResponse($items);
    }

    public function updateUserProfile(string $data, string $token = null):UserProfileResponse
    {
        //serialize data from request
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $requestData = new UserProfileResponse(null);
        $serializer->deserialize(
            $data,
            UserProfileResponse::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE=>$requestData]
        );
        //Get single user from items array
        if (count($requestData->items) > 0){
            $userItem = array_pop($requestData->items);
        }
        //Get user from DB and update properties according to user requestData
        $profile = $this->userRepository->findOneBy(['apiToken'=>$token]); //TODO: get user by token, check auth
        if (isset($userItem) && $profile != null){
            $profile->setName($userItem['name']);
            $profile->setIsGuide($userItem['isGuide']);
            $profile->setDescription($userItem['userDescription']);
            $profile->setPhone($userItem['phone']);
            if (isset($userItem['avatar']) && $userItem['avatar'] != null){
                $filename = $this->fm->saveImage(base64_decode($userItem['avatar']), $userItem['avatarFileName']);
                if ($filename != null){
                    $profile->setAvatar($filename);
                }
            }
            $this->userRepository->updateUser($profile);
        } else {
            print 'User trouble';
        }
        $profile = $this->userRepository->findOneBy(['apiToken'=>$token]);
        return $this->getUserProfile($profile);
    }

    public function updateUserAvatar(string $data, string $token = null){

    }

}