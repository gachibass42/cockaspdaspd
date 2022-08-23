<?php

namespace App\Modules\UserProfile;

use App\Entity\User;
use App\Helpers\FileManager\FileManagerService;
use App\Modules\UserProfile\Model\UserProfile;
use App\Modules\UserProfile\Model\UserProfileResponse;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserProfileService
{
    public function __construct(private UserRepository $userRepository,
                                private UrlHelper $urlHelper,
                                private FileManagerService $fm
    )
    {
        $this->client = HttpClient::create([
            'base_uri' => 'https://proc.minegoat.ru/',
        ]);
    }


    /**
     * @param string $username
     * @return UserProfile[]
     */
    public function getUserProfile(string $username, ?int $id = null):array
    {
        if (isset($id)) {
            $profile = $this->userRepository->findOneBy(['id' => $id]);
        } else {
            $profile = $this->userRepository->findOneBy(['username'=>$username]);
        }



        //$users = $this->userRepository->findAll();
        /*do {
            $profile = array_pop($users);//TODO: get user by id
        } while ($profile->getName() == null);*/
        $items = [];
        if (isset($profile)) {
            $items[] = $this->mapToUserProfile($profile); /*new UserProfile(
                (string)$profile->getId(),
                $profile->getName(),
                $profile->getPhone(),
                $profile->getDescription(),
                $profile->getIsGuide(),
                $profile->getAvatar() != null ? $this->urlHelper->getAbsoluteUrl('/api/v1/image/'.$profile->getAvatar()) : null, //TODO: move images directory path to global
                $profile->getHomeLocationID(),
                $profile->getSex()
            );*/

        }
        return $items;
    }

    public function updateUserProfile(string $data, string $username = null):array
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
        $profile = $this->userRepository->findOneBy(['username'=>$username]);
        if (isset($userItem) && $profile != null){
            $profile->setName($userItem['name'] ?? null);
            $profile->setIsGuide($userItem['isGuide']);
            $profile->setDescription($userItem['userDescription'] ?? null);
            $profile->setPhone($userItem['phone'] ?? null);
            $profile->setSex($userItem['sex'] ?? null);
            $profile->setHomeLocationID($userItem['homeLocationID'] ?? null);
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
        $profile = $this->userRepository->findOneBy(['username'=>$username]);
        //return $this->getUserProfile($profile);
        return [$this->mapToUserProfile($profile)];
    }

    public function deleteUser(string $username) {

    }

    private function mapToUserProfile(User $profile): UserProfile {
        return new UserProfile(
            (string)$profile->getId(),
            $profile->getName(),
            $profile->getPhone(),
            $profile->getDescription(),
            $profile->getIsGuide(),
            $profile->getAvatar() != null ? $this->urlHelper->getAbsoluteUrl('/api/v1/image/'.$profile->getAvatar()) : null, //TODO: move images directory path to global
            $profile->getHomeLocationID(),
            $profile->getSex()
        );
    }
}