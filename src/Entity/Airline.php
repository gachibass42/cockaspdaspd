<?php

namespace App\Entity;

use App\Repository\AirlineRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV6;

#[ORM\Entity(repositoryClass: AirlineRepository::class)]
class Airline
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\Column(type: 'uuid',unique: 'true')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidV6 $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private ?string $code;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $internationalName;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description;

    #[ORM\Column(type: 'string', length: 24, nullable: true)]
    private ?string $objID;

    public function __construct()
    {
        $this->id = Uuid::v6();
    }

    /**
     * @return UuidV6
     */
    public function getId(): UuidV6
    {
        return $this->id;
    }

    /**
     * @param UuidV6 $id
     */
    public function setId(UuidV6 $id): void
    {
        $this->id = $id;
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

    public function getObjID(): ?string
    {
        return $this->objID;
    }

    public function setObjID(?string $objID): self
    {
        $this->objID = $objID;

        return $this;
    }
}
