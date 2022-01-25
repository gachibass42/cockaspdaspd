<?php

namespace App\Model;

class UserProfileResponse
{
    /**
     * @var UserProfile[]
     */

    public ?array $items;

    /**
     * @param UserProfile[] $items
     */
    public function __construct(?array $items)
    {
        $this->items = $items;
    }

    /**
     * @return UserProfile[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param UserProfile[] $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }


}