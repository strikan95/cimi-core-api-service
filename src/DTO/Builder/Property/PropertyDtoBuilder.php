<?php

namespace App\DTO\Builder\Property;

use App\DTO\Builder\DtoBuilderInterface;
use App\DTO\Request\Amenity\GetAmenityDto;
use App\DTO\Request\Property\GetPropertyDto;
use App\Entity\EntityResourceInterface;
use Doctrine\ORM\EntityManagerInterface;

class PropertyDtoBuilder implements DtoBuilderInterface
{
    public function __construct
    (
        private readonly EntityManagerInterface $em,
        private readonly EntityResourceInterface $entity
    )
    {
    }

    public function buildDto(): GetPropertyDto
    {
        $dto = new GetPropertyDto();
        $dto->id = $this->entity->getId();
        $dto->title = $this->entity->getTitle();
        $dto->description = $this->entity->getDescription();
        $dto->createdAt = $this->entity->getCreatedAt();

        foreach ($this->entity->getAmenities() as $amenity)
        {
            $amenityDto = new GetAmenityDto();
            $amenityDto->id = $amenity->getId();
            $amenityDto->name = $amenity->getName();

            $dto->amenities[] = $amenityDto;
        }

        return $dto;
    }
}