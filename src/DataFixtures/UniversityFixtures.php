<?php

namespace App\DataFixtures;

use App\University\Entity\University as UniversityEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UniversityFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $json = $this->loadJson('src/DataFixtures/universities.json');

        $this->createMany(UniversityEntity::class, count($json), function (UniversityEntity $university, $count, $json) {
            $university->setId($count + 1);
            $university->setName($json[$count]->naziv);
            $university->setCity($json[$count]->sjediste);
            $university->setFullAddress($json[$count]->adresa);
            $university->setLat($json[$count]->lat);
            $university->setLon($json[$count]->lon);
        }, $json);

        $manager->flush();
    }

    protected function loadJson(string $uri)
    {
        return json_decode(file_get_contents($uri));
    }
}
