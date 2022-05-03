<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
//#[ORM\Index(name: "ix_location_external_place_id", fields: ["googlePlaceId"])]
//#[ORM\Index(name: "ix_location_search_tags", fields: ["searchTags"])]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

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

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private ?string $externalPlaceId = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $searchTags = null;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\Location')]
    private ?Location $cityLocation = null;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\Location')]
    private ?Location $countryLocation = null;

    public function getId(): ?int
    {
        return $this->id;
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
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getTimeZone(): ?string
    {
        return $this->timeZone;
    }

    public function setTimeZone(?string $timeZone): self
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    public function getCodeIATA(): ?string
    {
        return $this->codeIATA;
    }

    public function setCodeIATA(?string $codeIATA): self
    {
        $this->codeIATA = $codeIATA;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getCityLocation(): ?Location
    {
        return $this->cityLocation;
    }

    public function setCityLocation(?Location $cityLocation): Location
    {
        $this->cityLocation = $cityLocation;
        return $this;
    }

    public function getCountryLocation(): ?Location
    {
        return $this->countryLocation;
    }

    public function setCountryLocation(?Location $countryLocation): Location
    {
        $this->countryLocation = $countryLocation;
        return $this;
    }

    public function getSearchTags(): ?string
    {
        return $this->searchTags;
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
}
