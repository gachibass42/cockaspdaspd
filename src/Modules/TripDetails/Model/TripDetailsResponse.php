<?php

namespace App\Modules\TripDetails\Model;

class TripDetailsResponse
{

    /**
     * @var TripDetailsObject[]
     */
    public array $items;

    /**
     * @param TripDetailsObject[] $items
     */
    public function __construct(?array $items)
    {
        $this->items = $items ?? [];
    }
}