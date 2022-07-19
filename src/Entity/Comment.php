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
    private $linkedObjID;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private $type;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private $owner;

    #[ORM\Column(type: 'simple_array', nullable: true)]
    private $images = [];

    #[ORM\Column(type: 'simple_array', nullable: true)]
    private $tags = [];

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $date;

    #[ORM\Column(type: 'text', nullable: true)]
    private $content;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $syncDate;

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
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

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

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getSyncDate(): ?\DateTimeInterface
    {
        return $this->syncDate;
    }

    public function setSyncDate(?\DateTimeInterface $syncDate): self
    {
        $this->syncDate = $syncDate;

        return $this;
    }
}
