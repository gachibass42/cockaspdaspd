<?php

namespace App\Modules\LocationDetails\Model;

class LocationDetailsResponse
{
    /**
     * @var LocationDetailsItem[]
     */

    public ?array $items;

    /**
     * @param LocationDetailsItem[] $items
     */
    public function __construct(?array $items)
    {
        $this->items = $items;
    }

    /**
     * @return LocationDetailsItem[]
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * @param LocationDetailsItem[] $items
     */
    public function setItems(?array $items): void
    {
        $this->items = $items;
    }

}