<?php

namespace App\PropertyListing\Controller;

use App\PropertyListing\Dto\PropertyListing as PropertyListingDTO;
use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use App\PropertyListing\PropertyListingService;
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
        private readonly SerializerInterface  $serializer,
        private readonly ValidatorInterface $validator,
    )
    {
    }

    #[Route('/api/v1/listings/index', name: 'api.listings.index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([], 200);
    }

    #[Route('/api/v1/listings/index/{id}', name: 'api.listings.get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $entity = $this->propertyListingService->getById($id);

        $dto = new PropertyListingDTO($entity);

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['listings_extended', 'listings_with_amenities'])
            ->toArray();

        return $this->json($dto, Response::HTTP_OK, context:$context);
    }


    #[Route('/api/v1/listings/create', name: 'api.listings.create', methods: ['POST'])]
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

    #[Route('/api/v1/listings/{id}/update', name: 'api.listings.update', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $this->denyAccessUnlessGranted(
            'update:listing',
            $this->propertyListingService->getById($id)
        );

        $content = $request->getContent();
        /** @var PropertyListingDTO $dto */
        $dto = $this->serializer->deserialize
        (
            $content,
            PropertyListingDTO::class,
            'json'
        );
        $dto->setId($id);

        //Validate
        $errors = $this->validator->validate($dto, null, ['update']);
        if(count($errors) > 0)
        {
            throw new BadRequestHttpException('Error when validating.');
        }

        $entity = $this->propertyListingService->update($dto);

        return $this->json([], Response::HTTP_NO_CONTENT, ['Location' => '/listings/' . $entity->getId()]);
    }

    #[Route('/api/v1/listings/{id}/delete', name: 'api.listings.delete', methods: ['DELETE'])]
    public function delete($id): JsonResponse
    {
        $this->denyAccessUnlessGranted(
            'update:listing',
            $this->propertyListingService->getById($id)
        );

        $this->propertyListingService->delete($id);

        return $this->json([], Response::HTTP_OK);
    }
}