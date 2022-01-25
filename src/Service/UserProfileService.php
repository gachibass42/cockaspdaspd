<?php

namespace App\Service;

use App\Model\UserProfile;
use App\Model\UserProfileResponse;
use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserProfileService
{

    public function __construct(private UserRepository $userRepository)
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
            $profile->getIsGuide()
        );
        return new UserProfileResponse($items);
    }

    public function updateUserProfile(string $data)//:UserProfileResponse
    {


        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $response = new UserProfileResponse(null);
        $serializer->deserialize(
            $data,
            UserProfileResponse::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE=>$response]
        );

        if (isset($response) && $response != null){
            $userItem = array_pop($response->items);
        }
        $profile = $this->userRepository->findOneBy(['id'=>$userItem['id']]); //TODO: get user by token
        if (isset($userItem) && $profile != null){
            $profile->setName($userItem['name']);
            $profile->setIsGuide($userItem['isGuide']);
            $profile->setDescription($userItem['userDescription']);
            $profile->setPhone($userItem['phone']);
        }

        $this->userRepository->updateUser($profile);
        //return $this->getUserProfile($user);
    }
}