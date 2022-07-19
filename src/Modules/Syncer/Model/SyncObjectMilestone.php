<?php

namespace App\Modules\Syncer\Model;

class SyncObjectMilestone
{
    public ?string $objID;
    public ?string $name;
    public int $syncStatusDateTime;
    public ?string $carrierID;
    public ?string $locationID;

    public ?array $linkedMilestonesIDs;
    public ?bool $userEdited;
    public ?int $date;
    public ?string $type;
    public ?string $milestoneDescription;
    public ?int $order;
    public ?string $journeyNumber;
    public ?string $seat;
    public ?string $vagon;
    public ?string $classType;
    public ?string $terminal;
    public ?float $distance;
    public ?string $aircraft;
    public ?string $duration;
    public ?string $address;
    public ?string $website;
    public ?string $phoneNumber;
    public ?bool $isStartPoint;
    public ?bool $isEndPoint;
    public ?bool $inTransit;
    public ?string $roomNumber;
    public ?string $rentType;
    public ?string $ownerID;
    public ?bool $visibility;
    public ?array $images;
    public ?array $tags;
    public ?array $mealTimetables;

    /**
     * @param string|null $objID
     * @param string|null $name
     * @param int $syncStatusDateTime
     * @param string|null $carrierID
     * @param string|null $locationID
     * @param array|null $linkedMilestonesIDs
     * @param bool|null $userEdited
     * @param int|null $date
     * @param string|null $type
     * @param string|null $milestoneDescription
     * @param int|null $order
     * @param string|null $journeyNumber
     * @param string|null $seat
     * @param string|null $vagon
     * @param string|null $classType
     * @param string|null $terminal
     * @param float|null $distance
     * @param string|null $aircraft
     * @param string|null $duration
     * @param string|null $address
     * @param string|null $website
     * @param string|null $phoneNumber
     * @param bool|null $isStartPoint
     * @param bool|null $isEndPoint
     * @param bool|null $inTransit
     * @param string|null $roomNumber
     * @param string|null $rentType
     * @param string|null $ownerID
     * @param bool|null $visibility
     * @param array|null $images
     * @param array|null $tags
     * @param array|null $mealTimetables
     */
    public function __construct(?string $objID, ?string $name, int $syncStatusDateTime, ?string $carrierID, ?string $locationID, ?array $linkedMilestonesIDs, ?bool $userEdited, ?int $date, ?string $type, ?string $milestoneDescription, ?int $order, ?string $journeyNumber, ?string $seat, ?string $vagon, ?string $classType, ?string $terminal, ?float $distance, ?string $aircraft, ?string $duration, ?string $address, ?string $website, ?string $phoneNumber, ?bool $isStartPoint, ?bool $isEndPoint, ?bool $inTransit, ?string $roomNumber, ?string $rentType, ?string $ownerID, ?bool $visibility, ?array $images, ?array $tags, ?array $mealTimetables)
    {
        $this->objID = $objID;
        $this->name = $name;
        $this->syncStatusDateTime = $syncStatusDateTime;
        $this->carrierID = $carrierID;
        $this->locationID = $locationID;
        $this->linkedMilestonesIDs = $linkedMilestonesIDs;
        $this->userEdited = $userEdited;
        $this->date = $date;
        $this->type = $type;
        $this->milestoneDescription = $milestoneDescription;
        $this->order = $order;
        $this->journeyNumber = $journeyNumber;
        $this->seat = $seat;
        $this->vagon = $vagon;
        $this->classType = $classType;
        $this->terminal = $terminal;
        $this->distance = $distance;
        $this->aircraft = $aircraft;
        $this->duration = $duration;
        $this->address = $address;
        $this->website = $website;
        $this->phoneNumber = $phoneNumber;
        $this->isStartPoint = $isStartPoint;
        $this->isEndPoint = $isEndPoint;
        $this->inTransit = $inTransit;
        $this->roomNumber = $roomNumber;
        $this->rentType = $rentType;
        $this->ownerID = $ownerID;
        $this->visibility = $visibility;
        $this->images = $images;
        $this->tags = $tags;
        $this->mealTimetables = $mealTimetables;
    }


}