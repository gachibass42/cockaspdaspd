<?php

namespace App\Entity;

use App\Repository\TripUserRoleRepository;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripUserRoleRepository::class)]
#[UniqueConstraint(name: "user_role",columns: ['trip_id', 'trip_user_id', 'role_name'])]
class TripUserRole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 16)]
    private ?string $roleName;

    #[ORM\ManyToOne(targetEntity: Trip::class, inversedBy: 'usersRoles')]
    #[ORM\JoinColumn(referencedColumnName: 'obj_id', nullable: false)]
    private ?Trip $trip;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tripsRoles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $tripUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoleName(): ?string
    {
        return $this->roleName;
    }

    public function setRoleName(string $roleName): self
    {
        $this->roleName = $roleName;

        return $this;
    }

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function setTrip(?Trip $trip): self
    {
        $this->trip = $trip;

        return $this;
    }

    public function getTripUser(): ?User
    {
        return $this->tripUser;
    }

    public function setTripUser(?User $tripUser): self
    {
        $this->tripUser = $tripUser;

        return $this;
    }
}
