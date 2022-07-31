<?php

namespace App\Modules\UserProfile\Model;

use App\Model\Image;

class UserProfile
{
    public string $id;
    public string $name;
    public ?string $phone;
    public ?string $userDescription;
    public bool $isGuide;
    public ?string $avatarURL;
    public ?string $login;
    public ?string $password;
    //public Image $avatar;

    /**
     * @param string $id
     * @param string $name
     * @param string|null $phone
     * @param string|null $description
     * @param bool $isGuide
     * @param string|null $avatarURL
     * @param Image|null $avatar
     */
    public function __construct(string $id, string $name, ?string $phone, ?string $description, bool $isGuide, ?string $avatarURL, Image $avatar = null)
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
     * @return string|null
     */
    public function getAvatarURL(): ?string
    {
        return $this->avatarURL;
    }

    /**
     * @param string|null $avatarURL
     */
    public function setAvatarURL(?string $avatarURL): void
    {
        $this->avatarURL = $avatarURL;
    }

    /**
     * @return string|null
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * @param string|null $login
     */
    public function setLogin(?string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
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
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string|null
     */
    public function getUserDescription(): ?string
    {
        return $this->userDescription;
    }

    /**
     * @param string|null $userDescription
     */
    public function setUserDescription(?string $userDescription): void
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