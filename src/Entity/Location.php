<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{

    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 64)]
    private string $objID;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['location_details'])]
    private ?string $name = null;

    #[ORM\Column(type: 'float')]
    #[Groups(['location_details'])]
    private ?float $lat = null;

    #[ORM\Column(type: 'float')]
    #[Groups(['location_details'])]
    private ?float $lon = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private ?string $timeZone = null;

    #[ORM\Column(type: 'string', length: 3, nullable: true)]
    private ?string $codeIATA = null;

    #[ORM\Column(type: 'string', length: 3, nullable: true)]
    private ?string $countryCode = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $externalPlaceId = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $searchTags = null;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\Location')]
    #[ORM\JoinColumn(name: "city_location",referencedColumnName: "obj_id")]
    private ?Location $cityLocation = null;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\Location')]
    #[ORM\JoinColumn(name: "country_location",referencedColumnName: "obj_id")]
    private ?Location $countryLocation = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(type: 'string', length: 512, nullable: true)]
    private ?string $internationalName;

    #[ORM\Column(type: 'string', length: 2048, nullable: true)]
    private ?string $internationalAddress;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "owner_id",referencedColumnName: "id")]
    private ?User $owner;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $syncDate;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private ?string $phoneNumber;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $website;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
    }

    public function getExternalPlaceId(): ?string
    {
        return $this->externalPlaceId;
    }

    public function setExternalPlaceId(string $externalPlaceId): self
    {
        $this->externalPlaceId = $externalPlaceId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLon(): ?float
    {
        return $this->lon;
    }

    public function setLon(float $lon): self
    {
        $this->lon = $lon;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description  ?? null;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address ?? null;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getTimeZone(): ?string
    {
        return $this->timeZone ?? null;
    }

    public function setTimeZone(?string $timeZone): self
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    public function getCodeIATA(): ?string
    {
        return $this->codeIATA ?? null;
    }

    public function setCodeIATA(?string $codeIATA): self
    {
        $this->codeIATA = $codeIATA;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode ?? null;
    }

    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getCityLocation(): ?Location
    {
        return $this->cityLocation ?? null;
    }

    public function setCityLocation(?Location $cityLocation): Location
    {
        $this->cityLocation = $cityLocation;
        return $this;
    }

    #[Ignore]
    public function getCityLocationIATACode(): ?string
    {
        return $this->cityLocation->codeIATA ?? null;
    }

    #[Ignore]
    public function setCityLocationIATACode(string $code): Location
    {
        if (isset($this->cityLocation)) {
            $this->cityLocation->setCodeIATA($code);
        }
        return $this;
    }

    public function getCountryLocation(): ?Location
    {
        return $this->countryLocation ?? null;
    }

    public function setCountryLocation(?Location $countryLocation): Location
    {
        $this->countryLocation = $countryLocation;
        return $this;
    }

    public function getSearchTags(): ?string
    {
        return $this->searchTags ?? null;
    }

    public function setSearchTags(?string $searchTags): Location
    {
        $this->searchTags = $searchTags;
        return $this;
    }

    public function addSearchTag(string $searchTag): void
    {
        if (!$this->searchTags) {
            return;
        }

        $tags = explode(' ', $this->searchTags);
        if (!\in_array($searchTag, $tags, true)) {
            $tags[] = $searchTag;
        }

        $this->searchTags = implode(' ', $tags);
    }

    public function getType(): ?string
    {
        return $this->type ?? null;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getObjID(): ?string
    {
        return $this->objID ?? "";
    }

    public function setObjID(string $objID): self
    {
        $this->objID = $objID;

        return $this;
    }

    public function getInternationalName(): ?string
    {
        return $this->internationalName ?? null;
    }

    public function setInternationalName(?string $internationalName): self
    {
        $this->internationalName = $internationalName;

        return $this;
    }

    public function getInternationalAddress(): ?string
    {
        return $this->internationalAddress ?? null;
    }

    public function setInternationalAddress(?string $internationalAddress): self
    {
        $this->internationalAddress = $internationalAddress;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner ?? null;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getSyncDate(): ?\DateTimeInterface
    {
        return $this->syncDate ?? null;
    }

    public function setSyncDate(?\DateTimeInterface $syncDate): self
    {
        $this->syncDate = $syncDate;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber ?? null;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website ?? null;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

}
