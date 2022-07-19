<?php

namespace App\Modules\Syncer\Model;

class SyncObjectLocation
{
    private string $objID;
    private int $syncStatusDateTime;
    private string $syncAction;
    private ?string $name;
    private ?float $lat;
    private ?float $lon;
    private ?string $address;
    private ?string $timeZone;
    private ?string $codeIATA;
    private ?string $countryCode;
    private ?string $externalPlaceId;
    private ?string $searchTags;
    private ?string $internationalName;
    private ?string $internationalAddress;
    private ?string $cityLocationID;
    private ?string $countryLocationID;
    private ?string $type;
    private ?string $ownerID;
    private ?string $phoneNumber;
    private ?string $website;

    /**
     * @param string $objID
     * @param int $syncStatusDateTime
     * @param string $syncAction
     * @param string|null $name
     * @param float|null $lat
     * @param float|null $lon
     * @param string|null $address
     * @param string|null $timeZone
     * @param string|null $codeIATA
     * @param string|null $countryCode
     * @param string|null $externalPlaceId
     * @param string|null $searchTags
     * @param string|null $internationalName
     * @param string|null $internationalAddress
     * @param string|null $cityLocationID
     * @param string|null $countryLocationID
     * @param string|null $type
     * @param string|null $ownerID
     * @param string|null $phoneNumber
     * @param string|null $website
     */
    public function __construct(string $objID, int $syncStatusDateTime, string $syncAction, ?string $name, ?float $lat, ?float $lon, ?string $address, ?string $timeZone, ?string $codeIATA, ?string $countryCode, ?string $externalPlaceId, ?string $searchTags, ?string $internationalName, ?string $internationalAddress, ?string $cityLocationID, ?string $countryLocationID, ?string $type, ?string $ownerID, ?string $phoneNumber, ?string $website)
    {
        $this->objID = $objID;
        $this->syncStatusDateTime = $syncStatusDateTime;
        $this->syncAction = $syncAction;
        $this->lat = $lat;
        $this->name = $name;
        $this->address = $address;
        $this->lon = $lon;
        $this->timeZone = $timeZone;
        $this->codeIATA = $codeIATA;
        $this->countryCode = $countryCode;
        $this->externalPlaceId = $externalPlaceId;
        $this->searchTags = $searchTags;
        $this->internationalName = $internationalName;
        $this->internationalAddress = $internationalAddress;
        $this->cityLocationID = $cityLocationID;
        $this->countryLocationID = $countryLocationID;
        $this->type = $type;
        $this->ownerID = $ownerID;
        $this->phoneNumber = $phoneNumber;
        $this->website = $website;
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
     * @return int
     */
    public function getSyncStatusDateTime(): int
    {
        return $this->syncStatusDateTime;
    }

    /**
     * @param int $syncStatusDateTime
     */
    public function setSyncStatusDateTime(int $syncStatusDateTime): void
    {
        $this->syncStatusDateTime = $syncStatusDateTime;
    }

    /**
     * @return string
     */
    public function getSyncAction(): string
    {
        return $this->syncAction;
    }

    /**
     * @param string $syncAction
     */
    public function setSyncAction(string $syncAction): void
    {
        $this->syncAction = $syncAction;
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
    public function getLat(): ?float
    {
        return $this->lat;
    }

    /**
     * @param float|null $lat
     */
    public function setLat(?float $lat): void
    {
        $this->lat = $lat;
    }

    /**
     * @return float|null
     */
    public function getLon(): ?float
    {
        return $this->lon;
    }

    /**
     * @param float|null $lon
     */
    public function setLon(?float $lon): void
    {
        $this->lon = $lon;
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
     * @return string|null
     */
    public function getInternationalName(): ?string
    {
        return $this->internationalName;
    }

    /**
     * @param string|null $internationalName
     */
    public function setInternationalName(?string $internationalName): void
    {
        $this->internationalName = $internationalName;
    }

    /**
     * @return string|null
     */
    public function getInternationalAddress(): ?string
    {
        return $this->internationalAddress;
    }

    /**
     * @param string|null $internationalAddress
     */
    public function setInternationalAddress(?string $internationalAddress): void
    {
        $this->internationalAddress = $internationalAddress;
    }

    /**
     * @return string|null
     */
    public function getCityLocationID(): ?string
    {
        return $this->cityLocationID;
    }

    /**
     * @param string|null $cityLocationID
     */
    public function setCityLocationID(?string $cityLocationID): void
    {
        $this->cityLocationID = $cityLocationID;
    }

    /**
     * @return string|null
     */
    public function getCountryLocationID(): ?string
    {
        return $this->countryLocationID;
    }

    /**
     * @param string|null $countryLocationID
     */
    public function setCountryLocationID(?string $countryLocationID): void
    {
        $this->countryLocationID = $countryLocationID;
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

    /**
     * @return string|null
     */
    public function getOwnerID(): ?string
    {
        return $this->ownerID;
    }

    /**
     * @param string|null $ownerID
     */
    public function setOwnerID(?string $ownerID): void
    {
        $this->ownerID = $ownerID;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string|null $phoneNumber
     */
    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string|null
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * @param string|null $website
     */
    public function setWebsite(?string $website): void
    {
        $this->website = $website;
    }


}