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
        //$profile = $this->userRepository->findOneBy(['id'=>$user->getId()]);//TODO: get user by id
        $profile = $this->userRepository->findOneBy(['name'=>'Валентина']);
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
        $profile = $this->userRepository->findOneBy(['name'=>'Валентина']); //TODO: get user by id
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
        if (isset($userItem)){
            $profile->setName($userItem['name']);
            $profile->setIsGuide($userItem['isGuide']);
            $profile->setDescription($userItem['userDescription']);
            $profile->setPhone($userItem['phone']);
        }

        $this->userRepository->updateUser($profile);
        //return $this->getUserProfile($user);
    }
}