<?php

declare(strict_types=1);

namespace App\Service\Geo;

use App\Entity\Location;
use App\Modules\LocationAutocomplete\Model\LocationPredictionsItem;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GooglePlacesApi
{
    private HttpClientInterface $client;
    private string $apiKey;

    private array $googleTypes = ['city' => 'locality', 'country' => 'country', 'airport' => 'airport'];
    private array $localTypes = ['locality' => 'city', 'country' => 'country', 'airport' => 'airport'];

    public function __construct(string $googlePlacesApiKey)
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
        print("test");

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
    public function getPoliticalLocationByName (string $name, float $latitude, float $longtitude, string $type): ?Location{
        $radius = '1000';
        $response = $this->client->request(Request::METHOD_GET, '/maps/api/place/textsearch/json', [
            'query' => [
                'location' => $latitude.' '.$longtitude,
                'type' => $type,
                'language' => 'ru',
                'query' => $name,
                'radius' => $radius,
                'key' => $this->apiKey
            ],
        ]);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return null;
        }
        $data = json_decode($response->getContent(), true);
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

        $response = $this->client->request(Request::METHOD_GET, '/maps/api/geocode/json', [
            'query' => [
                'latlng' => $latitude.','.$longtitude,
                'result_type' => $this->googleTypes[$type],
                'language' => 'ru',
                'key' => $this->apiKey
            ],
        ]);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return null;
        }
        $data = json_decode($response->getContent(), true);

        if (\count($data['results']) > 0) {
            $location = (new Location())
                ->setName($data['results'][0]['address_components'][0]['long_name'])
                ->setAddress($data['results'][0]['formatted_address'])
                ->setExternalPlaceId($data['results'][0]['place_id'])
                ->setLat($data['results'][0]['geometry']['location']['lat'])
                ->setLon($data['results'][0]['geometry']['location']['lng'])
                ->setType($type)
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
            $location = (new Location())
                ->setName($data['result']['name'])
                ->setAddress($data['result']['formatted_address'])
                ->setExternalPlaceId($data['result']['place_id'])
                ->setLat($data['result']['geometry']['location']['lat'])
                ->setLon($data['result']['geometry']['location']['lng'])
            ;
            $placeType = '';
            foreach ($data['result']['types'] as $type) {
                if (!empty($this->localTypes[$type])) {
                    $location->setType($this->localTypes[$type]);
                    $placeType = $type;
                }
            }
            foreach ($data['result']['address_components'] as $address_component) {
                foreach ($address_component['types'] as $component_type) {
                    if ($component_type == 'locality' && $placeType != 'locality' && $placeType != 'country') {
                        $location->setCityLocation($this->getPoliticalLocationByName(
                            $address_component['long_name'],
                            $data['result']['geometry']['location']['lat'],
                            $data['result']['geometry']['location']['lng'],
                            'locality'
                        ));
                    }
                    if ($component_type == 'country') {
                        $location->setCountryCode($address_component['short_name']);
                        if ($placeType != 'country') {
                            $location->setCountryLocation($this->getPoliticalLocationByName(
                                $address_component['long_name'],
                                $data['result']['geometry']['location']['lat'],
                                $data['result']['geometry']['location']['lng'],
                                'country'
                            ));
                        }
                    }
                }
            }
        }

        return $location;
    }
}
