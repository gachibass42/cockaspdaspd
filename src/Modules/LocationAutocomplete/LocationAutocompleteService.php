<?php
declare(strict_types=1);
namespace App\Modules\LocationAutocomplete;

use App\Modules\LocationAutocomplete\Model\LocationPredictionsItem;
use App\Service\Geo\GooglePlacesApi;

class LocationAutocompleteService
{
    public function __construct(
        private GooglePlacesApi $googlePlacesApi,
        private LocationAutocompleteRepository $autocompleteRepository,
    ) {}

    /**
     * @return LocationPredictionsItem[]
     */
    public function getLocationsSearchText(string $text, ?string $sessionID, ?string $type = ""): array
    {
        $mgLocations = $this->autocompleteRepository->searchByText($text);

        $googleLocations = $this->googlePlacesApi->getAutocompleteForText($text, $type,$sessionID);
        $externalIds = array_keys($googleLocations);

        $indexedLocations = [];
        foreach ($mgLocations as $location) {
            $indexedLocations[$location->getExternalId()] = $location;
        }

        foreach ($externalIds as $externalId) {
            if (isset($indexedLocations[$externalId])){
                unset($googleLocations[$externalId]);
            }
        }

        return array_merge($mgLocations, array_values($googleLocations));

    }
}