<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Controller\JsonResponseTrait;
use App\Helpers\TravelpayoutsIATAParser;
use App\Modules\LocationAutocomplete\LocationAutocompleteService;
use App\Modules\LocationDetails\LocationDetailsService;
use PhpParser\Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PlaceController extends AbstractController
{
    use JsonResponseTrait;

    #[Route('/place/autocomplete', name: 'api_v1_place_autocomplete')]
    public function autocomplete(Request $request, NormalizerInterface $normalizer, LocationAutocompleteService $autocompleteService): JsonResponse
    {
        $text = $request->query->get('query');
        $type = $request->query->get('type') != null ? $request->query->get('type') : "";
        $sessionID = $request->query->get('session');
        if (!$text) {
            return $this->errorResponse(error: 'Text is required', statusCode: Response::HTTP_BAD_REQUEST);
        }

        $predictions = $autocompleteService->getLocationsSearchText($text, $sessionID, $type);
        $data = $normalizer->normalize($predictions, 'json', ['groups' => 'location_predictions']);

        return $this->successResponse($data);
    }

    #[Route('/place/details', name: 'api_v1_place_get_by_id')]
    public function getPlace(Request $request, LocationDetailsService $detailsService, NormalizerInterface $normalizer): JsonResponse
    {
        $objID = $request->query->get('id');
        $externalID = $request->query->get('externalID');
        $type = $request->query->get('type');
        $latitude = $request->query->get('lat');
        $longitude = $request->query->get('lon');
        $name = $request->query->get('name');

        if (isset($objID)){
            $location = $detailsService->getPlaceDetails($objID);
        } elseif (isset($externalID)){
            $location = $detailsService->getPlaceDetailsByExternalID($externalID, $type);
        } elseif (isset($latitude) && isset($longitude) && isset($name)){
            $location = $detailsService->prepareLocationWithCoordinates((float)$latitude,(float)$longitude,$name, $type);
        } else {
            return $this->errorResponse(error: 'ID is required', statusCode: Response::HTTP_BAD_REQUEST);
        }
        $data = $normalizer->normalize($location, 'json');

        return $this->successResponse($data);
    }

    #[Route('/place/address', name: 'api_v1_place_get_by_address')]
    public function getPlaceByAddress (Request $request, LocationDetailsService $detailsService, NormalizerInterface $normalizer): JsonResponse
    {
        $address = $request->query->get('text');

        if (isset($address)) {
            $location = $detailsService->getPlaceDetailsByAddress($address);
        } else {
            return $this->errorResponse(error: 'ID is required', statusCode: Response::HTTP_BAD_REQUEST);
        }

        $data = $normalizer->normalize($location, 'json', ['groups' => 'location_details']);

        return $this->successResponse(['location' => $data]);
    }

    #[Route('/place/coordinates', name: 'api_v1_place_get_by_coordinates')]
    public function getPlaceByCoordinates (Request $request, LocationDetailsService $detailsService, NormalizerInterface $normalizer): JsonResponse
    {
        $latitude = $request->query->get('lat');
        $longitude = $request->query->get('lon');
        $name = $request->query->get('name');
        $type = $request->query->get('type');

        if (isset($latitude) && isset($longitude) && isset($name)){
            $location = $detailsService->prepareLocationWithCoordinates((float)$latitude,(float)$longitude,$name, $type);
        } else {
            return $this->errorResponse(error: 'More parameters is required', statusCode: Response::HTTP_BAD_REQUEST);
        }

        $data = $normalizer->normalize($location, 'json', ['groups' => 'location_details']);

        return $this->successResponse(['location' => $data]);
    }

    #[Route('/place/parseIATA', name: 'api_v1_parse_iata')]
    public function parseIATA (Request $request, TravelpayoutsIATAParser $travelpayoutsIATAParser) : JsonResponse
    {
        if  ($request->query->get('airports') !== null) {
            $travelpayoutsIATAParser->parse();
        }
        if  ($request->query->get('airlines') !== null) {
            $travelpayoutsIATAParser->parseAirlines();
        }

        return $this->successResponse(['status' => 'OK']);
    }
}