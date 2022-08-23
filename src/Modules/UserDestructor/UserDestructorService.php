<?php

namespace App\Modules\UserDestructor;

use App\Modules\UserProfile\UserRepository;
use App\Repository\CheckListRepository;
use App\Repository\CommentRepository;
use App\Repository\LocationRepository;
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
        private LocationRepository $locationRepository,
        private CommentRepository $commentRepository
    )
    {
    }

    public function deleteUser(string $username): array{
        $profile = $this->userRepository->findOneBy(['username' => $username]);
        $id = $profile->getId();
        $this->tripUserRoleRepository->removeByUser($profile);

        $ownerTrips = $this->tripRepository->findBy(['owner'=>$profile]);
        foreach ($ownerTrips as $trip) {
            $roles = $this->tripUserRoleRepository->findBy(['trip'=>$trip]);
            if (count($roles) > 0) {
                $trip->setOwner($roles[0]->getTripUser());
                $this->milestoneRepository->updateMilestonesOwner($trip->getMilestonesIDs(),$roles[0]->getTripUser()->getId());
            } else {
                $this->milestoneRepository->removeMilestones($trip->getMilestonesIDs());
                $this->checkListRepository->removeCheckLists($trip->getCheckListsIDs());
                $this->tripRepository->remove($trip);
            }
        }
        $this->milestoneRepository->clearMilestonesForUser($id);
        $this->locationRepository->defaultLocationsOwner($id);
        $this->userRepository->remove($profile);
        return [
            'result' => 'completed'
        ];
    }
}