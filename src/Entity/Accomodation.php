<?php

namespace App\Entity;

use App\Repository\AccomodationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccomodationRepository::class)]
class Accomodation extends Milestone
{

}
