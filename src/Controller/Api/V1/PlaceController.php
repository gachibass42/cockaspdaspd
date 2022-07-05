<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Helpers\TravelpayoutsIATAParser;
use App\Modules\LocationAutocomplete\LocationAutocompleteService;
use App\Modules\LocationDetails\LocationDetailsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PlaceController extends AbstractController
{
    #[Route('/place/autocomplete', name: 'api_v1_place_autocomplete')]
    public function autocomplete(Request $request, NormalizerInterface $normalizer, LocationAutocompleteService $autocompleteService): JsonResponse
    {
        $text = $request->query->get('query');
        $type = $request->query->get('type');
        $sessionID = $request->query->get('session');
        if (!$text) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Text is required');
        }

        $predictions = $autocompleteService->getLocationsSearchText($text, $type, $sessionID);
        $data = $normalizer->normalize($predictions, 'json', ['groups' => 'location_predictions']);
        return new JsonResponse(['items' => $data]);
    }

    /*#[Route('/place/search', name: 'api_v1_place_search')]
    public function search(Request $request, LocationManager $locationManager, NormalizerInterface $normalizer): JsonResponse
    {
        $text = $request->query->get('query');
        $sessionID = $request->query->get('session');
        if (!$text) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Text is required');
        }

        $locations = $locationManager->getLocationsSearchText($text, $sessionID);
        $data = $normalizer->normalize($locations, 'json', ['groups' => 'location_details']);

        return new JsonResponse(['items' => $data]);
    }*/

    #[Route('/place/text', name: 'api_v1_place_get')]
    public function getPlace(Request $request, LocationDetailsService $detailsService, NormalizerInterface $normalizer): JsonResponse
    {
        $objID = $request->query->get('id');
        $externalID = $request->query->get('externalID');

        if (isset($objID)){
            $location = $detailsService->getPlaceDetails($objID);
        } elseif (isset($externalID)){
            $location = $detailsService->getPlaceDetailsByExternalID($externalID);
        } else {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'ID is required');
        }

        //$data = $normalizer->normalize($location, 'json', ['groups' => 'location_details']);

        return $this->json($location);//new JsonResponse(['items' => $data]);
    }

    #[Route('/place/address', name: 'api_v1_place_get')]
    public function getPlaceByAddress (Request $request, LocationDetailsService $detailsService, NormalizerInterface $normalizer): JsonResponse
    {
        $objID = $request->query->get('id');
        $externalID = $request->query->get('externalID');

        if (isset($objID)){
            $location = $detailsService->getPlaceDetails($objID);
        } elseif (isset($externalID)){
            $location = $detailsService->getPlaceDetailsByExternalID($externalID);
        } else {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'ID is required');
        }

        //$data = $normalizer->normalize($location, 'json', ['groups' => 'location_details']);

        return $this->json($location);//new JsonResponse(['items' => $data]);
    }

    #[Route('/place/coordinates', name: 'api_v1_place_get')]
    public function getPlaceByCoordinates (Request $request, LocationDetailsService $detailsService, NormalizerInterface $normalizer): JsonResponse
    {
        $objID = $request->query->get('id');
        $externalID = $request->query->get('externalID');

        if (isset($objID)){
            $location = $detailsService->getPlaceDetails($objID);
        } elseif (isset($externalID)){
            $location = $detailsService->getPlaceDetailsByExternalID($externalID);
        } else {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'ID is required');
        }

        //$data = $normalizer->normalize($location, 'json', ['groups' => 'location_details']);

        return $this->json($location);//new JsonResponse(['items' => $data]);
    }

    #[Route('place/add', name: 'api_v1_add_new_place')]
    public function addNewPlace (Request $request, LocationDetailsService $detailsService, NormalizerInterface $normalizer)
    {

        //$this->json($this->userProfileService->updateUserProfile($request->getContent(),$this->getAuthToken($request)));
        $detailsService->addNewLocationFromExternal($request->getContent());
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

        return $this->json('{"status":"OK"}');
    }
}
