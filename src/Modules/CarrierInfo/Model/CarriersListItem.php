<?php

namespace App\Modules\CarrierInfo\Model;

class CarriersListItem
{
    public ?string $name;
    public ?string $code;
    public ?string $internationalName;
    public ?string $type;
    public ?string $id;
    public ?string $objID;

    /**
     * @param string|null $name
     * @param string|null $code
     * @param string|null $internationalName
     * @param string|null $type
     * @param string|null $id
     * @param string|null $objID
     */
    public function __construct(?string $name, ?string $code, ?string $internationalName, ?string $type, ?string $id, ?string $objID)
    {
        $this->name = $name;
        $this->code = $code;
        $this->internationalName = $internationalName;
        $this->type = $type;
        $this->id = $id;
        $this->objID = $objID;
    }
}