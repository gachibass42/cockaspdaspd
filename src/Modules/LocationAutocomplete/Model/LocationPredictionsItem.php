<?php
declare(strict_types=1);
namespace App\Modules\LocationAutocomplete\Model;

use Symfony\Component\Serializer\Annotation\Groups;

class LocationPredictionsItem
{
    #[Groups(['location_predictions'])]
    public string $objID;

    #[Groups(['location_predictions'])]
    public string $name;

    #[Groups(['location_predictions'])]
    public string $externalID;

    #[Groups(['location_predictions'])]
    public string $address;

    /**
     * @param string $objID
     * @param string $name
     * @param string $externalID
     * @param string $address
     */
    public function __construct(string $objID, string $name, string $externalID, string $address)
    {
        $this->objID= $objID;
        $this->name = $name;
        $this->externalID = $externalID;
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getObjID(): string
    {
        return $this->objID;
    }

    /**
     * @param string $objID
     */
    public function setObjID(string $objID): void
    {
        $this->objID = $objID;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getExternalID(): string
    {
        return $this->externalID;
    }

    /**
     * @param string $externalID
     */
    public function setExternalID(string $externalID): void
    {
        $this->externalID = $externalID;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }


}