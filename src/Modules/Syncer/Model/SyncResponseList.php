<?php

namespace App\Modules\Syncer\Model;

class SyncResponseList
{
    public array $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function appendArray (array $newItems): void
    {
        $this->items[] = $newItems;
    }

}