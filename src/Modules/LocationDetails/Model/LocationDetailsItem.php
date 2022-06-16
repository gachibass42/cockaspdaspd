<?php

namespace App\Modules\LocationDetails\Model;

use App\Entity\Location;
use Symfony\Component\Serializer\Annotation\Groups;

class LocationDetailsItem
{
    private string $objID;
    private ?string $name;
    private ?float $latitude;
    private ?float $longtitude;
    private ?string $address;
    private ?string $timeZone;
    private ?string $codeIATA;
    private ?string $countryCode;
    private ?string $externalPlaceId;
    private ?string $searchTags;
    private ?Location $cityLocation;
    private ?Location $countryLocation;
    private ?string $type;

    /**
     * @param string $objID
     * @param string|null $name
     * @param float|null $latitude
     * @param float|null $longtitude
     * @param string|null $address
     * @param string|null $timeZone
     * @param string|null $codeIATA
     * @param string|null $countryCode
     * @param string|null $externalPlaceId
     * @param string|null $searchTags
     * @param Location|null $cityLocation
     * @param Location|null $countryLocation
     * @param string|null $type
     */
    public function __construct(string $objID, ?string $name, ?float $latitude, ?float $longtitude, ?string $address, ?string $timeZone, ?string $codeIATA, ?string $countryCode, ?string $externalPlaceId, ?string $searchTags, ?Location $cityLocation, ?Location $countryLocation, ?string $type)
    {
        $this->objID = $objID;
        $this->name = $name;
        $this->latitude = $latitude;
        $this->longtitude = $longtitude;
        $this->address = $address;
        $this->timeZone = $timeZone;
        $this->codeIATA = $codeIATA;
        $this->countryCode = $countryCode;
        $this->externalPlaceId = $externalPlaceId;
        $this->searchTags = $searchTags;
        $this->cityLocation = $cityLocation;
        $this->countryLocation = $countryLocation;
        $this->type = $type;
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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @param float|null $latitude
     */
    public function setLatitude(?float $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float|null
     */
    public function getLongtitude(): ?float
    {
        return $this->longtitude;
    }

    /**
     * @param float|null $longtitude
     */
    public function setLongtitude(?float $longtitude): void
    {
        $this->longtitude = $longtitude;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string|null
     */
    public function getTimeZone(): ?string
    {
        return $this->timeZone;
    }

    /**
     * @param string|null $timeZone
     */
    public function setTimeZone(?string $timeZone): void
    {
        $this->timeZone = $timeZone;
    }

    /**
     * @return string|null
     */
    public function getCodeIATA(): ?string
    {
        return $this->codeIATA;
    }

    /**
     * @param string|null $codeIATA
     */
    public function setCodeIATA(?string $codeIATA): void
    {
        $this->codeIATA = $codeIATA;
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @param string|null $countryCode
     */
    public function setCountryCode(?string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return string|null
     */
    public function getExternalPlaceId(): ?string
    {
        return $this->externalPlaceId;
    }

    /**
     * @param string|null $externalPlaceId
     */
    public function setExternalPlaceId(?string $externalPlaceId): void
    {
        $this->externalPlaceId = $externalPlaceId;
    }

    /**
     * @return string|null
     */
    public function getSearchTags(): ?string
    {
        return $this->searchTags;
    }

    /**
     * @param string|null $searchTags
     */
    public function setSearchTags(?string $searchTags): void
    {
        $this->searchTags = $searchTags;
    }

    /**
     * @return Location|null
     */
    public function getCityLocation(): ?Location
    {
        return $this->cityLocation;
    }

    /**
     * @param Location|null $cityLocation
     */
    public function setCityLocation(?Location $cityLocation): void
    {
        $this->cityLocation = $cityLocation;
    }

    /**
     * @return Location|null
     */
    public function getCountryLocation(): ?Location
    {
        return $this->countryLocation;
    }

    /**
     * @param Location|null $countryLocation
     */
    public function setCountryLocation(?Location $countryLocation): void
    {
        $this->countryLocation = $countryLocation;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

}