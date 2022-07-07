<?php

namespace App\Helpers;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Serializer\Annotation\Groups;

class NameTranslation {
    /**
     * @var string
     */
    public ?string $en;

    /**
     * @param string $en
     */
    public function __construct(?string $en)
    {
        $this->en = $en;
    }

}

class Coordinates {
    public float $lat;
    public float $lon;
}

class Airports {
    public array $cities;
}

class AirportIATA
{
    /**
     * @var string
     */
    #[Groups(['airportIATA'])]
    public string $cityCode;

    /**
     * @var string
     */
    #[Groups(['airportIATA'])]
    public string $countryCode;

    /**
     * @var NameTranslation[]
     */
    #[Groups(['airportIATA'])]
    public array $nameTranslations;

    /**
     * @var string
     */
    #[Groups(['airportIATA'])]
    public string $timeZone;

    /**
     * @var string
     */
    #[Groups(['airportIATA'])]
    public string $flightable;

    /**
     * @var Coordinates[]
     */
    #[Groups(['airportIATA'])]
    public array $coordinates;

    /**
     * @var string|null
     */
    #[Groups(['airportIATA'])]
    public ?string $name;

    /**
     * @var string
     */
    #[Groups(['airportIATA'])]
    public string $code;

    /**
     * @var string
     */
    #[Groups(['airportIATA'])]
    public string $iataType;

}

class CityIATA
{
    #[Groups(['cityIATA'])]
    public string $code;

    #[Groups(['cityIATA'])]
    public string $countryCode;

    /**
     * @var NameTranslation[]
     */
    #[Groups(['airportIATA'])]
    public array $nameTranslations;

    /**
     * @var string
     */
    #[Groups(['airportIATA'])]
    public string $timeZone;

    /**
     * @var string
     */
    #[Groups(['airportIATA'])]
    public string $flightable;

    /**
     * @var Coordinates[]
     */
    #[Groups(['airportIATA'])]
    public array $coordinates;

    /**
     * @var string|null
     */
    #[Groups(['airportIATA'])]
    public ?string $name;

    /**
     * @var array|null
     */
    #[Groups(['airportIATA'])]
    public ?array $cases;
}

class AirlineIATA {
    #[Groups(['airlineIATA'])]
    public string $code;

    #[Groups(['airlineIATA'])]
    public ?string $name;

    #[Groups(['airlineIATA'])]
    public ?string $isLowcost;

    /**
     * @var NameTranslation[]
     */
    #[Groups(['airlineIATA'])]
    public array $nameTranslations;

}

class TravelpayoutsIATAParser
{
    private HttpClientInterface $client;
    private Serializer $serializer;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->client = HttpClient::create([
            'base_uri' => 'https://api.travelpayouts.com/',
        ]);

        $encoders = [new JsonEncoder()];

        $normalizers = [
            new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter(), NULL, null),
            new ArrayDenormalizer(),
            new GetSetMethodNormalizer()
        ];

        $this->serializer = new Serializer($normalizers, $encoders);

        //$this->airportIATARepository = new AirportIATARepository();
    }
    public function parse () {
        $airportsResponse = $this->client->request(Request::METHOD_GET, '/data/ru/airports.json');

        if ($airportsResponse->getStatusCode() !== Response::HTTP_OK) {
            return null;
        }
        $airportsData = $airportsResponse->getContent();
        //dump($airportData);
        //$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        //$serializer = new Serializer([new ArrayDenormalizer(), new ObjectNormalizer($classMetadataFactory, NULL, NULL, new ReflectionExtractor())], [new JsonEncoder()]);
        $airports = $this->serializer->deserialize(
            $airportsData,
            'App\Helpers\AirportIATA[]',//'AppHelpersAirportIATA[]'
            'json',
            ["groups" => "airportsIATA"]
        );
        //dump ($requestData[0]->nameTranslations['en']);

        $citiesResponse = $this->client->request(Request::METHOD_GET, '/data/ru/cities.json');

        if ($citiesResponse->getStatusCode() !== Response::HTTP_OK) {
            return null;
        }
        $citiesData = $citiesResponse->getContent();
        //dump($airportData);
        //$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        //$serializer = new Serializer([new ArrayDenormalizer(), new ObjectNormalizer($classMetadataFactory, NULL, NULL, new ReflectionExtractor())], [new JsonEncoder()]);
        $cities = $this->serializer->deserialize(
            $citiesData,
            'App\Helpers\CityIATA[]',//'AppHelpersAirportIATA[]'
            'json',
            ["groups" => "citiesIATA"]
        );
        //dump ($cities[0]->nameTranslations['en']);
        $citiesIndexed = [];
        foreach ($cities as $city) {
            $citiesIndexed[$city->code] = $city;
        }

        $airportsIATA = [];
        foreach ($airports as $airport) {
            $airportIATA = new \App\Entity\AirportIATA();
            $airportIATA->setName($airport->name)
                ->setCountryCode($airport->countryCode)
                ->setTimeZone($airport->timeZone)
                ->setAirportCode($airport->code)
                ->setInternationalName($airport->nameTranslations['en'])
                ->setLatitude($airport->coordinates['lat'])
                ->setLongitude($airport->coordinates['lon'])
                ->setCityCode($airport->cityCode)
                ->setType($airport->iataType);
            if (isset($airport->cityCode) && $airport->cityCode != ""){
                $airportIATA->setCityName($citiesIndexed[$airport->cityCode]->name)
                    ->setCityInternationalName($citiesIndexed[$airport->cityCode]->nameTranslations['en'])
                    ->setCityLatitude($citiesIndexed[$airport->cityCode]->coordinates['lat'])
                    ->setCityLongitude($citiesIndexed[$airport->cityCode]->coordinates['lon']);
            }
            //$airportsIATA[] = $airportIATA;
            $this->entityManager->persist($airportIATA);
        }
        //dump(count($airports).' '.count($airportsIATA));
        $this->entityManager->flush();

        //$data = json_decode($response->getContent(), true);
    }

    public function parseAirlines () {
        $airlinesResponse = $this->client->request(Request::METHOD_GET, '/data/ru/airlines.json');

        if ($airlinesResponse->getStatusCode() !== Response::HTTP_OK) {
            return null;
        }
        $airlinesData = $airlinesResponse->getContent();
        $airlines = $this->serializer->deserialize(
            $airlinesData,
            'App\Helpers\AirlineIATA[]',//'AppHelpersAirportIATA[]'
            'json',
            ["groups" => "airlinesIATA"]
        );
        //dump ($requestData[0]->nameTranslations['en']);

        foreach ($airlines as $airline) {
            $airlineIATA = new \App\Entity\Airline();
            $airlineIATA->setName($airline->name)
                ->setCode($airline->code)
                ->setInternationalName($airline->nameTranslations['en']);
            $this->entityManager->persist($airlineIATA);
        }

        $this->entityManager->flush();

        //$data = json_decode($response->getContent(), true);
    }
}