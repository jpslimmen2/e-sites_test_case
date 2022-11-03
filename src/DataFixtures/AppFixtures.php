<?php

namespace App\DataFixtures;

use App\Entity\Seat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //creates 30 seats for testing purpose
        for ($i = 0; $i < 30; $i++){
            $seat = new Seat();
            $seat->setSeatNumber($i+1);
            $seat->setOccupied(rand(0,1));
            $manager->persist($seat);
        }

        $manager->flush();
    }
}
