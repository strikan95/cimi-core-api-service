<?php

namespace App\PropertyListing;

use App\PropertyListing\Dto\PropertyListingInputDto;
use App\PropertyListing\Dto\PropertyListingOutputDto;
use App\PropertyListing\Entity\Factory\PropertyListingFactory;
use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use App\PropertyListing\Repository\PropertyListingRepository;
use App\Security\User\CurrentUserProvider;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
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
        private readonly Security $security
    ){
    }

    public function getById($id, bool $asDto = false): PropertyListingOutputDto|PropertyListingEntity
    {
        // Fetch entity
        $listing = $this->listingRepository->findById($id);
        if(null === $listing)
            throw new NotFoundHttpException("Listing with id ".$id." couldn't be found.");

        if(!$this->security->isGranted('view:listing', $listing))
            throw new UnauthorizedHttpException('Error: Unauthorized action');

        if($asDto)
            return new PropertyListingOutputDto($listing);

        return $listing;
    }

    public function create(Request $request): PropertyListingEntity
    {
        // Deserialize and validate DTO
        $content = $request->getContent();
        $dto = $this->getValidatedDto(
            $content,
            ['create'],
            PropertyListingInputDto::class
        );

        // Create new Entity
        $listing = $this->listingFactory->buildOrUpdate(
            $dto,
            null,
            ['owner' => $this->currentUserProvider->get()]
        );

        $this->dbSave($listing);
        return $listing;
    }

    public function update(int $id, Request $request): PropertyListingEntity
    {
        // Fetch entity
        $listing = $this->listingRepository->findById($id);
        if(null == $listing)
            throw new NotFoundHttpException("Listing with id ".$id." couldn't be found.");

        if(!$this->security->isGranted('update:listing', $listing))
            throw new UnauthorizedHttpException('Error: Unauthorized action');

        // Deserialize and validate DTO
        $content = $request->getContent();
        $dto = $this->getValidatedDto(
            $content, ['update'], PropertyListingInputDto::class
        );

        // Update entity
        $updatedListing = $this->listingFactory->buildOrUpdate($dto, $listing);

        $this->dbSave($updatedListing);
        return $updatedListing;
    }

    public function delete(int $id): void
    {
        $listing = $this->listingRepository->findById($id);
        if(null === $listing)
            throw new NotFoundHttpException("Listing with id ".$id." couldn't be found.");

        if(!$this->security->isGranted('delete:listing', $listing))
            throw new UnauthorizedHttpException('Error: Unauthorized action');

        $this->dbRemove($listing);
    }

    private function dbSave(PropertyListingEntity $listing): void
    {
        $this->listingRepository->save($listing, true);
    }

    private function dbRemove(PropertyListingEntity $listing): void
    {
        $this->listingRepository->remove($listing, true);
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