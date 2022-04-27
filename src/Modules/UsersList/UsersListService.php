<?php

namespace App\Modules\UsersList;

use App\Entity\User;
use App\Modules\UsersList\Model\UsersListItem;
use App\Modules\UsersList\Model\UsersListResponse;
use Symfony\Component\HttpFoundation\UrlHelper;

class UsersListService
{
    public function __construct(private UsersListRepository $usersListRepository, private UrlHelper $urlHelper)
    {
    }

    public function getUsersList(): UsersListResponse {
        $users = $this->usersListRepository->findAll();

        return new UsersListResponse(array_map(
            fn (User $user) => new UsersListItem(
                $user->getId(),
                $user->getName(),
                $this->urlHelper->getAbsoluteUrl('/api/image/'.$user->getAvatar())
            ),
            $users
        ));
    }
}