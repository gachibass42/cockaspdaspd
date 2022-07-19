<?php

namespace App\Modules\Syncer\Model;

use App\Repository\TripRepository;

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
    public ?array $images;
    public ?array $milestonesIDs;
    public bool $visibility;

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
     * @param array|null $users
     * @param array|null $images
     * @param array|null $milestonesIDs
     * @param bool $visibility
     */
    public function __construct(string $objID, int $syncStatusDateTime, ?string $name, ?string $ownerID, int $startDate, int $endDate, bool $locked, ?int $duration, ?string $tripDescription, ?string $tags, ?array $users, ?array $images, ?array $milestonesIDs, bool $visibility)
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
        $this->users = $users;
        $this->images = $images;
        $this->milestonesIDs = $milestonesIDs;
        $this->visibility = $visibility;
    }


    function mapFromArray(array $object)
    {

    }
}