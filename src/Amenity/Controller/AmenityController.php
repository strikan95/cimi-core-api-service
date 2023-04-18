<?php

namespace App\Amenity\Controller;

use App\Amenity\AmenityService;
use App\Amenity\Dto\Amenity as AmenityDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AmenityController extends AbstractController
{
    public function __construct
    (
        private readonly AmenityService $amenityService,
        private readonly SerializerInterface  $serializer,
        private readonly ValidatorInterface $validator,
    )
    {
    }

    #[Route('/api/v1/amenities/{id}', name: 'api.amenities.get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $entity = $this->amenityService->getById($id);

        $dto = new AmenityDto($entity);

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['amenities_extended'])
            ->toArray();

        return $this->json($dto, Response::HTTP_OK, context:$context);
    }

    #[Route('/api/v1/amenities', name: 'api.amenities.create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $content = $request->getContent();
        $dto = $this->serializer->deserialize
        (
            $content,
            AmenityDto::class,
            'json'
        );

        //Validate
        $errors = $this->validator->validate($dto, null, ['create']);
        if(count($errors) > 0)
        {
            throw new BadRequestHttpException('Error when validating.');
        }

        $entity = $this->amenityService->store($dto);

        return $this->json([], Response::HTTP_CREATED, ['Location' => '/amenities/' . $entity->getId()]);
    }
}
