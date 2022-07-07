<?php

namespace App\Entity;

use App\Repository\AirportIATARepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AirportIATARepository::class)]
class AirportIATA
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 3)]
    private $airportCode;

    #[ORM\Column(type: 'string', length: 2, nullable: true)]
    private $countryCode;

    #[ORM\Column(type: 'string', length: 1024, nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', length: 1024, nullable: true)]
    private $cityName;

    #[ORM\Column(type: 'string', length: 1024, nullable: true)]
    private $internationalName;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $timeZone;

    #[ORM\Column(type: 'float', nullable: true)]
    private $latitude;

    #[ORM\Column(type: 'float', nullable: true)]
    private $longitude;

    #[ORM\Column(type: 'string', length: 1024, nullable: true)]
    private $cityInternationalName;

    #[ORM\Column(type: 'float', nullable: true)]
    private $cityLatitude;

    #[ORM\Column(type: 'float', nullable: true)]
    private $cityLongitude;

    #[ORM\OneToOne(targetEntity: Location::class)]
    #[ORM\JoinColumn(name: "location",referencedColumnName: "obj_id",nullable: true)]
    private $location;

    #[ORM\OneToOne(targetEntity: Location::class)]
    #[ORM\JoinColumn(name: "city_location",referencedColumnName: "obj_id",nullable: true)]
    private $cityLocation;

    #[ORM\Column(type: 'string', length: 3, nullable: true)]
    private $cityCode;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAirportCode(): ?string
    {
        return $this->airportCode;
    }

    public function setAirportCode(string $airportCode): self
    {
        $this->airportCode = $airportCode;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCityName(): ?string
    {
        return $this->cityName;
    }

    public function setCityName(?string $cityName): self
    {
        $this->cityName = $cityName;

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

    public function getTimeZone(): ?string
    {
        return $this->timeZone;
    }

    public function setTimeZone(?string $timeZone): self
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getCityInternationalName(): ?string
    {
        return $this->cityInternationalName;
    }

    public function setCityInternationalName(?string $cityInternationalName): self
    {
        $this->cityInternationalName = $cityInternationalName;

        return $this;
    }

    public function getCityLatitude(): ?float
    {
        return $this->cityLatitude;
    }

    public function setCityLatitude(?float $cityLatitude): self
    {
        $this->cityLatitude = $cityLatitude;

        return $this;
    }

    public function getCityLongitude(): ?float
    {
        return $this->cityLongitude;
    }

    public function setCityLongitude(?float $cityLongitude): self
    {
        $this->cityLongitude = $cityLongitude;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getCityLocation(): ?Location
    {
        return $this->cityLocation;
    }

    public function setCityLocation(?Location $cityLocation): self
    {
        $this->cityLocation = $cityLocation;

        return $this;
    }

    public function getCityCode(): ?string
    {
        return $this->cityCode;
    }

    public function setCityCode(?string $cityCode): self
    {
        $this->cityCode = $cityCode;

        return $this;
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
}
