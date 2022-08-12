<?php

namespace App\Modules\Syncer\Model;

class SyncObjectLocation
{
    public string $objID;
    public int $syncStatusDateTime;
    public string $syncAction;
    public ?string $name;
    public ?float $latitude;
    public ?float $longitude;
    public ?string $address;
    public ?string $timeZoneIdentifier;
    public ?string $codeIATA;
    public ?string $countryCode;
    public ?string $externalID;
    public ?string $searchTags;
    public ?string $internationalName;
    public ?string $internationalAddress;
    public ?string $cityLocationID;
    public ?string $countryLocationID;
    public ?string $type;
    public ?string $ownerID;
    public ?string $phoneNumber;
    public ?string $website;
    public ?string $descriptionLocation;

    /**
     * @param string $objID
     * @param int $syncStatusDateTime
     * @param string $syncAction
     * @param string|null $name
     * @param float|null $latitude
     * @param float|null $longitude
     * @param string|null $address
     * @param string|null $timeZoneIdentifier
     * @param string|null $codeIATA
     * @param string|null $countryCode
     * @param string|null $externalID
     * @param string|null $searchTags
     * @param string|null $internationalName
     * @param string|null $internationalAddress
     * @param string|null $cityLocationID
     * @param string|null $countryLocationID
     * @param string|null $type
     * @param string|null $ownerID
     * @param string|null $phoneNumber
     * @param string|null $website
     * @param string|null $descriptionLocation
     */
    public function __construct(string $objID, int $syncStatusDateTime, string $syncAction, ?string $name, ?float $latitude, ?float $longitude, ?string $address, ?string $timeZoneIdentifier, ?string $codeIATA, ?string $countryCode, ?string $externalID, ?string $searchTags, ?string $internationalName, ?string $internationalAddress, ?string $cityLocationID, ?string $countryLocationID, ?string $type, ?string $ownerID, ?string $phoneNumber, ?string $website, ?string $descriptionLocation)
    {
        $this->objID = $objID;
        $this->syncStatusDateTime = $syncStatusDateTime;
        $this->syncAction = $syncAction;
        $this->name = $name;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->address = $address;
        $this->timeZoneIdentifier = $timeZoneIdentifier;
        $this->codeIATA = $codeIATA;
        $this->countryCode = $countryCode;
        $this->externalID = $externalID;
        $this->searchTags = $searchTags;
        $this->internationalName = $internationalName;
        $this->internationalAddress = $internationalAddress;
        $this->cityLocationID = $cityLocationID;
        $this->countryLocationID = $countryLocationID;
        $this->type = $type;
        $this->ownerID = $ownerID;
        $this->phoneNumber = $phoneNumber;
        $this->website = $website;
        $this->descriptionLocation = $descriptionLocation;
    }

    /**
     * @return string|null
     */
    public function getDescriptionLocation(): ?string
    {
        return $this->descriptionLocation;
    }

    /**
     * @param string|null $descriptionLocation
     */
    public function setDescriptionLocation(?string $descriptionLocation): void
    {
        $this->descriptionLocation = $descriptionLocation;
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
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * @param float|null $longitude
     */
    public function setLongitude(?float $longitude): void
    {
        $this->longitude = $longitude;
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
    public function getTimeZoneIdentifier(): ?string
    {
        return $this->timeZoneIdentifier;
    }

    /**
     * @param string|null $timeZoneIdentifier
     */
    public function setTimeZoneIdentifier(?string $timeZoneIdentifier): void
    {
        $this->timeZoneIdentifier = $timeZoneIdentifier;
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
    public function getExternalID(): ?string
    {
        return $this->externalID;
    }

    /**
     * @param string|null $externalID
     */
    public function setExternalID(?string $externalID): void
    {
        $this->externalID = $externalID;
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