<?php

namespace App\DTO\Builder\Property;

use App\DTO\Builder\AbstractEntityBuilder;
use App\DTO\Builder\EntityBuilderInterface;
use App\Entity\Amenity;
use App\Entity\EntityResourceInterface;
use App\Entity\Property;
use Doctrine\Common\Collections\ArrayCollection;
use Http\Discovery\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class PropertyEntityBuilder extends AbstractEntityBuilder implements EntityBuilderInterface
{
    protected function build(EntityResourceInterface $entity): Property
    {
        if(!($entity instanceof Property))
        {
            throw new \LogicException('Wrong entity class for this builder');
        }

        $entity->setTitle($this->dto->title);
        $entity->setDescription($this->dto->description);

        // Clear amenities for updating else will append
        $entity->setAmenities(new ArrayCollection());
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

    protected function getEntityClassName(): string
    {
        return Property::class;
    }
}