<?php

namespace App\DataFixtures;

use App\Entity\PropertyAmenity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $fooAmenity = new PropertyAmenity();
        $fooAmenity->setName('Wifi');
        $manager->persist($fooAmenity);

        $barAmenity = new PropertyAmenity();
        $barAmenity->setName('Washing Machine');
        $manager->persist($barAmenity);

        $manager->flush();
    }
}
