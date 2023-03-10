<?php

namespace App\Modules\TripDetails;

use App\Entity\Milestone;
use App\Entity\TripUserRole;
use App\Helpers\FileManager\FileManagerService;
use App\Helpers\FileManager\ImagesManager;
use App\Modules\TripDetails\Model\MilestoneDetailsObject;
use App\Modules\TripDetails\Model\TripDetailsObject;
use App\Modules\TripDetails\Model\TripDetailsOwner;
use App\Modules\TripDetails\Model\TripDetailsUserRole;
use App\Repository\CommentRepository;
use App\Repository\MilestoneRepository;
use App\Repository\TripRepository;

class TripDetailsService
{

    public function __construct(private TripRepository $tripRepository,
                                private MilestoneRepository $milestoneRepository,
                                private FileManagerService $fileManagerService,
                                private ImagesManager $imagesManager,
                                private CommentRepository $commentRepository
    )
    {
    }

    private function getMilestoneDetailsObject(Milestone $milestone, bool $isLinked = false): MilestoneDetailsObject {
        return new MilestoneDetailsObject(
            $milestone->getObjId(),
            $milestone->getName(),
            $milestone->getCarrier()?->getObjID(),
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
            $milestone->getMealTimetables(),
            $this->commentRepository->getReviewsNumberForObject($milestone->getObjId())
        );
    }


    /**
     * @param string $tripID
     * @return TripDetailsObject[]
     */
    public function getTripDetailsByObjectID (string $tripID): array
    {
        $trip = $this->tripRepository->findOneBy(['objID'=>$tripID]);
        $milestones = $this->milestoneRepository->getMilestones($trip->getMilestonesIDs());
        $reviews = $this->commentRepository->getReviewsForObjects(array_map(fn (Milestone $m) => $m->getObjId(), $milestones));
        $imagesNumber = 0;
        $goodTagsNumber = 0;
        $badTagsNumber = 0;
        $attentionTagsNumber = 0;
        $reviewsNumber = count($reviews);
        foreach ($reviews as $review) {
            $tags = $review->getTags();
            $images = $review->getImages();
            foreach ($tags as $tag) {
                switch ($tag){
                    case 'good':
                        $goodTagsNumber++;
                        break;
                    case 'attention':
                        $attentionTagsNumber++;
                        break;
                    case 'bad':
                        $badTagsNumber++;
                        break;
                }
            }
            $imagesNumber += isset($images) ? count($images) : 0;
        }

        $tripObject = new TripDetailsObject(
            $trip->getObjId(),
            $trip->getName(),
            //$trip->getOwner()->getId(),
            new TripDetailsOwner(
                $trip->getOwner()->getId(),
                $trip->getOwner()->getName(),
                base64_encode($this->imagesManager->getThumbnailDataForImage($trip->getOwner()->getAvatar()))
            ),
            $trip->getStartDate()->getTimestamp(),
            $trip->getEndDate()->getTimestamp(),
            $trip->getDuration(),
            $trip->getTripDescription(),
            $trip->getTags(),
            array_map(fn (TripUserRole $userRole) => new TripDetailsUserRole($userRole->getTripUser()->getId(),$userRole->getRoleName()),
                $trip->getUsersRoles()->getValues()),
            $trip->getMainImage() ? base64_encode($this->fileManagerService->getImageContent($trip->getMainImage())) : null,
            array_map([$this,'getMilestoneDetailsObject'], $milestones),
            $trip->getCost(),
            $trip->getCostDescription(),
            $imagesNumber,
            $reviewsNumber,
            $goodTagsNumber,
            $badTagsNumber,
            $attentionTagsNumber
        );
        return [$tripObject];
    }
}