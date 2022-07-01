<?php
declare(strict_types=1);
namespace App\Modules\LocationDetails;

use App\Entity\Location;
use App\Modules\LocationDetails\Model\LocationDetailsItem;
use App\Modules\LocationDetails\Model\LocationDetailsResponse;
use App\Repository\LocationRepository;
use App\Service\Geo\GooglePlacesApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class LocationDetailsService
{
    public function __construct(
        private GooglePlacesApi $googlePlacesApi,
        private LocationRepository $locationRepository,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @param string $externalID
     * @return Location|null
     */
    private function getLocationByExternalID (string $externalID): ?Location {
        $location = $this->googlePlacesApi->getPlaceByGoogleID($externalID);
        //$internationalName = $this->googlePlacesApi->getInternationalNameByGoogleID($externalID);//$this->googlePlacesApi->getPlaceByGoogleID($externalID,'en');
        if (isset($location)){
            $lat = $location->getLat(); $lon = $location->getLon();
            $location->setTimeZone($this->googlePlacesApi->getTimeZone($lat,$lon));
            /*if (isset($internationalLocation)) {
                $location->setInternationalName($internationalName['name']);
                $location->setInternationalAddress($internationalName['address']);
            }*/
        }

        $cityLocation = $location->getCityLocation(); $countryLocation = null; $type = $location->getType();
        //dump ($location, $type);
        if ($type != "country"){ //fill the countryLocation if new place is not country
            $countryLocation = $this->getRegion($location->getCountryLocation(),'country', $location->getExternalPlaceId());
            if ($type != 'city' && !empty($cityLocation)) { //fill the cityLocation if new place is not city
                $cityLocation = $this->getRegion($cityLocation,'city', $location->getExternalPlaceId());
            }
        }
        if ($type == 'airport') {
            $cityLocation->setCodeIATA($location->getCityLocationIATACode());
        }
        $location->setCityLocation($cityLocation);
        $location->setCountryLocation($countryLocation);

        return $location;
    }


    /**
     * @param string $externalID
     * @param string $type
     * @return LocationDetailsResponse|null
     */
    public function getPlaceDetailsByExternalID (string $externalID, string $type = ""): ?LocationDetailsResponse {
        $location = $this->getLocationByExternalID($externalID);

        $items = [];

        if (isset($location)){
            $items[0] = new LocationDetailsItem(
                "",
                $location->getName(),
                $location->getLat(),
                $location->getLon(),
                $location->getAddress(),
                $location->getTimeZone(),
                $location->getCodeIATA(),
                $location->getCountryCode(),
                $location->getExternalPlaceId(),
                $location->getSearchTags(),
                $location->getInternationalName(),
                $location->getInternationalAddress(),
                $location->getCityLocation(),
                $location->getCountryLocation(),
                (!empty($location->getType()) ? $location->getType() : $type)
            );
        }
        return new LocationDetailsResponse($items);
    }

    /**
     * @return LocationDetailsResponse|null
     */
    public function getPlaceDetails (string $objID): ?LocationDetailsResponse {
        $location = $this->entityManager->find(Location::class,$objID);
        $items = [];
        if (isset($location)){
            $items[0] = new LocationDetailsItem(
                $location->getObjID(),
                $location->getName(),
                $location->getLat(),
                $location->getLon(),
                $location->getAddress(),
                $location->getTimeZone(),
                $location->getCodeIATA(),
                $location->getCountryCode(),
                $location->getExternalPlaceId(),
                $location->getSearchTags(),
                $location->getInternationalName(),
                $location->getInternationalAddress(),
                $location->getCityLocation(),
                $location->getCountryLocation(),
                $location->getType()
            );
        }
        return new LocationDetailsResponse($items);
    }

    public function addNewLocationFromExternal(string $data) {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $requestData = new LocationDetailsResponse(null);
        $serializer->deserialize(
            $data,
            LocationDetailsResponse::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE=>$requestData]
        );
        //Get single user from items array
        if (count($requestData->items) > 0){
            $locationItem = array_pop($requestData->items);
        }

        if (\count($this->locationRepository->findBy(array('externalPlaceId' => $locationItem->getExternalPlaceId()))) > 0){
            return null;
        }

        $countryLocation = $this->locationRepository->findBy(array('objID' => $locationItem->getCountryLocation()->getObjID()));
        $cityLocation = $this->locationRepository->findBy(array('objID' => $locationItem->getCityLocation()->getObjID()));

        if (\count($countryLocation) < 1 && !empty($locationItem->getCountryLocation()->getObjID())) {
            $this->entityManager->persist($locationItem->getCountryLocation());
            $this->entityManager->flush();
        }

        if (\count($cityLocation) < 1 && !empty($locationItem->getCityLocation()->getObjID())) {
            $this->entityManager->persist($locationItem->getCityLocation());
            $this->entityManager->flush();
        }

        $location = new Location();

        $location->setObjID($locationItem->getObjID());
        $location->setTimeZone($locationItem->getTimeZone());
        $location->setType($locationItem->getType());
        $location->setAddress($locationItem->getAddress());
        $location->setCodeIATA($locationItem->getCodeIATA());
        $location->setExternalPlaceId($locationItem->getExternalPlaceId());
        $location->setLat($locationItem->getLat());
        $location->setLon($locationItem->getLon());
        $location->setName($locationItem->getName());
        $location->setCityLocation($locationItem->getCityLocation());
        $location->setCountryLocation($locationItem->getCountryLocation());
        /*$location->setObjID($locationItem['objID']);
        $location->setTimeZone($locationItem['timeZone']);
        $location->setType($locationItem['type']);
        $location->setAddress($locationItem['address']);
        $location->setCodeIATA($locationItem['codeIATA']);
        $location->setExternalPlaceId($locationItem['externalID']);
        $location->setLat((float)$locationItem['latitude']);
        $location->setLon((float)$locationItem['longtitude']);
        $location->setName($locationItem['name']);*/

        if (!empty($location->getObjID())){
            $this->entityManager->persist($location);
            $this->entityManager->flush();
        }

    }

    private function getRegion (Location $location, string $type, $selfReference): ?Location {
        //$location = $this->googlePlacesApi->getPlaceByCoordinates($lat,$lon,$type);
        //dump ($location);
        if ($location->getExternalPlaceId() == $selfReference) {
            return null;
        }
        $dbLocation = $this->locationRepository->findBy(['externalPlaceId' => $location->getExternalPlaceId()]);
        //dump ($location);
        //dump($dbLocation);
        if (\count($dbLocation) > 0) {
            $location = array_pop($dbLocation);
        } else {
            $location = $this->getLocationByExternalID($location->getExternalPlaceId());
            $location->setObjID("");
        }
        return $location;
    }
}