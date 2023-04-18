<?php

namespace App\DataFixtures;

use App\Amenity\Entity\Amenity as AmenityEntity;
use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $fooAmenity = new AmenityEntity();
        $fooAmenity->setId(1);
        $fooAmenity->setName('Wifi');
        $manager->persist($fooAmenity);

        $barAmenity = new AmenityEntity();
        $fooAmenity->setId(2);
        $barAmenity->setName('Washing Machine');
        $manager->persist($barAmenity);

        $listing = new PropertyListingEntity();
        $fooAmenity->setId(1);
        $listing->setTitle('Apartment 2');
        $listing->setDescription('First listing on cimi website');
        $listing->setAmenities(new ArrayCollection([$fooAmenity, $barAmenity]));
        $manager->persist($listing);

        $manager->flush();
    }
}
