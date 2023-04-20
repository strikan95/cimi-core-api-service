<?php

namespace App\Amenity;

use App\Amenity\Dto\Amenity as AmenityDto;
use App\Amenity\Entity\Amenity as AmenityEntity;
use App\Amenity\Entity\Factory\AmenityFactory;
use App\Amenity\Repository\AmenityRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AmenityService
{
    public function __construct
    (
        private readonly AmenityRepository $amenityRepository
    ){
    }

    public function getById($id): AmenityEntity
    {
        $entity = $this->amenityRepository->find($id);

        if(null === $entity)
        {
            throw new NotFoundHttpException("Amenity with id ". $id ." couldn't be found.");
        }

        return $entity;
    }

    public function store(AmenityDto $dto): AmenityEntity
    {
        $entity = AmenityFactory::build($dto);
        $this->save($entity);

        return $entity;
    }

    public function update(AmenityDto $dto): AmenityEntity
    {
        if(null === $dto->getId())
        {
            throw new \LogicException('Id not provided');
        }

        $entity =  AmenityFactory::build(
            $dto,
            $this->getById($dto->getId())
        );
        $this->save($entity);

        return $entity;
    }

    private function save(AmenityEntity $entity): void
    {
        $this->amenityRepository->save($entity, true);
    }
}