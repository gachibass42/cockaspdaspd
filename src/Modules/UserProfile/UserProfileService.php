<?php

namespace App\Modules\UserProfile;

use App\Entity\User;
use App\Helpers\FileManager\FileManagerService;
use App\Modules\UserProfile\Model\UserProfile;
use App\Modules\UserProfile\Model\UserProfileResponse;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

//use Symfony\Component\HttpFoundation\File\File;

class UserProfileService
{
    private HttpClientInterface $client;
    public function __construct(private UserRepository $userRepository, private UrlHelper $urlHelper, private FileManagerService $fm)
    {
        $this->client = HttpClient::create([
            'base_uri' => 'https://proc.minegoat.ru/',
        ]);
    }

    public function getUserProfile(User $user):UserProfileResponse
    {
        if ($user->getId() != null) {
            $profile = $this->userRepository->findOneBy(['id'=>$user->getId()]);
        } else {
            $profile = $this->userRepository->findOneBy(['apiToken'=>$user->getApiToken()]);
        }


        //$users = $this->userRepository->findAll();
        /*do {
            $profile = array_pop($users);//TODO: get user by id
        } while ($profile->getName() == null);*/

        $items[] = new UserProfile(
            (string)$profile->getId(),
            $profile->getName(),
            $profile->getPhone(),
            $profile->getDescription(),
            $profile->getIsGuide(),
            $this->urlHelper->getAbsoluteUrl('/api/image/'.$profile->getAvatar()), //TODO: move images directory path to global
            $profile->getHomeLocationID(),
            $profile->getSex()
        );
        return new UserProfileResponse($items);
    }

    public function updateUserProfile(string $data, string $token = null):UserProfileResponse
    {
        //serialize data from request
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $requestData = new UserProfileResponse(null);
        $serializer->deserialize(
            $data,
            UserProfileResponse::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE=>$requestData]
        );
        //Get single user from items array
        if (count($requestData->items) > 0){
            $userItem = array_pop($requestData->items);
        }
        //Get user from DB and update properties according to user requestData
        $profile = $this->userRepository->findOneBy(['apiToken'=>$token]); //TODO: get user by token, check auth
        if (isset($userItem) && $profile != null){
            $profile->setName($userItem['name']);
            $profile->setIsGuide($userItem['isGuide']);
            $profile->setDescription($userItem['userDescription']);
            $profile->setPhone($userItem['phone']);
            $profile->setSex($userItem['sex']);
            $profile->setHomeLocationID($userItem['homeLocationID']);
            if (isset($userItem['avatar']) && $userItem['avatar'] != null){
                $filename = $this->fm->saveImage(base64_decode($userItem['avatar']), $userItem['avatarFileName']);
                if ($filename != null){
                    $profile->setAvatar($filename);
                }
            }
            $this->userRepository->updateUser($profile);
        } else {
            print 'User trouble';
        }
        $profile = $this->userRepository->findOneBy(['apiToken'=>$token]);
        return $this->getUserProfile($profile);
    }

    public function userRegister():UserProfileResponse {
        $response = $this->client->request(Request::METHOD_GET, '/api/register'/*, [
            'query' => [
                'input' => $text,
                'types' => $this->googleTypes[$type],//'locality',
                'sessiontoken' => $sessionID,
                'key' => $this->apiKey,
                'language' => 'ru'
            ],
        ]*/);
        //dump($response);
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return new UserProfileResponse(null);
        }
        $data = json_decode($response->getContent(), true);

        $user = new User();

        $userProfile = new UserProfile($user->getId(),
            $user->getName(),
            $user->getPhone(),
            $user->getDescription(),
            $user->getIsGuide(),
            $user->getAvatar(),
            $user->getHomeLocationID(),
            $user->getSex());
        return new UserProfileResponse([$userProfile]);
    }

}