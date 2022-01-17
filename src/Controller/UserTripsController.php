<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserTripsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserTripsController extends AbstractController
{
    public function __construct(private UserTripsService $userTripsService)
    {
    }

    #[Route('api/user/trips', name: 'user_trips')]
    public function userTrips(): Response
    {
        $user = new User(); //TODO: get user by token
        //dump($this->userTripsService->getUserTripsList($user));
        //$data = $this->userTripsService->getUserTripsList($user);
        //dump ($data);
        //print $data->getItems();

        //$response = ['test'=>'test_value'];
        return $this->json($this->userTripsService->getUserTripsList($user));
    }
}
