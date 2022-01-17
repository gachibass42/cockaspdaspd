<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Trip;
use App\Model\UserTripsListItem;
use App\Model\UserTripsListResponse;
use App\Repository\TripRepository;

class UserTripsService
{

    public function __construct(private TripRepository $tripRepository)
    {
    }

    public function getUserTripsList(User $user): UserTripsListResponse
    {
        $trips = $this->tripRepository->findAll(); //TODO: get trips by id
        $items = array_map(
            fn (Trip $trip) => new UserTripsListItem(
                $trip->getId(), $trip->getName()/*, $trip->getDescription(), $trip->getStartDate(), $trip->getFinishDate()*/
            ),
            $trips
        );
        return new UserTripsListResponse($items);
    }
}