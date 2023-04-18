<?php

namespace App\PropertyListing\Entity\Factory;

use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;

class PropertyListingFactory
{
    public static function create($dto): PropertyListingEntity
    {
        $entity = new PropertyListingEntity();
        $entity->setTitle($dto->getTitle());
        $entity->setDescription($dto->getDescription());

        return $entity;
    }
}