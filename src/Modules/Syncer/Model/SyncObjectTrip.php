<?php

namespace App\Modules\Syncer\Model;

use App\Entity\TripUserRole;

class SyncObjectTrip
{
    public string $objID;
    public int $syncStatusDateTime;

    public ?string $name;
    public ?string $ownerID;
    public int $startDate;
    public int $endDate;
    public bool $locked;
    public ?int $duration;
    public ?string $tripDescription;
    public ?string $tags;
    public ?array $users;
    public ?string $mainImage;
    public ?array $milestonesIDs;
    public bool $visibility;
    public ?array $checkListsIDs;
    public ?string $cost;
    public ?string $costDescription;

    /**
     * @param string $objID
     * @param int $syncStatusDateTime
     * @param string|null $name
     * @param string|null $ownerID
     * @param int $startDate
     * @param int $endDate
     * @param bool $locked
     * @param int|null $duration
     * @param string|null $tripDescription
     * @param string|null $tags
     * @param TripUserRole[]|null $users
     * @param string|null $mainImage
     * @param array|null $milestonesIDs
     * @param bool $visibility
     * @param array|null $checkListsIDs
     */
    public function __construct(string $objID, int $syncStatusDateTime, ?string $name, ?string $ownerID, int $startDate, int $endDate, bool $locked, ?int $duration, ?string $tripDescription, ?string $tags, ?array
    $users, ?string $mainImage, ?array $milestonesIDs, bool $visibility, ?array $checkListsIDs, ?string $cost, ?string $costDescription)
    {
        $this->objID = $objID;
        $this->syncStatusDateTime = $syncStatusDateTime;
        $this->name = $name;
        $this->ownerID = $ownerID;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->locked = $locked;
        $this->duration = $duration;
        $this->tripDescription = $tripDescription;
        $this->tags = $tags;
        $this->users = array_map(fn (TripUserRole $userRole) => new SyncObjectTripUserRole($userRole->getTripUser()->getId(),$userRole->getRoleName()),//[$userRole->getTripUser()->getId() => $userRole->getRoleName()],
            $users);
        $this->mainImage = $mainImage;
        $this->milestonesIDs = $milestonesIDs;
        $this->visibility = $visibility;
        $this->checkListsIDs = $checkListsIDs;
        $this->cost = $cost;
        $this->costDescription = $costDescription;
    }

}