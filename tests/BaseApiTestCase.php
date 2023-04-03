<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseApiTestCase extends WebTestCase
{
    protected ?EntityManagerInterface $entityManager;

    protected static ?KernelBrowser $staticClient;
    protected ?KernelBrowser $client;

    protected function setUp() : void
    {
        self::$staticClient = static::createClient();
        $this->client = self::$staticClient;

        //self::$kernel = self::bootKernel();

        DatabasePrimer::prime(self::$kernel);
        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}