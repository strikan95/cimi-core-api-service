<?php

namespace App\PropertyListing;

use App\PropertyListing\Dto\PropertyListing as PropertyListingDTO;
use App\PropertyListing\Entity\Factory\PropertyListingFactory;
use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use App\PropertyListing\Repository\PropertyListingRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PropertyListingService
{
    public function __construct
    (
        private readonly PropertyListingRepository $listingRepository
    ){
    }

    public function getById($id): PropertyListingEntity
    {
        $entity = $this->listingRepository->find($id);

        if(null === $entity)
        {
            throw new NotFoundHttpException("Listing with id ".$id." couldn't be found.");
        }

        return $entity;
    }

    public function store(PropertyListingDTO $dto): PropertyListingEntity
    {
        $entity = PropertyListingFactory::create($dto);
        $this->listingRepository->save($entity, true);

        return $entity;
    }
}