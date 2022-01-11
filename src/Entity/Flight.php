<?php

namespace App\Entity;
use App\Repository\FlightRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FlightRepository::class)]
class Flight extends Movement
{
    #[ORM\Column(type: 'integer')]
    private $gateNumber;

    /**
     * @return integer
     */
    public function getGateNumber(): ?int
    {
        return $this->gateNumber;
    }

    /**
     * @param integer $gateNumber
     */
    public function setGateNumber($gateNumber): void
    {
        $this->gateNumber = $gateNumber;
    }
}