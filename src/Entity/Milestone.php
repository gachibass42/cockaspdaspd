<?php

namespace App\Entity;

use App\Repository\MilestoneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MilestoneRepository::class)]
class Milestone
{
    /*#[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;*/
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 64)]
    private string $objID;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $date;

    #[ORM\Column(type: 'string', length: 64)]
    private ?string $type;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $milestoneDescription;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $milestoneOrder;

    #[ORM\Column(type: 'boolean')]
    private ?bool $userEdited;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $journeyNumber;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $seat;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $vagon;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private ?string $classType;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $terminal;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $distance;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $aircraft;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $duration;

    #[ORM\Column(type: 'string', length: 3000, nullable: true)]
    private ?string $address;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $website;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private ?string $phoneNumber;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isStartPoint;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isEndPoint;

    #[ORM\Column(type: 'boolean')]
    private ?bool $inTransit;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private ?string $rentType;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $name;

    #[ORM\ManyToOne(targetEntity: Airline::class)]
    #[ORM\JoinColumn(name: "carrier",referencedColumnName: "obj_id")]
    private ?Airline $carrier;

    #[ORM\Column(type: 'simple_array', nullable: true)]
    private array $linkedMilestonesIDs = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private array $mealTimetables = [];

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $syncDate;

    #[ORM\Column(type: 'boolean')]
    private ?bool $visibility;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner;

    #[ORM\Column(type: 'simple_array', nullable: true)]
    private array $tags = [];

    #[ORM\Column(type: 'string', length: 15, nullable: true)]
    private ?string $roomNumber;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private ?string $locationID;

    #[ORM\Column(type: 'simple_array', nullable: true)]
    private array $images = [];

    public function __construct()
    {

    }

    public function getObjId(): ?string
    {
        return $this->objID;
    }

    public function setObjId(string $objID): self
    {
        $this->objID = $objID;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date ?? null;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type ?? null;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMilestoneDescription(): ?string
    {
        return $this->milestoneDescription ?? null;
    }

    public function setMilestoneDescription(?string $milestoneDescription): self
    {
        $this->milestoneDescription = $milestoneDescription;

        return $this;
    }

    public function getMilestoneOrder(): ?int
    {
        return $this->milestoneOrder ?? null;
    }

    public function setMilestoneOrder(?int $milestoneOrder): self
    {
        $this->milestoneOrder = $milestoneOrder;

        return $this;
    }

    public function getUserEdited(): ?bool
    {
        return $this->userEdited ?? null;
    }

    public function setUserEdited(bool $userEdited): self
    {
        $this->userEdited = $userEdited;

        return $this;
    }

    public function getJourneyNumber(): ?string
    {
        return $this->journeyNumber ?? null;
    }

    public function setJourneyNumber(?string $journeyNumber): self
    {
        $this->journeyNumber = $journeyNumber;

        return $this;
    }

    public function getSeat(): ?string
    {
        return $this->seat ?? null;
    }

    public function setSeat(?string $seat): self
    {
        $this->seat = $seat;

        return $this;
    }

    public function getVagon(): ?int
    {
        return $this->vagon ?? null;
    }

    public function setVagon(?int $vagon): self
    {
        $this->vagon = $vagon;

        return $this;
    }

    public function getClassType(): ?string
    {
        return $this->classType ?? null;
    }

    public function setClassType(?string $classType): self
    {
        $this->classType = $classType;

        return $this;
    }

    public function getTerminal(): ?string
    {
        return $this->terminal ?? null;
    }

    public function setTerminal(?string $terminal): self
    {
        $this->terminal = $terminal;

        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance ?? null;
    }

    public function setDistance(?float $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getAircraft(): ?string
    {
        return $this->aircraft ?? null;
    }

    public function setAircraft(?string $aircraft): self
    {
        $this->aircraft = $aircraft;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration ?? null;
    }

    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address ?? null;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website ?? null;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber ?? null;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getIsStartPoint(): ?bool
    {
        return $this->isStartPoint;
    }

    public function setIsStartPoint(bool $isStartPoint): self
    {
        $this->isStartPoint = $isStartPoint;

        return $this;
    }

    public function getIsEndPoint(): ?bool
    {
        return $this->isEndPoint;
    }

    public function setIsEndPoint(bool $isEndPoint): self
    {
        $this->isEndPoint = $isEndPoint;

        return $this;
    }

    public function getInTransit(): ?bool
    {
        return $this->inTransit;
    }

    public function setInTransit(bool $inTransit): self
    {
        $this->inTransit = $inTransit;

        return $this;
    }

    public function getRentType(): ?string
    {
        return $this->rentType ?? null;
    }

    public function setRentType(?string $rentType): self
    {
        $this->rentType = $rentType;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCarrier(): ?Airline
    {
        return $this->carrier ?? null;
    }

    public function setCarrier(?Airline $carrier): self
    {
        $this->carrier = $carrier;

        return $this;
    }

    public function getLinkedMilestonesIDs(): ?array
    {
        return $this->linkedMilestonesIDs;
    }

    public function setLinkedMilestonesIDs(?array $linkedMilestonesIDs): self
    {
        $this->linkedMilestonesIDs = $linkedMilestonesIDs;

        return $this;
    }

    public function getMealTimetables(): ?array
    {
        return $this->mealTimetables;
    }

    public function setMealTimetables(?array $mealTimetables): self
    {
        $this->mealTimetables = $mealTimetables;

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

    public function getVisibility(): ?bool
    {
        return $this->visibility ?? null;
    }

    public function setVisibility(bool $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner ?? null;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tags ?? null;
    }

    public function setTags(?array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getRoomNumber(): ?string
    {
        return $this->roomNumber ?? null;
    }

    public function setRoomNumber(?string $roomNumber): self
    {
        $this->roomNumber = $roomNumber;

        return $this;
    }

    public function getLocationID(): ?string
    {
        return $this->locationID ?? null;
    }

    public function setLocationID(?string $locationID): self
    {
        $this->locationID = $locationID;

        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images ?? null;
    }

    public function setImages(?array $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function appendImage(string $imageName): self
    {
        $this->images[] = $imageName;

        return $this;
    }
}
