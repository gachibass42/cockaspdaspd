<?php

namespace App\Modules\UsersList\Model;

class UsersListItem
{
    public string $id;
    public string $name;
    public string $avatarURL;

    /**
     * @param string $id
     * @param string $name
     * @param string $avatarURL
     */
    public function __construct(string $id, string $name, string $avatarURL)
    {
        $this->id = $id;
        $this->name = $name;
        $this->avatarURL = $avatarURL;
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
     * @return string
     */
    public function getAvatarURL(): string
    {
        return $this->avatarURL;
    }

    /**
     * @param string $avatarURL
     */
    public function setAvatarURL(string $avatarURL): void
    {
        $this->avatarURL = $avatarURL;
    }


}