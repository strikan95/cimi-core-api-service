<?php

namespace App\PropertyListing\Controller;

use App\PropertyListing\Dto\PropertyListingInputDto;
use App\PropertyListing\Dto\PropertyListingOutputDto;
use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use App\PropertyListing\PropertyListingService;
use App\PropertyListing\Query\ListingFilter;
use App\Security\User\CurrentUserProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PropertyListingController extends AbstractController
{
    public function __construct
    (
        private readonly PropertyListingService $propertyListingService,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('/api/v1/listings/index', name: 'api.listings.index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $filter = new ListingFilter($request, $this->entityManager);
        $filter->apply();
        $results = $filter->executeQuery();

        $dtos = [];
        foreach ($results as $result)
        {
            $dto = new PropertyListingOutputDto(is_array($result) ? $result[0]:$result);
            $dtos[] = $dto;
        }

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['listings_extended', 'listings_with_amenities'])
            ->toArray();

        return $this->json($dtos, Response::HTTP_OK, context:$context);
    }

    #[Route('/api/v1/listings/{id}', name: 'api.listings.get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $dto = $this->propertyListingService->getById($id, true);

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['listings_extended', 'listings_with_amenities', 'listings_with_reservations'])
            ->toArray();

        return $this->json($dto, Response::HTTP_OK, context:$context);
    }

    #[Route('/api/v1/listings/create', name: 'api.listings.create', methods: ['POST'])]
    #[IsGranted('ROLE_LANDLORD')]
    public function create(Request $request): JsonResponse
    {
        $entity = $this->propertyListingService->createAndSaveListing($request);
        return $this->json([], Response::HTTP_CREATED, ['Location' => '/listings/'.$entity->getId()]);
    }

    #[Route('/api/v1/listings/{id}/update', name: 'api.listings.update', methods: ['PUT'])]
    #[IsGranted('ROLE_LANDLORD')]
    public function update(Request $request, int $id): JsonResponse
    {
        $entity = $this->propertyListingService->updateAndSaveListing($id, $request);
        return $this->json([], Response::HTTP_NO_CONTENT, ['Location' => '/listings/' . $entity->getId()]);
    }

    #[Route('/api/v1/listings/{id}/delete', name: 'api.listings.delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_LANDLORD')]
    public function delete($id): JsonResponse
    {
        $this->denyAccessUnlessGranted(
            'update:listing',
            $this->propertyListingService->getById($id)
        );

        $this->propertyListingService->deleteListing($id);

        return $this->json([], Response::HTTP_OK);
    }
}