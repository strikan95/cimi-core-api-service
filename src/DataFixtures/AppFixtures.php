<?php

namespace App\DataFixtures;

use App\Entity\Amenity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $fooAmenity = new Amenity();
        $fooAmenity->setName('Wifi');
        $manager->persist($fooAmenity);

        $barAmenity = new Amenity();
        $barAmenity->setName('Washing Machine');
        $manager->persist($barAmenity);

        $manager->flush();
    }
}
