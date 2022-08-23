<?php

namespace App\Modules\UserDestructor;

use App\Modules\UserProfile\UserRepository;
use App\Repository\CheckListRepository;
use App\Repository\CommentRepository;
use App\Repository\MilestoneRepository;
use App\Repository\TripRepository;
use App\Repository\TripUserRoleRepository;

class UserDestructorService
{

    public function __construct(
        private UserRepository $userRepository,
        private TripRepository $tripRepository,
        private TripUserRoleRepository $tripUserRoleRepository,
        private MilestoneRepository $milestoneRepository,
        private CheckListRepository $checkListRepository,
        private CommentRepository $commentRepository
    )
    {
    }

    public function deleteUser(string $username): array{
        $profile = $this->userRepository->findOneBy(['username' => $username]);

        $this->tripUserRoleRepository->removeByUser($profile);

        $ownerTrips = $this->tripRepository->findBy(['owner'=>$profile]);
        foreach ($ownerTrips as $trip) {
            $roles = $this->tripUserRoleRepository->findBy(['trip'=>$trip]);
            if (count($roles) > 0) {
                $trip->setOwner($roles[0]->getTripUser());
            } else {
                $this->milestoneRepository->removeMilestones($trip->getMilestonesIDs());
                $this->checkListRepository->removeCheckLists($trip->getCheckListsIDs());
                $this->tripRepository->remove($trip);
            }
        }
        $this->userRepository->remove($profile);
        return [
            'result' => 'completed'
        ];
    }
}