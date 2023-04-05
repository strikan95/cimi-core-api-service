<?php

namespace App\DTO\Builder;

use App\DTO\Builder\Property\PropertyDtoBuilder;
use App\DTO\Builder\Property\PropertyEntityBuilder;
use App\DTO\DtoResourceInterface;
use App\DTO\Response\Property\CreatePropertyDto;
use App\Entity\EntityResourceInterface;
use App\Entity\Property;
use Doctrine\ORM\EntityManagerInterface;

class BuilderFactory implements BuilderFactoryInterface
{

    public function __construct
    (
        protected readonly EntityManagerInterface $em
    )
    {
    }

    public function createDtoBuilder(EntityResourceInterface $entity): DtoBuilderInterface|\LogicException
    {
        if($entity instanceof Property)
        {
            return new PropertyDtoBuilder($this->em, $entity);
        }

        throw new \LogicException('Builder cannot be found');
    }

    public function createEntityBuilder(DtoResourceInterface $dto): EntityBuilderInterface|\LogicException
    {
        if($dto instanceof CreatePropertyDto)
        {
            return new PropertyEntityBuilder($this->em, $dto);
        }

        throw new \LogicException('Builder cannot be found');
    }
}