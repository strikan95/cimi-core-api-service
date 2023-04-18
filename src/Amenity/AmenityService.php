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
        $entity = AmenityFactory::create($dto);
        $this->amenityRepository->save($entity, true);

        return $entity;
    }
}