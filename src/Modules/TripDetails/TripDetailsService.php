<?php

namespace App\Modules\TripDetails;

use App\Entity\Milestone;
use App\Entity\TripUserRole;
use App\Helpers\FileManager\FileManagerService;
use App\Modules\TripDetails\Model\MilestoneDetailsObject;
use App\Modules\TripDetails\Model\TripDetailsObject;
use App\Modules\TripDetails\Model\TripDetailsResponse;
use App\Modules\TripDetails\Model\TripDetailsUserRole;
use App\Repository\MilestoneRepository;
use App\Repository\TripRepository;

class TripDetailsService
{

    public function __construct(private TripRepository $tripRepository, private MilestoneRepository $milestoneRepository, private FileManagerService $fileManagerService)
    {
    }

    private function getMilestoneDetailsObject(Milestone $milestone, bool $isLinked = false): MilestoneDetailsObject {
        return new MilestoneDetailsObject(
            $milestone->getObjId(),
            $milestone->getName(),
            $milestone->getCarrier(),
            $milestone->getLocationID(),
            $milestone->getOrganizationLocationID(),
            //$milestone->getLinkedMilestonesIDs(),
            !$isLinked ? array_map(fn (Milestone $linkedMilestone) => $this->getMilestoneDetailsObject($linkedMilestone, true), $this->milestoneRepository->getMilestones($milestone->getLinkedMilestonesIDs())) : null,
            $milestone->getUserEdited(),
            $milestone->getDate()->getTimestamp(),
            $milestone->getType(),
            $milestone->getMilestoneDescription(),
            $milestone->getMilestoneOrder(),
            $milestone->getJourneyNumber(),
            $milestone->getSeat(),
            $milestone->getVagon(),
            $milestone->getClassType(),
            $milestone->getTerminal(),
            $milestone->getDistance(),
            $milestone->getAircraft(),
            $milestone->getDuration(),
            $milestone->getAddress(),
            $milestone->getWebsite(),
            $milestone->getPhoneNumber(),
            $milestone->getIsStartPoint(),
            $milestone->getIsEndPoint(),
            $milestone->getInTransit(),
            $milestone->getRoomNumber(),
            $milestone->getRentType(),
            $milestone->getOwner()->getId(),
            $milestone->getImages(),
            $milestone->getTags(),
            $milestone->getMealTimetables()
        );
    }

    public function getTripDetailsByObjectID (string $tripID): TripDetailsResponse {
        $trip = $this->tripRepository->findOneBy(['objID'=>$tripID]);
        $tripObject = new TripDetailsObject(
            $trip->getObjId(),
            $trip->getName(),
            $trip->getOwner()->getId(),
            $trip->getStartDate()->getTimestamp(),
            $trip->getEndDate()->getTimestamp(),
            $trip->getDuration(),
            $trip->getTripDescription(),
            $trip->getTags(),
            array_map(fn (TripUserRole $userRole) => new TripDetailsUserRole($userRole->getTripUser()->getId(),$userRole->getRoleName()),
                $trip->getUsersRoles()->getValues()),
            $trip->getMainImage() ? base64_encode($this->fileManagerService->getImageContent($trip->getMainImage())) : null,
            array_map([$this,'getMilestoneDetailsObject'], $this->milestoneRepository->getMilestones($trip->getMilestonesIDs())),
            $trip->getCost(),
            $trip->getCostDescription()
        );
        return new TripDetailsResponse([$tripObject]);
    }
}