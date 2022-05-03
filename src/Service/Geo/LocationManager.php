<?php

declare(strict_types=1);

namespace App\Service\Geo;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;

class LocationManager
{
    public function __construct(
        private GooglePlacesApi $googlePlacesApi,
        private LocationRepository $locationRepository,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @return Location[]
     */
    public function getLocationsSearchText(string $text): array
    {
        $locations = $this->locationRepository->searchByText($text);
        if (\count($locations)) {
            return $locations;
        }

        $locations = $this->googlePlacesApi->getPlacesBySearchText($text);
        $externalIds = array_keys($locations);

        $existingLocations = $this->locationRepository->findBy(['externalPlaceId' => $externalIds]);
        $existingLocationsIndexed = [];
        foreach ($existingLocations as $existingLocation) {
            $existingLocationsIndexed[$existingLocation->getExternalPlaceId()] = $existingLocation;
        }

        foreach ($locations as $location) {
            if (\in_array($location->getExternalPlaceId(), array_keys($existingLocationsIndexed), true)) {
                $existingLocationsIndexed[$location->getExternalPlaceId()]->addSearchTag($text);
            } else {
                $this->entityManager->persist($location);
            }
        }

        $this->entityManager->flush();
        return $locations;
    }
}
