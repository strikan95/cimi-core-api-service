<?php

namespace App\PropertyListing\Entity\Factory;

use App\Amenity\Repository\AmenityRepository;
use App\AppUser\Entity\AppUser as AppUserEntity;
use App\PropertyListing\Dto\PropertyListingInputDto;
use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PropertyListingFactory
{
    public function __construct(
        private readonly AmenityRepository $amenityRepository
    )
    {
    }

    public function buildOrUpdate(PropertyListingInputDto $dto, PropertyListingEntity $listing = null, AppUserEntity $owner = null): ?PropertyListingEntity
    {
        if(null === $listing)
        {
            $listing = new PropertyListingEntity();

            if (null === $owner) throw new \LogicException('Owner cannot but null');
            $listing->setOwner($owner);
        }

        $this->loadFromDto($dto, $listing);

        return $listing;
    }

    private function loadFromDto(PropertyListingInputDto $dto, PropertyListingEntity $listing): void
    {
        foreach (get_object_vars($dto) as $param => $value)
        {
            if(null == $value) continue;

            $method = 'set'.ucwords($param);
            if (method_exists($this, $method) && is_callable([$this, $method])) {
                $this->$method($value, $listing);
            } else {
                $this->setValue($param, $value, $listing);
            }
        }
    }

    private function setAmenities(array $amenities, PropertyListingEntity $subject): void
    {
        $subject->setAmenities(new ArrayCollection());
        foreach ($amenities as $amenity) {
            $amenity = $this->amenityRepository->findById($amenity);
            if($amenity == null) throw new BadRequestHttpException("Amenity couldn't be found!");
            $subject->addAmenity($amenity);
        }
    }

    private function setValue($param, $value, $subject): void
    {
        $method = 'set'.ucwords($param);

        if (method_exists($subject, $method) && is_callable([$subject, $method])) {
            $subject->$method($value);
        }
    }
}