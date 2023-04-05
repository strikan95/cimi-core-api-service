<?php

namespace App\DTO\Builder;

use App\DTO\Request\Amenity\GetAmenityDto;
use App\DTO\Request\Property\GetPropertyDto;
use App\DTO\Response\Property\CreatePropertyDto;
use App\Entity\Amenity;
use App\Entity\Property;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class PropertyListingBuilder
{
    public function __construct
    (
        private readonly EntityManagerInterface $em
    )
    {
    }

    public function buildDto(Property $entity)
    {
        $dto = new GetPropertyDto();
        $dto->id = $entity->getId();
        $dto->title = $entity->getTitle();
        $dto->description = $entity->getDescription();
        $dto->createdAt = $entity->getCreatedAt();

        foreach ($entity->getAmenities() as $amenity)
        {
            $amenityDto = new GetAmenityDto();
            $amenityDto->id = $amenity->getId();
            $amenityDto->name = $amenity->getName();

            $dto->amenities[] = $amenityDto;
        }

        return $dto;
    }

    public function buildEntity(CreatePropertyDto $dto)
    {
        $entity = new Property();

        $entity->setTitle($dto->title);
        $entity->setDescription($dto->description);

        foreach ($dto->amenities as $amenityId)
        {
            $amenity = $this->em->getRepository(Amenity::class)->findOneBy(['id' => $amenityId]);
            if(!$amenity)
            {
                throw new BadRequestException('Amenity with id ' . $amenityId . ' not found');
            }

            $entity->getAmenities()->add($amenity);
        }

        return $entity;
    }
}