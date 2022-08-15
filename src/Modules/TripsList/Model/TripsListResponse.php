<?php

namespace App\Modules\TripsList\Model;

class TripsListResponse
{
    /**
     * @var TripsListItem[]
     */
    public array $items;

    /**
     * @param TripsListItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }


}