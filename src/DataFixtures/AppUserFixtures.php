<?php

namespace App\DataFixtures;

use App\AppUser\Entity\AppUser as AppUserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppUserFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager): void
    {
        $user = new AppUserEntity();
        $user->setUserIdentifier('auth0|647ded4bdefe974c4f779009');
        $user->setEmail('landlord@example.com');
        $user->setDisplayName($this->faker->userName);
        $user->setFirstName($this->faker->firstName);
        $user->setLastName($this->faker->lastName);

        $this->addReference(AppUserEntity::class . '_' . 'landlord', $user);

        $manager->persist($user);
        $manager->flush();
    }
}
