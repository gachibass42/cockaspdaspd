<?php

namespace App\Modules\TripDetails\Model;

class TripDetailsObject
{
    public string $objID;
    public ?string $name;
    public ?string $ownerID;
    public int $startDate;
    public int $endDate;
    public ?int $duration;
    public ?string $tripDescription;
    public ?string $tags;
    public ?array $users;
    public ?string $mainImage;
    public ?array $milestones;
    public ?string $cost;
    public ?string $costDescription;

    /**
     * @param string $objID
     * @param string|null $name
     * @param string|null $ownerID
     * @param int $startDate
     * @param int $endDate
     * @param int|null $duration
     * @param string|null $tripDescription
     * @param string|null $tags
     * @param array|null $users
     * @param string|null $mainImage
     * @param MilestoneDetailsObject[]|null $milestones
     * @param string|null $cost
     * @param string|null $costDescription
     */
    public function __construct(string $objID, ?string $name, ?string $ownerID, int $startDate, int $endDate, ?int $duration, ?string $tripDescription, ?string $tags, ?array $users, ?string $mainImage, ?array $milestones, ?string $cost, ?string $costDescription)
    {
        $this->objID = $objID;
        $this->name = $name;
        $this->ownerID = $ownerID;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->duration = $duration;
        $this->tripDescription = $tripDescription;
        $this->tags = $tags;
        $this->users = $users;
        $this->mainImage = $mainImage;
        $this->milestones = $milestones;
        $this->cost = $cost;
        $this->costDescription = $costDescription;
    }


}