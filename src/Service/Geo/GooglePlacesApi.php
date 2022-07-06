<?php

declare(strict_types=1);

namespace App\Service\Geo;

use App\Entity\Location;
use App\Modules\LocationAutocomplete\Model\LocationPredictionsItem;
use App\Repository\AirportIATARepository;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GooglePlacesApi
{
    private HttpClientInterface $client;
    private string $apiKey;

    private array $googleTypes = ['city' => 'locality',
        'country' => 'country',
        'airport' => 'airport',
        '' => '',
        'hotel' => 'lodging',
        'museum' => 'museum',
        'railwayStation' => 'train_station',
        'busStation' => 'bus_station',
        'spot' => '',
        'other' => 'cemetery',
        'address' => '',
        'camping' => 'campground',
        'restaurant' => 'restaurant',
        'bar' => 'bar',
        'nightClub' => 'night_club',
        'amusementPark' => 'amusement_park',
        'aquarium' => 'aquarium',
        'artGallery' => 'art_gallery',
        'bowling' => 'bowling_alley',
        'cinema' => 'movie_theater',
        'theatre' => '',
        'zoo' => 'zoo',
        'park' => 'park',
        'spa' => 'spa',
        'stadium' => 'stadium',
        'playground' => '',
        'attraction' => 'tourist_attraction',
        'casino' => 'casino',
        'church' => 'church',
        'gym' => 'gym',
        'store' => 'store',
        'drugstore' => 'drugstore',
        'rent' => 'car_rental',
        'parking' => 'parking',
        'service' => 'lawyer',
        'bank' => 'atm',
        'gasStation' => 'gas_station',
        'publicStation' => 'subway_station'];
    private array $localTypes = ['locality' => 'city',
        'country' => 'country',
        'airport' => 'airport',
        '' => '',
        'lodging' => 'hotel',
        'museum' => 'museum',
        'train_station' => 'railwayStation',
        'bus_station' => 'busStation',
        'cemetery' => 'other',
        'campground' => 'camping',
        'rv_park' => 'camping',
        'restaurant' => 'restaurant',
        'cafe' => 'restaurant',
        'meal_delivery' => 'restaurant',
        'meal_takeaway' => 'restaurant',
        'bakery' => 'restaurant',
        'bar' => 'bar',
        'night_club' => 'nightClub',
        'amusement_park' => 'amusementPark',
        'aquarium' => 'aquarium',
        'art_gallery' => 'artGallery',
        'bowling_alley' => 'bowling',
        'movie_theater' => 'cinema',
        'zoo' => 'zoo',
        'park' => 'park',
        'spa' => 'spa',
        'beauty_salon' => 'spa',
        'hair_care' => 'spa',
        'stadium' => 'stadium',
        'tourist_attraction' => 'attraction',
        'casino' => 'casino',
        'church' => 'church',
        'mosque' => 'church',
        'city_hall' => 'church',
        'synagogue' => 'church',
        'hindu_temple' => 'church',
    ];

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
    public function getPoliticalLocationByName (string $name, float $latitude, float $longtitude, string $type, string $language = 'ru'): ?Location{
        $radius = '50000';
        $response = $this->client->request(Request::METHOD_GET, '/maps/api/place/textsearch/json', [
            'query' => [
                'location' => $latitude.' '.$longtitude,
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
        }
        return null;
    }

    /**
     * @return Location|null
     */
    public function getPlaceByCoordinates(float $latitude, float $longtitude, string $type): ?Location {

        $queryItems = [
            'latlng' => $latitude.','.$longtitude,
            'result_type' => $this->googleTypes[$type],
            'language' => 'ru',
            'key' => $this->apiKey
        ];

        $location = $this->processGeocodeData($this->getGeocodeData($queryItems));

        if (isset($location) && !empty($type)) {
            $location->setType($type);
        }

        return $location;
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
        $location = $this->processGeocodeData($this->getGeocodeData($queryItems));
        if (isset($location)) {
            $location->setName($location->getAddress());
            return $this->getPlaceByGoogleID($location->getExternalPlaceId());
        }
        return null;
    }

    private function getGeocodeData(array $queryItems): ?array {
        $response = $this->client->request(Request::METHOD_GET, '/maps/api/geocode/json', [
            'query' => $queryItems,
        ]);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return null;
        }
        return json_decode($response->getContent(), true);
    }

    private function processGeocodeData (?array $data): ?Location {
        if (\count($data['results']) > 0) {
            $location = (new Location())
                ->setName($data['results'][0]['address_components'][0]['long_name'])
                ->setAddress($data['results'][0]['formatted_address'])
                ->setExternalPlaceId($data['results'][0]['place_id'])
                ->setLat($data['results'][0]['geometry']['location']['lat'])
                ->setLon($data['results'][0]['geometry']['location']['lng'])
            ;
            foreach ($data['results'][0]['types'] as $type) {
                if (!empty($this->localTypes[$type])) {
                    $location->setType($this->localTypes[$type]);
                }
            }
            foreach ($data['results'][0]['address_components'] as $address_component) {
                foreach ($address_component['types'] as $component_type) {
                    if ($component_type == 'country') {
                        $location->setCountryCode($address_component['short_name']);
                    }
                }
            }
            return $location;
        }
        return null;
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
                        $airportIATA->getCityLongtitude(),
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
        }
    }
}
