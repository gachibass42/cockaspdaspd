<?php

namespace App\Entity;
use App\Repository\MovementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovementRepository::class)]
#[ORM\InheritanceType("JOINED")]

class Movement extends Milestone
{
 /*   #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;
*/
    #[ORM\Column(type: 'string', length: 255)]
    protected $voyageId;

    /**
     * @return string
     */
    public function getVoyageId(): ?string
    {
        return $this->voyageId;
    }

    /**
     * @param string $voyageId
     */
    public function setVoyageId($voyageId): void
    {
        $this->voyageId = $voyageId;
    }

}