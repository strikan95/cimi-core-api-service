<?php

namespace App\DataFixtures;

use App\AppUser\Entity\AppUser as AppUserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppUserFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(AppUserEntity::class, 30, function (AppUserEntity $appUser, $count) {
            if($count == 0) {
                $appUser->setId($count + 1);
                $appUser->setUserIdentifier('auth0|647ded4bdefe974c4f779009');
                $appUser->setRole('ROLE_LANDLORD');
                $appUser->setDisplayName('landlord');
                $appUser->setFirstName('Land');
                $appUser->setLastName('Lord');
            } else {
                $appUser->setId($count + 1);
                $appUser->setUserIdentifier($count);
                $appUser->setRole('ROLE_LANDLORD');
                $appUser->setFirstName($this->faker->firstName);
                $appUser->setLastName($this->faker->lastName);
                $appUser->setDisplayName($this->faker->userName);
            }
        });

        $manager->flush();
    }
}
