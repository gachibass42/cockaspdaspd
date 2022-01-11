<?php

namespace App\Model;

class UserTripsListResponse
{
    /**
     * @var UserTripsListItem[]
     */

    public array $items;

    /**
     * @param UserTripsListItem[] $items
     */

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return UserTripsListItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

}