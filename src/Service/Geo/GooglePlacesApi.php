<?php

declare(strict_types=1);

namespace App\Service\Geo;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GooglePlacesApi
{
    private HttpClientInterface $client;
    private string $apiKey;

    public function __construct()
    {
        $this->client = HttpClient::create([
            'base_uri' => 'https://maps.googleapis.com/',
        ]);
        $this->apiKey = 'AIzaSyDVWmwtlhY4WLkK8NY13N_NQWtzGHePLVA'; // todo to config
    }

    public function getPlacesBySearchText(string $text): ?array
    {
        $response = $this->client->request(Request::METHOD_GET, '/maps/api/place/findplacefromtext/json', [
            'fields' => 'formatted_address,geometry,name,place_id',
            'input' => $text,
            'inputtype' => 'textquery',
            'key' => $this->apiKey,
        ]);
        if ($response->getStatusCode() === Response::HTTP_OK) {
            return json_decode($response->getContent(), true);
        }

        return null;
    }
}
