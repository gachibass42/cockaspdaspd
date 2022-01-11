<?php

namespace App\DataFixtures;

use App\Entity\Flight;
use App\Entity\User;
use App\Entity\Trip;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $users[] = new User();
        $users[0]->setUsername('mguser');
        $users[0]->setIsGuide(true);
        $users[0]->setPassword('123');
        $users[0]->setRoles(['ROLE_USER']);

        array_push($users, new User());
        $users[1]->setUsername('mgus');
        $users[1]->setIsGuide(true);
        $users[1]->setPassword('123');
        $users[1]->setRoles(['ROLE_USER']);

        foreach ($users as $user){
            $manager->persist($user);
        }
        $manager->flush();

        $trips[] = new Trip();
        $trips[0]->setName('Чехия');
        $trips[0]->setStartDate(new \DateTime());

        array_push($trips, new Trip());
        $trips[1]->setName('Бали');
        $trips[1]->setStartDate(new \DateTime());

        array_push($trips, new Trip());
        $trips[2]->setName('Спасск');
        $trips[2]->setStartDate(new \DateTime());

        $users[0]->addTrip($trips[0]);
        $users[1]->addTrip($trips[1]);
        $users[1]->addTrip($trips[2]);

        foreach ($users as $user){
            $manager->persist($user);
        }

        foreach ($trips as $trip){
            $manager->persist($trip);
        }

        $manager->flush();

        $milestones[] = new Flight();
        $milestones[0]->setName('Владивосток-Спасск');
        $milestones[0]->setVoyageId('666');
        $milestones[0]->setGateNumber(2);
        $trips[2]->addMilestone($milestones[0]);

        $manager->persist($trips[2]);
        $manager->persist($milestones[0]);
        $manager->flush();
        //$user->addTrip()
    }
}
