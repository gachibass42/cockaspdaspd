<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripRepository::class)]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $startDate;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $finishDate;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'trips')]
    private $tripId;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: Milestone::class, orphanRemoval: true)]
    private $milestones;

    public function __construct()
    {
        $this->tripId = new ArrayCollection();
        $this->milestones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getFinishDate(): ?\DateTimeInterface
    {
        return $this->finishDate;
    }

    public function setFinishDate(?\DateTimeInterface $finishDate): self
    {
        $this->finishDate = $finishDate;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getTripId(): Collection
    {
        return $this->tripId;
    }

    public function addTripId(User $tripId): self
    {
        if (!$this->tripId->contains($tripId)) {
            $this->tripId[] = $tripId;
        }

        return $this;
    }

    public function removeTripId(User $tripId): self
    {
        $this->tripId->removeElement($tripId);

        return $this;
    }

    /**
     * @return Collection|Milestone[]
     */
    public function getMilestones(): Collection
    {
        return $this->milestones;
    }

    public function addMilestone(Milestone $milestone): self
    {
        if (!$this->milestones->contains($milestone)) {
            $this->milestones[] = $milestone;
            $milestone->setTrip($this);
        }

        return $this;
    }

    public function removeMilestone(Milestone $milestone): self
    {
        if ($this->milestones->removeElement($milestone)) {
            // set the owning side to null (unless already changed)
            if ($milestone->getTrip() === $this) {
                $milestone->setTrip(null);
            }
        }

        return $this;
    }
}
