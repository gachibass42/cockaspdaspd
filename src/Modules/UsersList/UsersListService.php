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


    /**
     * @return UsersListItem[]
     */
    public function getUsersList(): array {
        $users = $this->usersListRepository->findAll();

        return array_map(
            fn (User $user) => new UsersListItem(
                $user->getId(),
                $user->getName() ?? $user->getUserIdentifier(),
                $user->getAvatar() != null ? $this->urlHelper->getAbsoluteUrl('/api/v1/image/'.$user->getAvatar()) : null
            ),
            $users
        );
    }
}