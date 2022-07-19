<?php

namespace App\Modules\Syncer;

use App\Entity\Location;
use App\Entity\Milestone;
use App\Entity\Trip;
use App\Helpers\FileManager\FileManagerService;
use App\Modules\Syncer\Model\SyncResponseList;
use App\Modules\UserProfile\UserRepository;
use App\Repository\CheckListRepository;
use App\Repository\CommentRepository;
use App\Repository\LocationRepository;
use App\Repository\MilestoneRepository;
use App\Repository\TripRepository;

class SyncerService
{
    private ?\DateTime $lastSuccessfulSyncDate;
    private ?string $sessionID;
    private array $trips = [];
    private array $milestones = [];
    private array $countries = [];
    private array $cities = [];
    private array $locations = [];
    private array $checklists = [];
    private array $corpses = [];
    private array $comments = [];

    public function __construct(private FileManagerService $fileManagerService,
                                private LocationRepository $locationRepository,
                                private UserRepository $userRepository,
                                private MilestoneRepository $milestoneRepository,
                                private TripRepository $tripRepository,
                                private CommentRepository $commentRepository,
                                private CheckListRepository $checkListRepository)
    {
    }

    private function getArrayValue(string $keyname, array &$object): mixed {
        return $object[$keyname] ?? null;
    }

    private function saveLocations(?string $locationType) {
        $locations = $this->locations;
        switch ($locationType) {
            case "country":
                $locations = $this->countries;
                break;
            case "city":
                $locations = $this->cities;
                break;
        }
        //dump ($locations);
        //$repository = new LocationRepository();
        foreach ($locations as $location) {
            $dbLocation = $this->locationRepository->findOneBy(["objID" => $location["objID"]]);
            if (isset($dbLocation)) {
                //dump($dbLocation);
            } else {
                $dbLocation = new Location();
                $dbLocation->setObjID($location["objID"])
                    ->setLon($location["longitude"])
                    ->setLat($location["latitude"])
                    ->setCountryCode($location["countryCode"])
                    ->setExternalPlaceId($location["externalID"] ?? null)
                    ->setType($location["type"] ?? null)
                    ->setName($location["name"] ?? null)
                    ->setCodeIATA($location["codeIATA"] ?? null)
                    ->setAddress($location["address"] ?? null)
                    ->setInternationalAddress($location["internationalAddress"] ?? null)
                    ->setInternationalName($location["internationalName"] ?? null)
                    ->setTimeZone($location["timeZoneIdentifier"] ?? null)
                    ->setDescription($location["descriptionLocation"] ?? null)
                    ->setPhoneNumber($location['phoneNumber'] ?? null)
                    ->setWebsite($location["website"] ?? null);
                if (isset($location["countryLocationID"])){
                    $countryLocation = $this->locationRepository->findOneBy(["objID" => $location["countryLocationID"]]);
                    if (isset($countryLocation)){
                        $dbLocation->setCountryLocation($countryLocation);
                    }
                    if (isset($location["cityLocationID"])) {
                        $cityLocation = $this->locationRepository->findOneBy(["objID" => $location["cityLocationID"]]);
                        if (isset($cityLocation)) {
                            $dbLocation->setCityLocation($cityLocation);
                        }
                    }
                }
                if (isset($location["ownerID"])) {
                    $owner = $this->userRepository->findOneBy(["id" => (int)$location["ownerID"]]);
                    if (isset($owner)) {
                        $dbLocation->setOwner($owner);
                    }
                }
            }
            $this->locationRepository->save($dbLocation);
        }
    }

    private function saveTrips(): void
    {
        foreach ($this->trips as $trip) {
            $dbTrip = $this->tripRepository->findOneBy(["objID" => $trip["objID"]]);
            if (isset($dbTrip)) {
                //dump($dbTrip);
            } else {
                $dbTrip = new Trip();
                $dbTrip->setObjID($trip["objID"])
                    ->setSyncDate(new \DateTime())
                    ->setName($trip["name"])
                    ->setDuration($trip["duration"] ?? null)
                    ->setStartDate(\DateTime::createFromFormat('U',$trip["startDate"]))
                    ->setEndDate(\DateTime::createFromFormat('U',$trip["endDate"]))
                    ->setLocked($trip["locked"])
                    ->setMilestonesIDs($trip["milestonesIDs"])
                    ->setTags($trip["tags"] ?? null)
                    ->setTripDescription($trip["tripDescription"] ?? null)
                    ->setVisibility($trip["visibility"]);
                if (isset($trip["ownerID"])) {
                    $owner = $this->userRepository->findOneBy(["id" => (int)$trip["ownerID"]]);
                    if (isset($owner)) {
                        $dbTrip->setOwner($owner);
                    }
                }
                if (count($trip["images"] ?? [])){
                    foreach ($trip["images"] as $fileName => $imageData) {
                        if ($this->fileManagerService->saveImage($imageData,$fileName)) {
                            $dbTrip->setMainImage($fileName);
                        }
                    }
                }

            }
            $this->tripRepository->save($dbTrip);
        }
    }

    private function saveMilestones(): void
    {
        foreach ($this->milestones as $milestone) {
            $dbMilestone = $this->milestoneRepository->findOneBy(["objID" => $milestone["objID"]]);
            if (isset($dbMilestone)) {
                //dump($dbMilestone);
            } else {
                $dbMilestone = new Milestone();
                $dbMilestone->setObjID($milestone["objID"])
                    ->setSyncDate(new \DateTime())
                    ->setName($milestone["name"])
                    ->setLocationID($milestone["locationID"] ?? null)
                    ->setDuration($milestone["duration"] ?? null)
                    ->setDate(\DateTime::createFromFormat('U',$milestone["date"]))
                    ->setType($milestone["type"])
                    ->setMilestoneOrder($milestone["order"] ?? null)
                    ->setJourneyNumber($milestone["journeyNumber"] ?? null)
                    ->setSeat($milestone["seat"] ?? null)
                    ->setVagon($milestone["vagon"] ?? null)
                    ->setClassType($milestone["classType"] ?? null)
                    ->setTerminal($milestone["terminal"] ?? null)
                    ->setDistance($milestone["distance"] ?? null)
                    ->setAircraft($milestone["aircraft"] ?? null)
                    ->setTags($milestone["tags"] ?? null)
                    ->setMilestoneDescription($milestone["milestoneDescription"] ?? null)
                    ->setAddress($milestone["address"] ?? null)
                    ->setWebsite($milestone["website"] ?? null)
                    ->setPhoneNumber($milestone["phoneNumber"] ?? null)
                    ->setIsStartPoint($milestone["isStartPoint"])
                    ->setIsEndPoint($milestone["isEndPoint"])
                    ->setInTransit($milestone["inTransit"])
                    ->setRoomNumber($milestone["roomNumber"] ?? null)
                    ->setRentType($milestone["rentType"] ?? null)
                    ->setMealTimetables($milestone["mealTimetables"] ?? null)
                    ->setUserEdited($milestone['userEdited'])
                    ->setVisibility($milestone["visibility"])
                    ->setLinkedMilestonesIDs($milestone['linkedMilestonesIDs'] ?? []);
                if (isset($milestone ["ownerID"])) {
                    $owner = $this->userRepository->findOneBy(["id" => (int)$milestone["ownerID"]]);
                    if (isset($owner)) {
                        $dbMilestone->setOwner($owner);
                    }
                }
                if (count($milestone["images"] ?? [])){
                    foreach ($milestone["images"] as $fileName => $imageData) {
                        if ($this->fileManagerService->saveImage($imageData,$fileName)) {
                            $dbMilestone->appendImage($fileName);
                        }
                    }
                }
            }
            $this->milestoneRepository->save($dbMilestone);
        }
    }

    private function saveChecklists(): void
    {
        //dump ($checklists);
    }

    private function saveComments(): void
    {
        //dump($comments);
    }

    private function processCorpses(): void
    {
        foreach ($this->corpses as $corpse) {
            switch ($corpse["corpseType"]) {
                case "Trip":
                    $this->tripRepository->removeByID($corpse["corpseID"]);
            }
        }
        $this->locationRepository->commit();
    }

    public function processRequestObjectsArray(?array $objects): void
    {
        //dump($objects);
        foreach ($objects as $object) {
            switch ($object['type']) {
                case 'Location' && isset($object['object']['type']) && $object['object']['type'] == 'country':
                    $this->countries[$object['object']['objID']] = $object['object'];
                    break;
                case 'Location' && isset($object['object']['type']) && $object['object']['type'] == 'city':
                    $this->cities[$object['object']['objID']] = $object['object'];
                    break;
                case 'Location':
                    $this->locations[$object['object']['objID']] = $object['object'];
                    break;
                case 'Milestone':
                    $this->milestones[$object['object']['objID']] = $object['object'];
                    break;
                case 'Trip':
                    $this->trips[$object['object']['objID']] = $object['object'];
                    break;
                case 'Checklist':
                    $this->checklists[$object['object']['objID']] = $object['object'];
                    break;
                case 'Corpse':
                    $this->corpses[] = $object['object'];
                    break;
                case 'Comment':
                    $comments[$object['object']['objID']] = $object['object'];
                    break;
                case 'Syncer':
                    $this->lastSuccessfulSyncDate = \DateTime::createFromFormat(
                        'U',
                        (int)$object["object"]["lastSuccessfulSyncDate"] ?? 1);
                    $this->sessionID = $object["object"]["sessionID"] ?? null;
                    break;
                default:
                    break;
            }
        }
        $this->saveLocations("country");
        $this->saveLocations("city");
        $this->saveLocations(null);
        $this->saveMilestones();
        $this->saveTrips();
        $this->saveChecklists();
        $this->saveComments();
        $this->processCorpses();
    }

    public function getSyncResponse():SyncResponseList {
        $repositories = [
            "Trip" => $this->tripRepository,
            "Location" => $this->locationRepository,
            "Milestone" => $this->milestoneRepository,
            "Comment" => $this->commentRepository,
            "CheckList" => $this->checkListRepository
        ];
        $responseList = new SyncResponseList();

        foreach ($repositories as $objectType => $repository) {
            $dbObjects = $repository->getObjectsForSync($this->lastSuccessfulSyncDate);

            $responseList->appendArray($dbObjects);
        }

        //dump ($responseList);

        return $responseList;
    }
}