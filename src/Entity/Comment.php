<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    /*#[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;*/
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 64)]
    private string $objID;

    #[ORM\Column(type: 'string', length: 32)]
    private ?string $linkedObjID;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private ?string $type;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $owner;

    #[ORM\Column(type: 'simple_array', nullable: true)]
    private array $images = [];

    #[ORM\Column(type: 'simple_array', nullable: true)]
    private array $tags = [];

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $date;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $content;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $syncDate;

    public function getObjId(): ?string
    {
        return $this->objID;
    }

    public function setObjId(string $objID): self
    {
        $this->objID = $objID;

        return $this;
    }

    public function getLinkedObjID(): ?string
    {
        return $this->linkedObjID;
    }

    public function setLinkedObjID(string $linkedObjID): self
    {
        $this->linkedObjID = $linkedObjID;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type ?? null;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

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

    public function getImages(): ?array
    {
        return $this->images ?? null;
    }

    public function setImages(?array $images): self
    {
        $this->images = $images;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date ?? null;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content ?? null;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

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
}
