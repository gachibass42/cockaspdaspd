<?php

namespace App\Modules\EPicker;

use App\Entity\EPicker;
use App\Repository\EPickerRepository;

class EPickerService
{

    public function __construct(private EPickerRepository $repository)
    {
    }

    public function saveMessage(string $name, string $description, ?string $additional) {
        $message = new EPicker();
        $message->setDate(new \DateTime())
            ->setName($name)
            ->setDescription($description)
            ->setAdditional($additional);
        $this->repository->add($message);
    }
}