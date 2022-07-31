<?php

namespace App\Modules\Syncer\Model;

class SyncObjectCheckList
{
    public ?string $objID;
    public ?string $name;
    public int $syncStatusDateTime;

    public string $type;

    public ?array $boxes;

    /**
     * @param string|null $objID
     * @param string|null $name
     * @param int $syncStatusDateTime
     * @param string $type
     * @param array|null $boxes
     */
    public function __construct(?string $objID, ?string $name, int $syncStatusDateTime, string $type, ?array $boxes)
    {
        $this->objID = $objID;
        $this->name = $name;
        $this->syncStatusDateTime = $syncStatusDateTime;
        $this->type = $type;
        $this->boxes = $boxes;
    }


}