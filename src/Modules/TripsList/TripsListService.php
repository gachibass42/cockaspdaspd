<?php

namespace App\Modules\TripsList;

use App\Entity\Trip;
use App\Helpers\FileManager\FileManagerService;
use App\Helpers\FileManager\ImagesManager;
use App\Modules\TripsList\Model\ShortMilestone;
use App\Modules\TripsList\Model\TripsListItem;
use App\Modules\UserProfile\UserRepository;
use App\Repository\MilestoneRepository;
use App\Repository\TripRepository;

class TripsListService
{

    public function __construct(private TripRepository $tripRepository,
                                private MilestoneRepository $milestoneRepository,
                                private UserRepository $userRepository,
                                private ImagesManager $imagesManager)
    {
    }

    /**
     * @param Trip[]|null $trips
     * @return TripsListItem[]
     */
    private function mapTripsToResponse (?array $trips): array {
        $items = [];
        if (isset($trips)) {
            $shortMilestones = [];
            foreach ($trips as $trip) {
                $shortMilestones[$trip->getObjId()] = $this->milestoneRepository->getShortMilestones($trip->getMilestonesIDs());
            }
            $items = array_map(
                fn (Trip $trip) => new TripsListItem(
                    $trip->getObjId(),
                    $trip->getOwner()->getId(),
                    $trip->getStartDate()->getTimestamp(),
                    $trip->getEndDate()->getTimestamp(),
                    $trip->getTags(),
                    $trip->getMainImage() ? base64_encode($this->imagesManager->getThumbnailDataForImage($trip->getMainImage())) : null,
                    array_reduce($shortMilestones[$trip->getObjId()], fn (?int $carry, ShortMilestone $milestone) => $carry + $milestone->getReviewsNumber()),
                    //array_values(array_filter($shortMilestones[$trip->getObjId()], fn (ShortMilestone $milestone) => $milestone->getType() == 'Город')),
                    array_values($shortMilestones[$trip->getObjId()]),
                    $trip->getName()
                ),
                $trips);
            //dump($items);

        }
        return $items;
    }


    /**
     * @param string $locationID
     * @return TripsListItem[]
     */
    public function getTripsListWithLocation (string $locationID): array
    {
        $trips = $this->tripRepository->getTripsWithLocation($locationID);
        //dump($trips);
        return $this->mapTripsToResponse($trips);
    }

    /**
     * @param string $userID
     * @return TripsListItem[]
     */
    public function getUserTripsList (string $userID): array {
        $userID = (int)$userID;
        $trips = [];
        if ($userID > 0) {
            $trips = $this->tripRepository->getUserTrips($userID);
        }
        return $this->mapTripsToResponse($trips);
    }
}