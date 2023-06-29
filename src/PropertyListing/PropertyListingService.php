<?php

namespace App\PropertyListing;

use App\PropertyListing\Dto\PropertyListingInputDto;
use App\PropertyListing\Dto\PropertyListingOutputDto;
use App\PropertyListing\Entity\Factory\PropertyListingFactory;
use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use App\PropertyListing\Repository\PropertyListingRepository;
use App\Security\User\CurrentUserProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PropertyListingService
{
    public function __construct
    (
        private readonly PropertyListingRepository $listingRepository,
        private readonly CurrentUserProvider $currentUserProvider,
        private readonly PropertyListingFactory $listingFactory,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface  $serializer,
    ){
    }

    public function getById($id, bool $asDto = false): PropertyListingOutputDto|PropertyListingEntity
    {
        $entity = $this->listingRepository->find($id);

        if(null === $entity)
        {
            throw new NotFoundHttpException("Listing with id ".$id." couldn't be found.");
        }

        if($asDto)
            return new PropertyListingOutputDto($entity);

        return $entity;
    }

    public function create(Request $request): PropertyListingEntity
    {
        $dto = $this->getValidatedDto(
            $request->getContent(),
            ['create'],
            PropertyListingInputDto::class
        );

        $entity = $this->listingFactory->buildOrUpdate(
            dto: $dto,
            owner: $this->currentUserProvider->get()
        );

        $this->save($entity);

        return $entity;
    }

    public function update(int $id, Request $request): PropertyListingEntity
    {
        $dto = $this->getValidatedDto(
            $request->getContent(),
            ['update'],
            PropertyListingInputDto::class
        );

        $entity = $this->listingFactory->buildOrUpdate(
            $dto,
            $this->listingRepository->findById($id)
        );

        $this->save($entity);

        return $entity;
    }

    private function save(PropertyListingEntity $listing): void
    {
        $this->listingRepository->save($listing, true);
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

    private function getValidatedDto(string $rawJson, array $validationGroups,  string $className)
    {
        $dto = $this->deserializeTo($rawJson, $className);
        if(count($this->validateDto($dto, $validationGroups)) > 0)
        {
            throw new BadRequestHttpException('Error when validating.');
        }

        return $dto;
    }

    private function deserializeTo(string $content, string $className)
    {
        return $this->serializer->deserialize
        (
            $content,
            $className,
            'json'
        );
    }

    private function validateDto($dto, array $validationGroups): ConstraintViolationListInterface
    {
        return $this->validator->validate($dto, null, $validationGroups);
    }
}