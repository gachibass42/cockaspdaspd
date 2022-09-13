<?php

namespace App\Modules\TripDetails\Model;

class TripDetailsObject
{
    public string $objID;
    public ?string $name;
    //public ?string $ownerID;
    public ?TripDetailsOwner $owner;
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
    public int $imagesNumber;
    public int $reviewsNumber;
    public int $goodTagsNumber;
    public int $badTagsNumber;
    public int $attentionTagsNumber;

    /**
     * @param string $objID
     * @param string|null $name
     * @param TripDetailsOwner|null $owner
     * @param int $startDate
     * @param int $endDate
     * @param int|null $duration
     * @param string|null $tripDescription
     * @param string|null $tags
     * @param array|null $users
     * @param string|null $mainImage
     * @param array|null $milestones
     * @param string|null $cost
     * @param string|null $costDescription
     * @param int $imagesNumber
     * @param int $reviewsNumber
     * @param int $goodTagsNumber
     * @param int $badTagsNumber
     * @param int $attentionTagsNumber
     */
    public function __construct(string $objID, ?string $name, ?TripDetailsOwner $owner, int $startDate, int $endDate, ?int $duration, ?string $tripDescription, ?string $tags, ?array $users, ?string $mainImage, ?array $milestones, ?string $cost, ?string $costDescription, int $imagesNumber, int $reviewsNumber, int $goodTagsNumber, int $badTagsNumber, int $attentionTagsNumber)
    {
        $this->objID = $objID;
        $this->name = $name;
        $this->owner = $owner;
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
        $this->imagesNumber = $imagesNumber;
        $this->reviewsNumber = $reviewsNumber;
        $this->goodTagsNumber = $goodTagsNumber;
        $this->badTagsNumber = $badTagsNumber;
        $this->attentionTagsNumber = $attentionTagsNumber;
    }
}