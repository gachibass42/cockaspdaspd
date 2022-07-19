<?php

namespace App\Modules\CarrierInfo;

use App\Entity\Airline;
use App\Modules\CarrierInfo\Model\CarriersListItem;
use App\Modules\CarrierInfo\Model\CarriersListResponse;
use App\Repository\AirlineRepository;

class CarrierInfoService
{

    public function __construct(private AirlineRepository $airlineRepository)
    {
    }

    public function getCarriersList(string $type): CarriersListResponse {
        $carriers = new CarriersListResponse([]);
        if ($type == 'airline') {
            $carriers = $this->airlineRepository->findAll();
            return new CarriersListResponse(array_map(
                fn (Airline $airline) => new CarriersListItem(
                    $airline->getName(),
                    $airline->getCode(),
                    $airline->getInternationalName(),
                    'airline',
                    $airline->getId(),
                    $airline->getObjID()
                ),
                $carriers
            ));
        }

       return $carriers;
    }

    public function loadCarriers(array $carriers): void
    {
        //dump ($carriers);
        foreach ($carriers as $carrier) {
            //$carriersIndexed[$carriers['guid']] = $carrier['objID'];
            $airline = $this->airlineRepository->findOneBy(['id'=>$carrier['object']['guid']]);
            $airline->setObjID($carrier['object']['objID']);
            $this->airlineRepository->updateAirline($airline);
        }


    }
}