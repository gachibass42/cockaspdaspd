<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
//#[ORM\Index(name: "ix_location_external_place_id", fields: ["googlePlaceId"])]
//#[ORM\Index(name: "ix_location_search_tags", fields: ["searchTags"])]
class Location
{
    //#[ORM\Id]
    //#[ORM\GeneratedValue]
    /*#[ORM\Column(type: 'integer')]
    private ?int $id = null;*/
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

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
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
    private $internationalName;

    #[ORM\Column(type: 'string', length: 2048, nullable: true)]
    private $internationalAddress;

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

    #[Ignore]
    public function getCityLocationIATACode(): ?string
    {
        return $this->cityLocation->codeIATA;
    }

    public function setCityLocationIATACode(string $code): Location
    {
        if (isset($this->cityLocation)) {
            $this->cityLocation->setCodeIATA($code);
        }
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getObjID(): ?string
    {
        return $this->objID;
    }

    public function setObjID(string $objID): self
    {
        $this->objID = $objID;

        return $this;
    }

    public function getInternationalName(): ?string
    {
        return $this->internationalName;
    }

    public function setInternationalName(?string $internationalName): self
    {
        $this->internationalName = $internationalName;

        return $this;
    }

    public function getInternationalAddress(): ?string
    {
        return $this->internationalAddress;
    }

    public function setInternationalAddress(?string $internationalAddress): self
    {
        $this->internationalAddress = $internationalAddress;

        return $this;
    }

}
