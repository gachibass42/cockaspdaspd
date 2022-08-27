<?php

namespace App\Modules\Syncer;

use App\Entity\CheckList;
use App\Entity\Comment;
use App\Entity\Location;
use App\Entity\Milestone;
use App\Entity\Trip;
use App\Entity\TripUserRole;
use App\Entity\User;
use App\Helpers\FileManager\FileManagerService;
use App\Modules\Syncer\Model\SyncResponseListItem;
use App\Modules\UserProfile\UserRepository;
use App\Repository\AirlineRepository;
use App\Repository\CheckListRepository;
use App\Repository\CommentRepository;
use App\Repository\LocationRepository;
use App\Repository\MilestoneRepository;
use App\Repository\TripRepository;
use App\Repository\TripUserRoleRepository;
use Error;

class SyncerService
{
    const LASTSUCCESSFULSYNCSHIFTSECONDS = 120;
    private ?string $sessionID;
    private ?string $requestHandlingStatus;
    private array $trips = [];
    private array $milestones = [];
    private array $countries = [];
    private array $cities = [];
    private array $locations = [];
    private array $checklists = [];
    private array $corpses = [];
    private array $comments = [];
    private array $images = [];
    private ?\DateTimeImmutable $lastSuccessfulSyncDate;
    private User $user;

    public function __construct(private FileManagerService $fileManagerService,
                                private LocationRepository $locationRepository,
                                private UserRepository $userRepository,
                                private MilestoneRepository $milestoneRepository,
                                private TripRepository $tripRepository,
                                private CommentRepository $commentRepository,
                                private CheckListRepository $checkListRepository,
                                private AirlineRepository $airlineRepository,
                                private TripUserRoleRepository $tripUserRoleRepository)
    {
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
        foreach ($locations as $location) {
            $dbLocation = $this->locationRepository->findOneBy(["objID" => $location["objID"]]);
            if (!isset($dbLocation) || $dbLocation->getSyncDate()->getTimestamp() < ((int)$location["syncStatusDateTime"] ?? 0)) {
                $dbLocation = $dbLocation ?? new Location();
                $dbLocation->setObjID($location["objID"])
                    ->setSyncDate(\DateTime::createFromFormat('U', (int)$location["syncStatusDateTime"]))
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
                    ->setWebsite($location["website"] ?? null)
                    ->setIsUsing($location['isUsing']);
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
            if (!isset($dbTrip) || $dbTrip->getSyncDate()->getTimestamp() < ((int)$trip["syncStatusDateTime"] ?? 0)){
                $dbTrip = $dbTrip ?? new Trip();
                $dbTrip->setObjID($trip["objID"])
                    ->setSyncDate(\DateTime::createFromFormat('U', (int)$trip["syncStatusDateTime"]))
                    ->setName($trip["name"])
                    ->setDuration($trip["duration"] ?? null)
                    ->setStartDate(\DateTime::createFromFormat('U',(int)$trip["startDate"]))
                    ->setEndDate(\DateTime::createFromFormat('U',(int)$trip["endDate"]))
                    ->setLocked($trip["locked"])
                    ->setMilestonesIDs($trip["milestonesIDs"])
                    ->setTags($trip["tags"] ?? null)
                    ->setTripDescription($trip["tripDescription"] ?? null)
                    ->setVisibility($trip["visibility"])
                    ->setMainImage($trip['mainImage'] ?? null)
                    ->setCheckListsIDs($trip['checkListsIDs'])
                    ->setCost($trip['cost'] ?? null)
                    ->setCostDescription($trip['costDescription'] ?? null);
                if (isset($trip["ownerID"])) {
                    $owner = $this->userRepository->findOneBy(["id" => (int)$trip["ownerID"]]);
                    if (isset($owner)) {
                        $dbTrip->setOwner($owner);
                    }
                }
                if (isset($trip['users'])){
                    foreach ($trip['users'] as $userRole) {
                        $userID = $userRole['userID'];
                        $role = $userRole['roleName'];
                        $user = $this->userRepository->findOneBy(["id" => (int)$userID]);
                        if (isset($user)) {
                            $userRole = $this->tripUserRoleRepository->findOneBy(['trip' => $dbTrip, 'tripUser' => $user]) ?? new TripUserRole();
                            $userRole->setTrip($dbTrip)
                                ->setTripUser($user)
                                ->setRoleName($role);
                            $this->tripUserRoleRepository->save($userRole);
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
            if (!isset($dbMilestone) || $dbMilestone->getSyncDate()->getTimestamp() < ((int)$milestone["syncStatusDateTime"] ?? 0)){
                $dbMilestone = $dbMilestone ?? new Milestone();
                $dbMilestone->setObjID($milestone["objID"])
                    ->setSyncDate(\DateTime::createFromFormat('U', (int)$milestone["syncStatusDateTime"]))
                    ->setName($milestone["name"])
                    ->setLocationID($milestone["locationID"] ?? null)
                    ->setDuration($milestone["duration"] ?? null)
                    ->setDate(\DateTime::createFromFormat('U',(int)$milestone["date"]))
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
                    ->setLinkedMilestonesIDs($milestone['linkedMilestonesIDs'] ?? [])
                    ->setImages($milestone['images'])
                    ->setOrganizationLocationID($milestone["organizationLocationID"] ?? null);
                if (isset($milestone ["ownerID"])) {
                    $owner = $this->userRepository->findOneBy(["id" => (int)$milestone["ownerID"]]);
                    if (isset($owner)) {
                        $dbMilestone->setOwner($owner);
                    }
                }
                if (isset($milestone ["carrierID"])) {
                    $carrier = $this->airlineRepository->findOneBy(["objID" => $milestone["carrierID"]]);
                    if (isset($carrier)) {
                        $dbMilestone->setCarrier($carrier);
                    }
                }
            }
            //dump ($dbMilestone);
            $this->milestoneRepository->save($dbMilestone);
        }
    }

    private function saveChecklists(): void
    {
        //dump($this->checklists);
        foreach ($this->checklists as $checklist) {
            $dbCheckList = $this->checkListRepository->findOneBy(["objID" => $checklist["objID"]]);

            if (!isset($dbCheckList) || $dbCheckList->getSyncDate()->getTimestamp() < ((int)$checklist["syncStatusDateTime"] ?? 0)) {
                $dbCheckList = $dbCheckList ?? new CheckList();
                $dbCheckList->setObjID($checklist["objID"])
                    ->setSyncDate(\DateTime::createFromFormat('U', (int)$checklist["syncStatusDateTime"]))
                    ->setName($checklist["name"])
                    ->setType($checklist['type'] ?? null)
                    ->setBoxes($checklist['boxes'] ?? null);

            }
            //dump($dbCheckList);
            $this->checkListRepository->save($dbCheckList);
        }
    }

    private function saveComments(): void
    {
        foreach ($this->comments as $comment) {
            $dbComment = $this->commentRepository->findOneBy(["objID" => $comment["objID"]]);
            if (!isset($dbComment) || $dbComment->getSyncDate()->getTimestamp() < ((int)$comment["syncStatusDateTime"] ?? 0)){
                $dbComment = $dbComment ?? new Comment();
                $dbComment->setObjID($comment["objID"])
                    ->setSyncDate(\DateTime::createFromFormat('U', (int)$comment["syncStatusDateTime"]))
                    ->setLinkedObjID($comment["linkedObjID"])
                    ->setImages($comment["images"] ?? null)
                    ->setTags($comment["tags"] ?? null)
                    ->setDate(\DateTime::createFromFormat('U',(int)$comment["date"]))
                    ->setType($comment["type"])
                    ->setContent($comment["content"] ?? null);
                if (isset($comment["userID"])) {
                    $owner = $this->userRepository->findOneBy(["id" => (int)$comment["userID"]]);
                    if (isset($owner)) {
                        $dbComment->setOwner($owner);
                    }
                }
            }
            $this->commentRepository->save($dbComment);
        }
    }

    private function processCorpses(): void
    {
        foreach ($this->corpses as $corpse) {
            switch ($corpse["corpseType"]) {
                case "Trip":
                    $this->tripUserRoleRepository->removeByTrip($corpse["corpseID"]);
                    $this->tripRepository->removeByID($corpse["corpseID"]);
                    break;
                case "Milestone":
                    $this->milestoneRepository->removeByID($corpse["corpseID"]);
                    break;
                case "Comment":
                    $this->commentRepository->removeByID($corpse["corpseID"]);
                    break;
                case "CheckList":
                    $this->checkListRepository->removeByID($corpse["corpseID"]);
                    break;
            }
        }
        //dump ($this);
        //$this->locationRepository->commit();
    }

    private function saveImages(): bool
    {

        if (count($this->images) > 0){
            foreach ($this->images as $image) {
                $this->fileManagerService->saveImage(base64_decode($image['data']),$image['name']);
            }
        }
        return true;
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
                case 'CheckList':
                    $this->checklists[$object['object']['objID']] = $object['object'];
                    break;
                case 'Cemetery':
                    $this->corpses = $object['object']['corpses'];
                    break;
                case 'Comment':
                    $this->comments[$object['object']['objID']] = $object['object'];
                    break;
                case 'Syncer':
                    $this->lastSuccessfulSyncDate = \DateTimeImmutable::createFromFormat(
                        'U',
                        isset($object["object"]["lastSuccessfulSyncDate"]) ?
                            (int)($object["object"]["lastSuccessfulSyncDate"]) - SyncerService::LASTSUCCESSFULSYNCSHIFTSECONDS : 1);
                    //dump($this->lastSuccessfulSyncDate);
                    $this->sessionID = $object["object"]["sessionID"] ?? null;
                    break;
                case 'Images':
                    $this->images = $object['object']['images'];
                    break;
                default:
                    break;
            }
        }

        try {
            $this->saveLocations("country");
            $this->locationRepository->commit();
            $this->saveLocations("city");
            $this->locationRepository->commit();
            $this->saveLocations(null);
            $this->saveMilestones();
            $this->saveTrips();

            $this->saveChecklists();
            $this->saveComments();
            $this->processCorpses();
            if ($this->saveImages()) {
                $this->locationRepository->commit();
                $this->requestHandlingStatus = "Success";
            } else {
                $this->requestHandlingStatus = "Error";
            }

        } catch (Error $error) {
            $this->requestHandlingStatus = "Error";
        }

    }

    /**
     * @return SyncResponseListItem[]
     */
    public function getSyncResponse(string $username):array {
        $this->user = $this->userRepository->findOneBy(['username' => $username]);
        $repositories = [
            "Trip" => $this->tripRepository,
            "Location" => $this->locationRepository,
            "Milestone" => $this->milestoneRepository,
            "Comment" => $this->commentRepository,
            "CheckList" => $this->checkListRepository
        ];
        $requestObjects = array_merge($this->cities,
            $this->locations,
            $this->countries,
            $this->trips,
            $this->milestones,
            $this->comments,
            $this->checklists
        );
        //$responseList = [];
        $syncer = new SyncResponseListItem('Syncer', ['sessionID' => $this->sessionID, 'requestHandlingStatus' => $this->requestHandlingStatus]);
        //$responseList[] = $syncer;
        $lastSuccessfulSyncDate = $this->lastSuccessfulSyncDate ?? \DateTimeImmutable::createFromFormat('U', 1);// ?? $this->userRepository->findOneBy(['apiToken' => $apiToken])->getLastSuccessfulSyncDate();
        //dump($lastSuccessfulSyncDate);
        /*foreach ($repositories as $objectType => $repository) {
            $dbObjects = $repository->getObjectsForSync($lastSuccessfulSyncDate ?? \DateTime::createFromFormat('U', 1));


        }*/

        $locationsResponse = $this->mapToSyncResponseListItems($this->locationRepository->getObjectsForSync($lastSuccessfulSyncDate),
            array_merge($this->cities,$this->countries,$this->locations),
            'Location'
        );

        $dbTrips = $this->tripRepository->getObjectsForSync($lastSuccessfulSyncDate, $this->user->getId());
        $tripsResponse = $this->mapToSyncResponseListItems(
            $dbTrips,
            $this->trips,
            'Trip'
        );

        $milestonesArray = [];
        $checkListsArray = [];
        foreach ($dbTrips as $trip) {
            $milestonesArray = array_merge($milestonesArray,$trip->milestonesIDs);
            $checkListsArray = array_merge($checkListsArray,$trip->checkListsIDs);
        }

        $dbMilestones = count($milestonesArray) > 0 ? $this->milestoneRepository->getObjectsForSync($lastSuccessfulSyncDate,$milestonesArray) : [];
        $milestonesResponse = $this->mapToSyncResponseListItems($dbMilestones,
            $this->milestones,
            'Milestone'
        );

        $dbComments = count($milestonesArray) > 0 ? $this->commentRepository->getObjectsForSync($lastSuccessfulSyncDate,$milestonesArray) : [];
        $commentsResponse = $this->mapToSyncResponseListItems($dbComments,
            $this->comments,
            'Comment'
        );

        $dbChecklists = count($checkListsArray) > 0 ? $this->checkListRepository->getObjectsForSync($lastSuccessfulSyncDate,$checkListsArray) : [];
        $checklistsResponse = $this->mapToSyncResponseListItems($dbChecklists,
            $this->checklists,
            'CheckList'
        );

        //dump ($responseList);
        $responseList = array_merge($locationsResponse,$tripsResponse,$milestonesResponse,$commentsResponse,$checklistsResponse);
        $responseList[] = $syncer;
        return $responseList;
    }

    /**
     * @param array $dbObjects
     * @return SyncResponseListItem[]
     */
    private function mapToSyncResponseListItems(array $dbObjects, array $requestObjects, string $objectType): array {
        $responseList = [];
        foreach ($dbObjects as $dbObject) {
            $objID = $dbObject->objID;
            if (!isset($requestObjects[$objID]) || $dbObject->syncStatusDateTime > (int)$requestObjects[$objID]["syncStatusDateTime"]) {
                $responseList[] = new SyncResponseListItem($objectType,$dbObject);
            }

        }
        return $responseList;
    }

    public function commitSyncSession(string $username, $syncTimestamp): void
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);
        if (isset($user)) {
            $user->setLastSuccessfulSyncDate(\DateTime::createFromFormat('U', isset($syncTimestamp) ? (int)$syncTimestamp : 1));
            $this->userRepository->updateUser($user);
        }
    }

    public function failedSyncTry(string $username, $syncTimestamp): void
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);
        if (isset($user)) {
            $user->setLastSyncTryDate(isset($syncTimestamp) ? \DateTime::createFromFormat('U', (int)$syncTimestamp) : new \DateTime());
            $this->userRepository->updateUser($user);
        }
    }


    /**
     * @param $sessionID
     * @return SyncResponseListItem[]
     */
    public function getSyncerResponse($sessionID): array
    {
        //$responseList = new SyncResponseList();
        $syncer = new SyncResponseListItem('Syncer', ['sessionID' => $sessionID ?? "", 'requestHandlingStatus' => $this->requestHandlingStatus ?? "Commit"]);
        //$responseList->items[] = $syncer;
        //return $responseList;
        return [$syncer];
    }
}