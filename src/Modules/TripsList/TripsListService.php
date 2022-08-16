<?php

namespace App\Modules\TripsList;

use App\Entity\Trip;
use App\Helpers\FileManager\FileManagerService;
use App\Modules\TripsList\Model\ShortMilestone;
use App\Modules\TripsList\Model\TripsListItem;
use App\Modules\TripsList\Model\TripsListResponse;
use App\Repository\CommentRepository;
use App\Repository\LocationRepository;
use App\Repository\MilestoneRepository;
use App\Repository\TripRepository;

class TripsListService
{

    public function __construct(private TripRepository $tripRepository,
                                private MilestoneRepository $milestoneRepository,
                                private FileManagerService $fileManagerService)
    {
    }

    /**
     * @param Trip[]|null $trips
     * @return TripsListResponse
     */
    private function mapTripsToResponse (?array $trips): TripsListResponse {
        $items = [];
        if (isset($trips)) {
            $shortMilestones = [];
            foreach ($trips as $trip) {
                $shortMilestones[$trip->getObjId()] = $this->milestoneRepository->getShortMilestones($trip->getMilestonesIDs());
                //dump($shortMilestones);
            }
            $items = array_map(
                fn (Trip $trip) => new TripsListItem(
                    $trip->getObjId(),
                    $trip->getOwner()->getId(),
                    $trip->getStartDate()->getTimestamp(),
                    $trip->getEndDate()->getTimestamp(),
                    $trip->getTags(),
                    $trip->getMainImage() ? base64_encode($this->fileManagerService->getImageContent($trip->getMainImage())) : null,
                    array_reduce($shortMilestones[$trip->getObjId()], fn (?int $carry, ShortMilestone $milestone) => $carry + $milestone->getReviewsNumber()),
                    array_values(array_filter($shortMilestones[$trip->getObjId()], fn (ShortMilestone $milestone) => $milestone->getType() == 'Город'))
                ),
                $trips);
            //dump($items);

        }
        return new TripsListResponse($items);
    }

    public function getTripsListWithLocation (string $locationID): TripsListResponse{

        $trips = $this->tripRepository->getTripsWithLocation($locationID);
        //dump($trips);
        return $this->mapTripsToResponse($trips);
    }

    /*public function getUserTripsList (string $userID): TripsListResponse {
        $id = (int)$userID;
        $trips = $this->tripRepository->getUserTrips($id);

        return $this->mapTripsToResponse($trips);
    }*/
}