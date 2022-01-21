<?php

namespace App\Service;

use App\Model\UserProfile;
use App\Model\UserProfileResponse;
use App\Repository\UserRepository;
use App\Entity\User;

class UserProfileService
{

    public function __construct(private UserRepository $userRepository)
    {

    }

    public function getUserProfile(User $user):UserProfileResponse
    {

        //$profile = $this->userRepository->findOneBy(['id'=>$user->getId()]);//TODO: get user by id
        $profile = $this->userRepository->findOneBy(['name'=>'Валентина']);
        $items = [];
        array_push($items,new UserProfile(
            $profile->getId(),
            $profile->getName(),
            $profile->getPhone(),
            $profile->getDescription(),
            $profile->getIsGuide()
        ));

        return new UserProfileResponse($items);
    }
}