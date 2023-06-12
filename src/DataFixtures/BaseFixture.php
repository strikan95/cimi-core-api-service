<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Faker\Generator;

abstract class BaseFixture extends Fixture
{
    /** @var ObjectManager */
    private ObjectManager $manager;
    protected Generator $faker;

    private array $referencesIndex = [];

    abstract protected function loadData(ObjectManager $manager);
    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->faker = Factory::create();
        $this->loadData($manager);
    }

    protected function createMany(string $className, int $count, callable $factory, mixed $data = null): void
    {
        for ($i = 0; $i < $count; $i++) {
            $entity = new $className();
            $factory($entity, $i, $data);
            $this->manager->persist($entity);
            // store for usage later as App\Entity\ClassName_#COUNT#
            $this->addReference($className . '_' . $i, $entity);
        }
    }

    /**
     * @throws Exception
     */
    protected function getRandomReference(string $className)
    {
        if (!isset($this->referencesIndex[$className])) {
            $this->referencesIndex[$className] = [];
            foreach ($this->referenceRepository->getReferencesByClass() as $key => $ref) {
                $this->referencesIndex[$key] = array_keys($ref);
            }
        }

        if (empty($this->referencesIndex[$className])) {
            throw new Exception(sprintf('Cannot find any references for class "%s"', $className));
        }

        $randomReferenceKey = $this->faker->randomElement($this->referencesIndex[$className]);
        return $this->getReference($randomReferenceKey);
    }
}