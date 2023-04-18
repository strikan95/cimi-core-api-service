<?php

namespace App\Amenity\Entity\Factory;

use App\Amenity\Dto\Amenity as AmenityDto;
use App\Amenity\Entity\Amenity as AmenityEntity;

class AmenityFactory
{
    public static function create(AmenityDto $dto): AmenityEntity
    {
        $entity = new AmenityEntity();
        $entity->setName($dto->getName());

        return $entity;
    }
}