<?php

namespace App\DTO\Builder;

use App\DTO\PropertyAmenity\PropertyAmenityOutput;
use App\DTO\PropertyListing\PropertyListingInput;
use App\DTO\PropertyListing\PropertyListingOutput;
use App\Entity\PropertyAmenity;
use App\Entity\PropertyListing;
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

    public function buildDto(PropertyListing $entity)
    {
        $dto = new PropertyListingOutput();
        $dto->id = $entity->getId();
        $dto->title = $entity->getTitle();
        $dto->description = $entity->getDescription();
        $dto->createdAt = $entity->getCreatedAt();

        foreach ($entity->getAmenities() as $amenity)
        {
            $amenityDto = new PropertyAmenityOutput();
            $amenityDto->id = $amenity->getId();
            $amenityDto->name = $amenity->getName();

            $dto->amenities[] = $amenityDto;
        }

        return $dto;
    }

    public function buildEntity(PropertyListingInput $dto)
    {
        $entity = new PropertyListing();

        $entity->setTitle($dto->title);
        $entity->setDescription($dto->description);

        foreach ($dto->amenities as $amenityId)
        {
            $amenity = $this->em->getRepository(PropertyAmenity::class)->findOneBy(['id' => $amenityId]);
            if(!$amenity)
            {
                throw new BadRequestException('Amenity with id ' . $amenityId . ' not found');
            }

            $entity->getAmenities()->add($amenity);
        }

        return $entity;
    }
}