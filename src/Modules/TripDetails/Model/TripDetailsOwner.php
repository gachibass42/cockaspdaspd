<?php

namespace App\Modules\TripDetails\Model;

class TripDetailsOwner
{
    public string $id;
    public string $name;
    public ?string $avatar;

    /**
     * @param string $id
     * @param string $name
     * @param string|null $avatar
     */
    public function __construct(string $id, string $name, ?string $avatar)
    {
        $this->id = $id;
        $this->name = $name;
        $this->avatar = $avatar;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string|null $avatar
     */
    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }
}