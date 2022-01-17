<?php

namespace App\Service;

use App\Model\UserProfile;
use App\Repository\UserRepository;
use App\Entity\User;

class UserProfileService
{

    public function __construct(private UserRepository $userRepository)
    {

    }

    public function getUserProfile(User $user):UserProfile
    {

        //$profile = $this->userRepository->findOneBy(['id'=>$user->getId()]);//TODO: get user by id
        $profile = $this->userRepository->findOneBy(['name'=>'Валентина']);

        return new UserProfile(
            $profile->getId(),
            $profile->getName(),
            $profile->getPhone(),
            $profile->getDescription(),
            $profile->getIsGuide()
        );
    }
}