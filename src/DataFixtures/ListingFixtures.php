<?php

namespace App\DataFixtures;

use App\Amenity\Entity\Amenity as AmenityEntity;
use App\AppUser\Entity\AppUser as AppUserEntity;
use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ListingFixtures extends BaseFixture implements DependentFixtureInterface
{
    const OSBB = [18.589554,45.525833,18.768768,45.581608];

    public function getDependencies(): array
    {
        return [
            AppUserFixtures::class,
            AmenityFixtures::class,
        ];
    }

    protected function loadData(ObjectManager $manager): void
    {
        $ranPoints = $this->generateRandomNPoints(self::OSBB, 200);

        $this->createMany(PropertyListingEntity::class, 100, function (PropertyListingEntity $propertyListing, $count, $ranPoints) {
            $propertyListing->setId($count + 1);
            $propertyListing->setTitle($this->faker->realText(20));
            $propertyListing->setDescription($this->faker->realText(100));
            $propertyListing->setPrice($this->faker->numberBetween(10, 1000));
            $propertyListing->setOwner($this->getRandomReference(AppUserEntity::class));

            if($count > 100) {
                $propertyListing->setLat(0);
                $propertyListing->setLon(0);
            } else {
                $propertyListing->setLat($ranPoints[$count][1]);
                $propertyListing->setLon($ranPoints[$count][0]);
            }

            for ($i = 0; $i < 4; $i++)
            {
                $propertyListing->addAmenity($this->getRandomReference(AmenityEntity::class));
            }
        }, $ranPoints);

        $manager->flush();
    }

    private function generateRandomNPoints(array $rect, int $n): array
    {
        $points = [];
        for ($i = 0; $i < $n; $i++)
        {
            $p[0] = $rect[0] + $this->randomFloat(0, $rect[2] - $rect[0]);
            $p[1] = $rect[1] + $this->randomFloat(0, $rect[3] - $rect[1]);

            $points[] = $p;
        }

        return $points;
    }

    private function randomFloat($min = 0, $max = 1) {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }
}
