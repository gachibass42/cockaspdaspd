<?php

namespace App\Service;

use App\Model\UserProfile;
use App\Model\UserProfileResponse;
use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserProfileService
{

    public function __construct(private UserRepository $userRepository, private UrlHelper $urlHelper)
    {

    }

    public function getUserProfile(User $user):UserProfileResponse
    {
        //$profile = $this->userRepository->findOneBy(['id'=>$user->getId()]);
        $users = $this->userRepository->findAll();
        $profile = array_pop($users);//TODO: get user by id

        $items[] = new UserProfile(
            $profile->getId(),
            $profile->getName(),
            $profile->getPhone(),
            $profile->getDescription(),
            $profile->getIsGuide(),
            $this->urlHelper->getAbsoluteUrl('/api/image/'.$profile->getAvatar())  //TODO: move images directory path to global
        );
        return new UserProfileResponse($items);
    }

    public function updateUserProfile(string $data, string $token = null)//:UserProfileResponse
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $response = new UserProfileResponse(null);
        $serializer->deserialize(
            $data,
            UserProfileResponse::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE=>$response]
        );

        if (count($response->items) > 0){
            $userItem = array_pop($response->items);
        }

        $profile = $this->userRepository->findOneBy(['apiToken'=>$token]); //TODO: get user by token, check auth
        if (isset($userItem) && $profile != null){
            $profile->setName($userItem['name']);
            $profile->setIsGuide($userItem['isGuide']);
            $profile->setDescription($userItem['userDescription']);
            $profile->setPhone($userItem['phone']);
            $this->userRepository->updateUser($profile);
        } else {
            print 'User trouble';
        }

    }

    public function updateUserAvatar(string $data, string $token = null){

    }

}