<?php

namespace App\Modules\CarrierInfo\Model;

class CarriersListResponse
{

    /**
     * @var CarriersListItem[]
     */
    public ?array $items;

    /**
     * @param CarriersListItem[]|null $items
     */
    public function __construct(?array $items)
    {
        $this->items = $items;
    }

    /**
     * @return CarriersListItem[]
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * @param CarriersListItem[] $items
     */
    public function setItems(?array $items): void
    {
        $this->items = $items;
    }


}