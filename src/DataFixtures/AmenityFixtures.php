<?php

namespace App\DataFixtures;

use App\Amenity\Entity\Amenity as AmenityEntity;
use Doctrine\Persistence\ObjectManager;

class AmenityFixtures extends BaseFixture
{
    private static array $amenityNames = [
        'Wifi',
        'Washing machine',
        'Dishwasher',
        'Smart TV',
        'Kitchen',
        'Dryer',
        'Air Conditioning',
        'Heating'
    ];

    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(AmenityEntity::class, count(self::$amenityNames), function (AmenityEntity $amenity, $count) {
            $amenity->setId($count + 1);
            $amenity->setName(self::$amenityNames[$count]);
        });

        $manager->flush();
    }
}
