<?php

namespace App\Entity;

use App\Repository\AirlineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AirlineRepository::class)]
class Airline
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private $code;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $internationalName;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
