<?php

namespace App\Model;

class UserProfile
{
    public int $id;
    public string $name;
    public string $phone;
    public string $description;
    public bool $isGuide;

    /**
     * @param int $id
     * @param string $name
     * @param string $phone
     * @param string $description
     * @param bool $isGuide
     */
    public function __construct(int $id, string $name, string $phone, string $description, bool $isGuide)
    {
        $this->id = $id;
        $this->name = $name;
        $this->phone = $phone;
        $this->description = $description;
        $this->isGuide = $isGuide;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
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
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function isGuide(): bool
    {
        return $this->isGuide;
    }

    /**
     * @param bool $isGuide
     */
    public function setIsGuide(bool $isGuide): void
    {
        $this->isGuide = $isGuide;
    }


}