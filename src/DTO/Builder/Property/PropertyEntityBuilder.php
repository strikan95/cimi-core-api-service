<?php

namespace App\DTO\Builder\Property;

use App\DTO\Builder\EntityBuilderInterface;
use App\DTO\DtoResourceInterface;
use App\Entity\Amenity;
use App\Entity\EntityResourceInterface;
use App\Entity\Property;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class PropertyEntityBuilder implements EntityBuilderInterface
{
    public function __construct
    (
        private readonly EntityManagerInterface $em,
        private readonly DtoResourceInterface $dto
    )
    {
    }

    public function buildEntity(): EntityResourceInterface
    {
        $entity = new Property();

        $entity->setTitle($this->dto->title);
        $entity->setDescription($this->dto->description);

        foreach ($this->dto->amenities as $amenityId)
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