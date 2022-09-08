<?php

namespace App\Modules\TripsList\Model;

class ShortMilestone
{
    public string $objID;
    public string $locationName;
    public string $locationObjID;
    public int $reviewsNumber;
    public int $date;
    public string $type;

    /**
     * @param string $objID
     * @param string $locationName
     * @param int $reviewsNumber
     * @param int $date
     * @param string $type
     * @param string $locationObjID
     */
    public function __construct(string $objID, string $locationName, int $reviewsNumber, int $date, string $type, string $locationObjID)
    {
        $this->objID = $objID;
        $this->locationName = $locationName;
        $this->reviewsNumber = $reviewsNumber;
        $this->date = $date;
        $this->type = $type;
        $this->locationObjID = $locationObjID;
    }


    /**
     * @return string
     */
    public function getObjID(): string
    {
        return $this->objID;
    }

    /**
     * @param string $objID
     */
    public function setObjID(string $objID): void
    {
        $this->objID = $objID;
    }

    /**
     * @return string
     */
    public function getLocationName(): string
    {
        return $this->locationName;
    }

    /**
     * @param string $locationName
     */
    public function setLocationName(string $locationName): void
    {
        $this->locationName = $locationName;
    }

    /**
     * @return int
     */
    public function getReviewsNumber(): int
    {
        return $this->reviewsNumber;
    }

    /**
     * @param int $reviewsNumber
     */
    public function setReviewsNumber(int $reviewsNumber): void
    {
        $this->reviewsNumber = $reviewsNumber;
    }

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->date;
    }

    /**
     * @param int $date
     */
    public function setDate(int $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getLocationObjID(): string
    {
        return $this->locationObjID;
    }

    /**
     * @param string $locationObjID
     */
    public function setLocationObjID(string $locationObjID): void
    {
        $this->locationObjID = $locationObjID;
    }


}