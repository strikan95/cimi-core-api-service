<?php

namespace App\PropertyListing\Controller;

use App\PropertyListing\Dto\PropertyListing as PropertyListingDTO;
use App\PropertyListing\PropertyListingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PropertyListingController extends AbstractController
{
    public function __construct
    (
        private readonly PropertyListingService $propertyListingService,
        private readonly SerializerInterface  $serializer,
        private readonly ValidatorInterface $validator,
    )
    {
    }

    #[Route('/api/v1/listings', name: 'api.listings.index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([], 200);
    }

    #[Route('/api/v1/listings/{id}', name: 'api.listings.get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $entity = $this->propertyListingService->getById($id);

        $dto = new PropertyListingDTO($entity);

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['listings_extended', 'listings_with_amenities'])
            ->toArray();

        return $this->json($dto, Response::HTTP_OK, context:$context);
    }

    #[Route('/api/v1/listings', name: 'api.listings.create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $content = $request->getContent();
        $dto = $this->serializer->deserialize
        (
            $content,
            PropertyListingDTO::class,
            'json'
        );

        //Validate
        $errors = $this->validator->validate($dto, null, ['create']);
        if(count($errors) > 0)
        {
            throw new BadRequestHttpException('Error when validating.');
        }

        $entity = $this->propertyListingService->store($dto);

        return $this->json([], Response::HTTP_CREATED, ['Location' => '/listings/'.$entity->getId()]);
    }

    #[Route('/api/v1/listings/{id}', name: 'api.listings.patch', methods: ['PUT'])]
    public function update($id): JsonResponse
    {
        return $this->json([], 200);
    }

    #[Route('/api/v1/listings/{id}', name: 'api.listings.patch', methods: ['PATCH'])]
    public function patch($id): JsonResponse
    {
        return $this->json([], 200);
    }

    #[Route('/api/v1/listings/{id}', name: 'api.listings.delete', methods: ['DELETE'])]
    public function delete($id): JsonResponse
    {
        return $this->json([], 200);
    }
}