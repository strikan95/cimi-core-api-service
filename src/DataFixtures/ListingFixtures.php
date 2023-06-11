<?php

namespace App\DataFixtures;

use App\Amenity\Entity\Amenity as AmenityEntity;
use App\AppUser\Entity\AppUser as AppUserEntity;
use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ListingFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            AppUserFixtures::class,
            AmenityFixtures::class,
        ];
    }

    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(PropertyListingEntity::class, 200, function (PropertyListingEntity $propertyListing, $count) {
            $propertyListing->setTitle($this->faker->realText(20));
            $propertyListing->setDescription($this->faker->realText(100));
            $propertyListing->setPrice($this->faker->numberBetween(10, 1000));
            $propertyListing->setOwner($this->getRandomReference(AppUserEntity::class));

            for ($i = 0; $i < 4; $i++)
            {
                $propertyListing->addAmenity($this->getRandomReference(AmenityEntity::class));
            }
        });

        $manager->flush();
    }
}
