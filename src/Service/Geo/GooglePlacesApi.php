<?php

declare(strict_types=1);

namespace App\Service\Geo;

use App\Entity\Location;
use App\Modules\LocationAutocomplete\Model\LocationPredictionsItem;
use App\Repository\AirportIATARepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GooglePlacesApi
{
    private HttpClientInterface $client;
    private string $apiKey;

    private array $googleTypes = ['city' => 'locality',
        'country' => 'country', 'airport' => 'airport', '' => '', 'hotel' => 'lodging', 'museum' => 'museum',
        'railwayStation' => 'train_station', 'busStation' => 'bus_station', 'spot' => '', 'other' => '', 'address' => '',
        'camping' => 'campground', 'restaurant' => 'restaurant', 'bar' => 'bar', 'nightClub' => 'night_club',
        'amusementPark' => 'amusement_park', 'aquarium' => 'aquarium', 'artGallery' => 'art_gallery',
        'bowling' => 'bowling_alley', 'cinema' => 'movie_theater', 'theatre' => '', 'zoo' => 'zoo', 'park' => 'park',
        'spa' => 'spa', 'stadium' => 'stadium', 'playground' => '', 'attraction' => 'tourist_attraction', 'casino' => 'casino',
        'church' => 'church', 'gym' => 'gym', 'store' => 'store', 'drugstore' => 'drugstore', 'rent' => 'car_rental',
        'parking' => 'parking', 'service' => '', 'bank' => 'atm', 'gasStation' => 'gas_station',
        'publicStation' => 'subway_station', 'port' => 'transit_station', 'plusCode' => 'plus_code', 'streetAddress' => 'street_address'];
    private array $localTypes = ['locality' => 'city',
        'country' => 'country', 'airport' => 'airport', '' => '', 'lodging' => 'hotel', 'museum' => 'museum',
        'train_station' => 'railwayStation', 'bus_station' => 'busStation', 'cemetery' => 'other', 'campground' => 'camping',
        'rv_park' => 'camping', 'restaurant' => 'restaurant', 'cafe' => 'restaurant', 'meal_delivery' => 'restaurant',
        'meal_takeaway' => 'restaurant', 'bakery' => 'restaurant', 'bar' => 'bar', 'night_club' => 'nightClub',
        'amusement_park' => 'amusementPark', 'aquarium' => 'aquarium', 'art_gallery' => 'artGallery',
        'bowling_alley' => 'bowling', 'movie_theater' => 'cinema', 'zoo' => 'zoo', 'park' => 'park', 'spa' => 'spa',
        'beauty_salon' => 'spa', 'hair_care' => 'spa', 'stadium' => 'stadium', 'tourist_attraction' => 'attraction',
        'casino' => 'casino', 'church' => 'church', 'mosque' => 'church', 'city_hall' => 'church', 'synagogue' => 'church',
        'hindu_temple' => 'church', 'liquor_store' => 'store', 'pet_store' => 'store', 'book_store' => 'store',
        'clothing_store' => 'store', 'convenience_store' => 'store', 'department_store' => 'store', 'electronics_store' => 'store',
        'shopping_mall' => 'store', 'store' => 'store', 'furniture_store' => 'store', 'supermarket' => 'store',
        'hardware_store' => 'store', 'home_goods_store' => 'store', 'jewelry_store' => 'store', 'bicycle_store' => 'store',
        'car_dealer' => 'store', 'shoe_store' => 'store', 'car_rental' => 'rent', 'real_estate_agency' => 'rent',
        'lawyer' => 'service',  'painter' => 'service', 'car_repair' => 'service', 'plumber' => 'service',
        'physiotherapist' => 'service', 'movie_rental' => 'service', 'veterinary_care' => 'service', 'car_wash' => 'service',
        'laundry' => 'service', 'dentist' => 'service', 'florist' => 'service', 'doctor' => 'service', 'electrician' => 'service',
        'travel_agency' => 'service', 'insurance_agency' => 'service', 'accounting' => 'service', 'moving_company' => 'service',
        'post_office' => 'service', 'locksmith' => 'service', 'library' => 'service','funeral_home' => 'service',
        'hospital' => 'service', 'atm' => 'bank', 'bank' => 'bank', 'gas_station' => 'gasStation',
        'subway_station' => 'publicStation', 'taxi_stand' => 'publicStation', 'primary_school' => 'other',
        'police' => 'other', 'courthouse' => 'other', 'university' => 'other',   'school' => 'other', 'embassy' => 'other',
        'storage' => 'other', 'roofing_contractor' => 'other', 'secondary_school' => 'other', 'fire_station' => 'other',
        'plus_code' => 'plusCode', 'premise' => 'premise', 'street_address' => 'streetAddress'];

    public function __construct(string $googlePlacesApiKey, private AirportIATARepository $airportIATARepository)
    {
        $this->client = HttpClient::create([
            'base_uri' => 'https://maps.googleapis.com/',
        ]);
        $this->apiKey = $googlePlacesApiKey;
    }

    /**
     * @return Location[]|null
     */
    public function getPlacesBySearchText(string $text): ?array
    {
        $response = $this->client->request(Request::METHOD_GET, '/maps/api/place/findplacefromtext/json', [
            'query' => [
                'fields' => 'formatted_address,geometry,name,place_id,types',
                'input' => $text,
                'inputtype' => 'textquery',
                'key' => $this->apiKey,
            ],
        ]);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return null;
        }

        $data = json_decode($response->getContent(), true);

        $locations = [];

        foreach ($data['candidates'] as $row) {
            $location = (new Location())
                ->setName($row['name'])
                ->setAddress($row['formatted_address'])
                ->setExternalPlaceId($row['place_id'])
                ->setLat($row['geometry']['location']['lat'])
                ->setLon($row['geometry']['location']['lng'])
                ->setSearchTags($text)
            ;
            $locations[$row['place_id']] = $location;
        }

        return $locations;
    }

    /**
     * @return LocationPredictionsItem[]|null
     */
    public function getAutocompleteForText (string $text, string $type,string $sessionID): ?array {
        $response = $this->client->request(Request::METHOD_GET, '/maps/api/place/autocomplete/json', [
            'query' => [
                'input' => $text,
                'type' => $this->googleTypes[$type],//'locality',
                'sessiontoken' => $sessionID,
                'key' => $this->apiKey,
                'language' => 'ru'
            ],
        ]);
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return null;
        }
        $data = json_decode($response->getContent(), true);

        $locations = [];
        foreach ($data['predictions'] as $row) {
            $location = (new LocationPredictionsItem(
                "",
                $row['structured_formatting']['main_text'],
                $row['place_id'],
                $row['description']
            ));

            $locations[$row['place_id']] = $location;
        }

        return $locations;
    }

    /**
     * @return string|null
     */
    public function getTimeZone(float $latitude, float $longtitude) : ?string {
        $response = $this->client->request(Request::METHOD_GET, '/maps/api/timezone/json', [
            'query' => [
                'location' => $latitude.' '.$longtitude,
                'timestamp' => (string) time(),
                'key' => $this->apiKey,
            ],
        ]);
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return null;
        }
        $data = json_decode($response->getContent(), true);
        if (isset($data['timeZoneId'])) {
            return $data['timeZoneId'];
        }
        return null;
    }

    /**
     * @return Location|null
     */
    private function getPoliticalLocationByName (string $name, float $latitude, float $longitude, string $type, string $language = 'ru'): ?Location{
        $radius = '50000';
        $response = $this->client->request(Request::METHOD_GET, '/maps/api/place/textsearch/json', [
            'query' => [
                'location' => $latitude.' '.$longitude,
                'type' => $type,
                'language' => $language,
                'query' => $name,
                //'radius' => $radius,
                'key' => $this->apiKey
            ],
        ]);
        //dump($response);
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return null;
        }
        $data = json_decode($response->getContent(), true);
        //dump ($data);
        if (\count($data['results']) > 0) {
            return (new Location())
                ->setExternalPlaceId($data['results'][0]['place_id'])
                ->setType($this->localTypes[$type]);
        } else {
            $locations = $this->getPlacesArrayByCoordinates($latitude,$longitude,$this->localTypes[$type]);
            if (count($locations) > 0) {
                $location = array_pop($locations);
                $location->setType($this->localTypes[$type]);
                return $location;
            }
        }
        return null;
    }

    /**
     * @return Location|null
     */
    public function getPlacesArrayByCoordinates(float $latitude, float $longitude, ?string $type = null): array {

        $queryItems = [
            'latlng' => $latitude.','.$longitude,
            'result_type' => isset($type) ? $this->googleTypes[$type] : "plus_code|premise|street_address",
            'language' => 'ru',
            'key' => $this->apiKey
        ];
        return $this->processGeocodeData($this->getGeocodeData($queryItems));
    }

    /**
     * @return string|null
     */
    public function getPlaceByAddress(string $address): ?Location
    {
        $queryItems = [
            'address' => $address,
            'language' => 'ru',
            'key' => $this->apiKey
        ];
        $locations = $this->processGeocodeData($this->getGeocodeData($queryItems));
        if (count($locations) > 0) {
            $location = array_pop($locations);
            $location->setName($location->getAddress());
            return $this->getPlaceByGoogleID($location->getExternalPlaceId());
        }
        return null;
    }

    private function getGeocodeData(array $queryItems): array {
        $response = $this->client->request(Request::METHOD_GET, '/maps/api/geocode/json', [
            'query' => $queryItems,
        ]);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return [];
        }
        return json_decode($response->getContent(), true);
    }

    private function processGeocodeData (?array $data): array {
        $results = [];
        foreach ($data['results'] as $result) {
            $location = (new Location())
                ->setName($result['address_components'][0]['long_name'])
                ->setAddress($result['formatted_address'])
                ->setExternalPlaceId($result['place_id'])
                ->setLat($result['geometry']['location']['lat'])
                ->setLon($result['geometry']['location']['lng'])
            ;
            foreach ($result['types'] as $type) {
                if (!empty($this->localTypes[$type])) {
                    $location->setType($this->localTypes[$type]);
                }
            }
            foreach ($result['address_components'] as $address_component) {
                foreach ($address_component['types'] as $component_type) {
                    if ($component_type == 'country') {
                        $location->setCountryCode($address_component['short_name']);
                    }
                }
            }
            $results[$location->getType()] = $location;
        }
        //dump();
        return $results;
    }

    private function getTranslationByGoogleID(string $googleID): ?array {
        $response = $this->client->request(Request::METHOD_GET, '/maps/api/place/details/json', [
            'query' => [
                'place_id' => $googleID,
                'key' => $this->apiKey,
                'language' => 'en'
            ],
        ]);
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return null;
        }

        return json_decode($response->getContent(), true);
    }

    /**
     * @return Location|null
     */
    public function getPlaceByGoogleID(string $googleID, string $language = 'ru'): ?Location{
        $response = $this->client->request(Request::METHOD_GET, '/maps/api/place/details/json', [
            'query' => [
                'place_id' => $googleID,
                'key' => $this->apiKey,
                'language' => $language
            ],
        ]);
        //dump ($response);
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return null;
        }
        $data = json_decode($response->getContent(), true);

        //dump($data);
        if (\count($data['result']) > 0) {
            $translationData = $this->getTranslationByGoogleID($googleID);
            $location = (new Location())
                ->setName($data['result']['name'])
                ->setAddress($data['result']['formatted_address'])
                ->setExternalPlaceId($data['result']['place_id'])
                ->setLat($data['result']['geometry']['location']['lat'])
                ->setLon($data['result']['geometry']['location']['lng'])
                ->setInternationalName($translationData['result']['name'])
                ->setInternationalAddress($translationData['result']['formatted_address'])
            ;
            $placeType = '';
            foreach ($data['result']['types'] as $type) {
                if (!empty($this->localTypes[$type])) {
                    $location->setType($this->localTypes[$type]);
                    $placeType = $type;
                }
            }

            if ($placeType == 'airport') { //handle airport IATA binding
                $airportIATA = $this->airportIATARepository->findNearTheCoordinates(
                    $data['result']['geometry']['location']['lat'],
                    $data['result']['geometry']['location']['lng']
                );
                if (isset($airportIATA)) {
                    $location->setCodeIATA($airportIATA->getAirportCode());
                    $location->setCityLocation($this->getPoliticalLocationByName(
                        $airportIATA->getCityInternationalName(),
                        $airportIATA->getCityLatitude(),
                        $airportIATA->getCityLongitude(),
                        'locality'
                    ));
                    $location->setCityLocationIATACode($airportIATA->getCityCode());
                }
                //dump ($airportIATA);
                //dump ($location);
            }
            $this->getPoliticalLocations($location,
                $translationData['result']['address_components'],
                $placeType,
                $data['result']['geometry']['location']['lat'],
                $data['result']['geometry']['location']['lng']);

            if ($location->getCountryLocation() == null || $location->getCityLocation() == null) {
                $this->getPoliticalLocations($location,
                    $data['result']['address_components'],
                    $placeType,
                    $data['result']['geometry']['location']['lat'],
                    $data['result']['geometry']['location']['lng']);
            }
        }

        return $location;
    }

    private function getPoliticalLocations(Location $location,array $addressComponents, $placeType, $latitude, $longitude): void
    {
        foreach ($addressComponents as $address_component) {
            //dump ($address_component);
            foreach ($address_component['types'] as $component_type) {
                if ($component_type == 'locality' && $placeType != 'locality' && $placeType != 'country' && $location->getCityLocation() == null) {
                    $location->setCityLocation($this->getPoliticalLocationByName(
                        $address_component['long_name'],
                        $latitude,
                        $longitude,
                        'locality'
                    ));
                }
                if ($component_type == 'country' && $location->getCountryLocation() == null) {
                    $location->setCountryCode($address_component['short_name']);
                    if ($placeType != 'country') {
                        $location->setCountryLocation($this->getPoliticalLocationByName(
                            $address_component['long_name'],
                            $latitude,
                            $longitude,
                            'country'
                        ));
                    }
                }
            }
            if ($placeType != 'locality' && $placeType != 'country' && $location->getCityLocation() == null) {
                $locations = $this->getPlacesArrayByCoordinates($latitude,$longitude,'city');
                if (count($locations) > 0) {
                    $location->setCityLocation(array_pop($locations));
                }
            }
            if ($placeType != 'country'&& $location->getCountryLocation() == null) {
                $locations = $this->getPlacesArrayByCoordinates($latitude,$longitude,'country');
                if (count($locations) > 0) {
                    $location->setCountryLocation(array_pop($locations));
                }
            }
        }
    }
}
