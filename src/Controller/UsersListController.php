<?php

namespace App\Controller;

use App\Modules\UsersList\UsersListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersListController extends AbstractController
{
    public function __construct(private UsersListService $usersListService)
    {

    }

    #[Route('api/users/list', name: 'users_list')]
    public function index(): Response
    {
        return $this->json($this->usersListService->getUsersList());
    }
}
