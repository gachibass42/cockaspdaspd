<?php

namespace App\Modules\CarrierInformation;

use App\Entity\Airline;
use App\Repository\AirlineRepository;

class CarrierInformationService
{

    public function __construct(private AirlineRepository $airlineRepository)
    {
    }

    public function getCarriersList (string $type) {

    }
}