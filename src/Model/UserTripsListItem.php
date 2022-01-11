<?php

namespace App\Model;

class UserTripsListItem
{
    public int $id;

    public string $name;

    /*private ?string $description;

    private ?\DateTime $startDate;

    private ?\DateTime $finishDate;*/

    public function __construct(int $id, string $name/*, ?string $description, ?\DateTime $startDate, ?\DateTime $finishDate*/)
    {
        $this->id = $id;
        $this->name = $name;
        /*$this->description = $description;
        $this->startDate = $startDate;
        $this->finishDate = $finishDate;*/
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /*public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getFinishDate(): \DateTime
    {
        return $this->finishDate;
    }

    public function setFinishDate(\DateTime $finishDate): void
    {
        $this->finishDate = $finishDate;
    }*/
}