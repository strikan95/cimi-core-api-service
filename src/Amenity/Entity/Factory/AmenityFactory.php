<?php

namespace App\Amenity\Entity\Factory;

use App\Amenity\Dto\Amenity as AmenityDto;
use App\Amenity\Entity\Amenity as AmenityEntity;
use phpDocumentor\Reflection\Types\Self_;

class AmenityFactory
{
    public static function build(AmenityDto $dto, AmenityEntity $amenity = null): ?AmenityEntity
    {
        if(null === $amenity)
        {
            $amenity = new AmenityEntity();
        }

        self::loadFromDto($dto, $amenity);

        return $amenity;
    }

    private static function loadFromDto(AmenityDto $dto, AmenityEntity $amenity): void
    {
        $amenity->setName($dto->getName());
    }
}