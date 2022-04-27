<?php

namespace App\Modules\UsersList\Model;

class UsersListResponse
{
    /**
     * @var UsersListItem[]
     */

    public ?array $items;

    /**
     * @param UsersListItem[] $items
     */
    public function __construct(?array $items)
    {
        $this->items = $items;
    }

    /**
     * @return UsersListItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param UsersListItem[] $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }
}