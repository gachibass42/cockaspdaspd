<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends AbstractController
{
    #[Route('/place/search', name: 'api_v1_place_search')]
    public function search()
    {

    }
}
