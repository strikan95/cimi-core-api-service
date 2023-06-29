<?php

namespace App\PropertyListing\Entity\Factory;

use App\Amenity\Repository\AmenityRepository;
use App\ApiTools\EntityFactory\AbstractEntityFactory;
use App\PropertyListing\Entity\PropertyListing;
use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PropertyListingFactory extends AbstractEntityFactory
{

    public function __construct(
        private readonly AmenityRepository $amenityRepository
    )
    {
    }

    function getEntityClassName(): string
    {
        return PropertyListing::class;
    }

    function onCreatePreLoad($source, mixed $target, mixed $settings): void
    {
        if(!isset($settings['owner']))
            throw new \LogicException('Owner cannot but null');

        $target->setOwner($settings['owner']);
    }

    protected function setAmenities(array $amenities, PropertyListingEntity $subject): void
    {
        $subject->setAmenities(new ArrayCollection());
        foreach ($amenities as $amenity) {
            $amenity = $this->amenityRepository->findById($amenity);
            if($amenity == null) throw new BadRequestHttpException("Amenity couldn't be found!");
            $subject->addAmenity($amenity);
        }
    }
}