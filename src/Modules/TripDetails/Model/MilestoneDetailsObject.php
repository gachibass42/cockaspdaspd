<?php

namespace App\Modules\TripDetails\Model;

use App\Entity\Milestone;

class MilestoneDetailsObject
{
    public ?string $objID;
    public ?string $name;
    public ?string $carrierID;
    public ?string $locationID;
    public ?string $organizationLocationID;
    public ?array $linkedMilestones;
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
    public ?array $images;
    public ?array $tags;
    public ?array $mealTimetables;

    /**
     * @param string|null $objID
     * @param string|null $name
     * @param string|null $carrierID
     * @param string|null $locationID
     * @param string|null $organizationLocationID
     * @param MilestoneDetailsObject[]|null $linkedMilestones
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
     * @param array|null $images
     * @param array|null $tags
     * @param array|null $mealTimetables
     */
    public function __construct(?string $objID, ?string $name, ?string $carrierID, ?string $locationID, ?string $organizationLocationID, ?array $linkedMilestones, ?bool $userEdited, ?int $date, ?string $type, ?string $milestoneDescription, ?int $order, ?string $journeyNumber, ?string $seat, ?string $vagon, ?string $classType, ?string $terminal, ?float $distance, ?string $aircraft, ?string $duration, ?string $address, ?string $website, ?string $phoneNumber, ?bool $isStartPoint, ?bool $isEndPoint, ?bool $inTransit, ?string $roomNumber, ?string $rentType, ?string $ownerID, ?array $images, ?array $tags, ?array $mealTimetables)
    {
        $this->objID = $objID;
        $this->name = $name;
        $this->carrierID = $carrierID;
        $this->locationID = $locationID;
        $this->organizationLocationID = $organizationLocationID;
        $this->type = $type;
        $this->linkedMilestones = $linkedMilestones;
        $this->userEdited = $userEdited;
        $this->date = $date;
        $this->seat = $seat;
        $this->milestoneDescription = $milestoneDescription;
        $this->order = $order;
        $this->journeyNumber = $journeyNumber;
        $this->aircraft = $aircraft;
        $this->vagon = $vagon;
        $this->classType = $classType;
        $this->terminal = $terminal;
        $this->distance = $distance;
        $this->isStartPoint = $isStartPoint;
        $this->duration = $duration;
        $this->address = $address;
        $this->website = $website;
        $this->phoneNumber = $phoneNumber;
        $this->isEndPoint = $isEndPoint;
        $this->inTransit = $inTransit;
        $this->roomNumber = $roomNumber;
        $this->rentType = $rentType;
        $this->ownerID = $ownerID;
        $this->images = $images;
        $this->tags = $tags;
        $this->mealTimetables = $mealTimetables;
    }
}