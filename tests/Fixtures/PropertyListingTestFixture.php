<?php

namespace App\Tests\Fixtures;

use App\Entity\Property;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PropertyListingTestFixture extends AbstractFixture implements FixtureInterface, DependentFixtureInterface
{
    public const FOO_LISTING_REFERENCE = 'foo-listing';
    public const BAR_LISTING_REFERENCE = 'bar-listing';
    public const FOOBAR_LISTING_REFERENCE = 'foobar-listing';


    public function load(ObjectManager $manager)
    {
        $fooListing = new Property();
        $fooListing->setTitle('foo');
        $fooListing->setDescription('This is foo listing');
        $fooListing->setAmenities(
            new ArrayCollection([
                $this->getReference(PropertyAmenityTestFixture::WIFI_AMENITY_REFERENCE),
                $this->getReference(PropertyAmenityTestFixture::POOL_AMENITY_REFERENCE),
            ])
        );
        $manager->persist($fooListing);

        $barListing = new Property();
        $barListing->setTitle('bar');
        $barListing->setDescription('This is bar listing');
        $barListing->setAmenities(
            new ArrayCollection([
                $this->getReference(PropertyAmenityTestFixture::WASHING_MACHINE_AMENITY_REFERENCE)
            ])
        );
        $manager->persist($barListing);

        $foobarListing = new Property();
        $foobarListing->setTitle('bar');
        $foobarListing->setDescription('This is foobar listing');
        $foobarListing->setAmenities(new ArrayCollection([]));
        $manager->persist($foobarListing);

        $manager->flush();

        $this->addReference(self::FOO_LISTING_REFERENCE, $fooListing);
        $this->addReference(self::BAR_LISTING_REFERENCE, $barListing);
        $this->addReference(self::FOOBAR_LISTING_REFERENCE, $foobarListing);
    }

    public function getDependencies(): array
    {
        return [PropertyAmenityTestFixture::class];
    }
}