<?php

namespace App\Tests\Fixtures;

use App\Entity\Amenity;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PropertyAmenityTestFixture extends AbstractFixture implements FixtureInterface
{

    public const WIFI_AMENITY_REFERENCE             = 'wifi-amenity';
    public const POOL_AMENITY_REFERENCE             = 'pool-amenity';
    public const WASHING_MACHINE_AMENITY_REFERENCE  = 'washing-machine-amenity';

    public function load(ObjectManager $manager)
    {
        $wifiAmenity = new Amenity();
        $wifiAmenity->setName('Wifi');
        $manager->persist($wifiAmenity);

        $poolAmenity = new Amenity();
        $poolAmenity->setName('Pool');
        $manager->persist($poolAmenity);

        $washingMachineAmenity = new Amenity();
        $washingMachineAmenity->setName('Washing Machine');
        $manager->persist($washingMachineAmenity);

        $manager->flush();

        $this->addReference(self::WIFI_AMENITY_REFERENCE, $wifiAmenity);
        $this->addReference(self::POOL_AMENITY_REFERENCE, $poolAmenity);
        $this->addReference(self::WASHING_MACHINE_AMENITY_REFERENCE, $washingMachineAmenity);
    }
}