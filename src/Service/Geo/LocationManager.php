<?php

declare(strict_types=1);

namespace App\Service\Geo;

use App\Entity\Location;
use App\Modules\UserProfile\Model\UserProfileResponse;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class LocationManager
{
    public function __construct(
        private GooglePlacesApi $googlePlacesApi,
        private LocationRepository $locationRepository,
        //private EntityManagerInterface $entityManager
    ) {}

    /**
     * @return Location[]
     */
    public function getLocationsSearchText(string $text, string $sessionID): array
    {
        $mgLocations = $this->locationRepository->searchByText($text);
        /*if (\count($locations)) {
            return $locations;
        }*/

        $googleLocations = $this->googlePlacesApi->getPlacesBySearchText($text);
        $externalIds = array_keys($googleLocations);

        $indexedLocations = [];
        foreach ($mgLocations as $location) {
            $indexedLocations[$location->getExternalPlaceId()] = $location;
        }

        foreach ($externalIds as $externalId) {
            if (isset($indexedLocations[$externalId])){
                unset($googleLocations[$externalId]);
            }
        }

        $locations = array_merge($mgLocations, array_values($googleLocations));

        //dump($mgLocations);
        //dump($googleLocations);
        //dump($locations);
        /*$existingLocations = $this->locationRepository->findBy(['externalPlaceId' => $externalIds]);
        $existingLocationsIndexed = [];
        foreach ($existingLocations as $existingLocation) {
            $existingLocationsIndexed[$existingLocation->getExternalPlaceId()] = $existingLocation;
        }

        foreach ($googleLocations as $location) {
            if (\in_array($location->getExternalPlaceId(), array_keys($existingLocationsIndexed), true)) {
                $existingLocationsIndexed[$location->getExternalPlaceId()]->addSearchTag($text);
            } else {
                $this->entityManager->persist($location);
            }
        }

        $this->entityManager->flush();*/
        return $locations;
    }

    /*public function addNewLocationByExternalID(string $data) {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $requestData = new UserProfileResponse(null);
        $serializer->deserialize(
            $data,
            UserProfileResponse::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE=>$requestData]
        );
        //Get single user from items array
        if (count($requestData->items) > 0){
            $userItem = array_pop($requestData->items);
        }

        if (\count($this->locationRepository->findBy(array('externalPlaceId' => $externalID))) > 0){
            return null;
        }

        if (isset($location)){

            $this->entityManager->persist($location);
            $this->entityManager->flush();
        }

    }*/

}
