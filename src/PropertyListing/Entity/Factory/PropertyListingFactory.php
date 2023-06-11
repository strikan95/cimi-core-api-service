<?php

namespace App\PropertyListing\Entity\Factory;

use App\PropertyListing\Dto\PropertyListing as PropertyListingDto;
use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;

class PropertyListingFactory
{
    public static function build(PropertyListingDto $dto, PropertyListingEntity $listing = null): ?PropertyListingEntity
    {
        if(null === $listing)
        {
            $listing = new PropertyListingEntity();
        }

        self::loadFromDto($dto, $listing);

        return $listing;
    }

    private static function loadFromDto(PropertyListingDto $dto, PropertyListingEntity $listing): void
    {
        $listing->setTitle($dto->getTitle());
        $listing->setDescription($dto->getDescription());
        $listing->setPrice($dto->getPrice());
    }
}