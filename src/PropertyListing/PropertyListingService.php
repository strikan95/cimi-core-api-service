<?php

namespace App\PropertyListing;

use App\PropertyListing\Dto\PropertyListing as PropertyListingDTO;
use App\PropertyListing\Entity\Factory\PropertyListingFactory;
use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use App\PropertyListing\Repository\PropertyListingRepository;
use App\Security\User\CurrentUserProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PropertyListingService
{
    public function __construct
    (
        private readonly PropertyListingRepository $listingRepository,
        private readonly CurrentUserProvider $currentUserProvider
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
        $entity = PropertyListingFactory::build($dto);
        $entity->setOwner(
            $this->currentUserProvider->get()
        );
        $this->listingRepository->save($entity, true);

        return $entity;
    }

    public function update(PropertyListingDTO $dto): PropertyListingEntity
    {
        if(null === $dto->getId())
        {
            throw new \LogicException('Id not provided');
        }

        $entity = PropertyListingFactory::build(
            $dto,
            $this->getById($dto->getId())
        );
        $this->save($entity);

        return $entity;
    }

    public function delete(int $id): void
    {
        $entity = $this->listingRepository->find($id);

        if(null === $entity)
        {
            throw new NotFoundHttpException("Listing with id ".$id." couldn't be found.");
        }

        $this->listingRepository->remove($entity, true);
    }

    private function save(PropertyListingEntity $listing): void
    {
        $this->listingRepository->save($listing, true);
    }
}