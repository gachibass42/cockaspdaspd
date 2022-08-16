<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripRepository::class)]
class Trip
{
    /*#[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;*/
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 64)]
    private string $objID;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $name;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $startDate;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $endDate;

    #[ORM\Column(type: 'boolean')]
    private ?bool $locked;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $duration;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $tripDescription;

    #[ORM\Column(type: 'string', length: 1024, nullable: true)]
    private ?string $tags;

    #[ORM\Column(type: 'simple_array', nullable: true)]
    private array $milestonesIDs = [];

    #[ORM\Column(type: 'boolean')]
    private ?bool $visibility;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $syncDate;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $mainImage;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: TripUserRole::class, orphanRemoval: true)]
    private $usersRoles;

    #[ORM\Column(type: 'simple_array', nullable: true)]
    private array $checkListsIDs = [];

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private ?string $cost;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $costDescription;

    public function __construct()
    {
        $this->usersRoles = new ArrayCollection();
    }

    public function getObjId(): ?string
    {
        return $this->objID;
    }

    public function setObjId(?string $objID): self
    {
        $this->objID = $objID;

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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getLocked(): ?bool
    {
        return $this->locked;
    }

    public function setLocked(bool $locked): self
    {
        $this->locked = $locked;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getTripDescription(): ?string
    {
        return $this->tripDescription;
    }

    public function setTripDescription(?string $tripDescription): self
    {
        $this->tripDescription = $tripDescription;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getMilestonesIDs(): ?array
    {
        return $this->milestonesIDs ?? null;
    }

    public function setMilestonesIDs(?array $milestonesIDs): self
    {
        $this->milestonesIDs = $milestonesIDs;

        return $this;
    }

    public function getVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): self
    {
        $this->visibility = $visibility;

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

    public function getMainImage(): ?string
    {
        return $this->mainImage ?? null;
    }

    public function setMainImage(?string $mainImage): self
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    /**
     * @return Collection<int, TripUserRole>
     */
    public function getUsersRoles(): Collection
    {
        return $this->usersRoles;
    }

    /*public function syncGetUsersRoles(): ?array {
        //dump($this->usersRoles->);

    }*/

    public function addUsersRole(TripUserRole $usersRole): self
    {
        if (!$this->usersRoles->contains($usersRole)) {
            $this->usersRoles[] = $usersRole;
            $usersRole->setTrip($this);
        }

        return $this;
    }

    public function removeUsersRole(TripUserRole $usersRole): self
    {
        if ($this->usersRoles->removeElement($usersRole)) {
            // set the owning side to null (unless already changed)
            if ($usersRole->getTrip() === $this) {
                $usersRole->setTrip(null);
            }
        }

        return $this;
    }

    public function getCheckListsIDs(): ?array
    {
        return $this->checkListsIDs;
    }

    public function setCheckListsIDs(?array $checkListsIDs): self
    {
        $this->checkListsIDs = $checkListsIDs;

        return $this;
    }

    public function getCost(): ?string
    {
        return $this->cost;
    }

    public function setCost(?string $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getCostDescription(): ?string
    {
        return $this->costDescription;
    }

    public function setCostDescription(?string $costDescription): self
    {
        $this->costDescription = $costDescription;

        return $this;
    }
}
