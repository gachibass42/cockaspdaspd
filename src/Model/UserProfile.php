<?php

namespace App\Model;

class UserProfile
{
    public int $id;
    public string $name;
    public string $phone;
    public string $userDescription;
    public bool $isGuide;
    public string $avatarURL;
    //public Image $avatar;

    /**
     * @param int $id
     * @param string $name
     * @param string $phone
     * @param string $description
     * @param bool $isGuide
     * @param string $avatarURL
     * @param Image $avatar
     */
    public function __construct(int $id, string $name, string $phone, string $description, bool $isGuide, string $avatarURL, Image $avatar = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->phone = $phone;
        $this->userDescription = $description;
        $this->isGuide = $isGuide;
        $this->avatarURL = $avatarURL;
        $this->avatar = $avatar;
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
    public function getUserDescription(): string
    {
        return $this->userDescription;
    }

    /**
     * @param string $userDescription
     */
    public function setUserDescription(string $userDescription): void
    {
        $this->userDescription = $userDescription;
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