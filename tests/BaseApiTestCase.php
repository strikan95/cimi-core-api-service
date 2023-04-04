<?php

namespace App\Tests;

use App\Tests\Fixtures\PropertyListingTestFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseApiTestCase extends WebTestCase
{
    protected ?EntityManagerInterface $entityManager;

    protected ?KernelBrowser $client;

    protected array $fixtureReferences;

    protected function setUp() : void
    {
        $this->client = static::createClient();

        DatabasePrimer::prime(self::$kernel);
        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();

        $this->loader = new Loader();
        $this->executor = new ORMExecutor($this->entityManager, new ORMPurger());

        $this->loadFixtures();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    protected function loadFixtures(): void
    {
        $this->loader->addFixture(new PropertyListingTestFixture());
        $this->executor->execute($this->loader->getFixtures());

        $this->fixtureReferences = $this->executor
            ->getReferenceRepository()
            ->getReferencesByClass();
    }
}