<?php

namespace App\Controller;

use App\DTO\Builder\PropertyListingBuilder;
use App\DTO\Response\Property\CreatePropertyDto;
use App\Entity\Property;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PropertyListingController extends AbstractApiController
{
    #[Route('/api/v1/listings/{id}', name: 'api.listing.get.one', methods: ['GET'])]
    public function findById(Request $request, int $id): JsonResponse
    {
        return $this->resolveGetAction($request, $id);
    }

    #[Route('/api/v1/listings', name: 'api.listing.add', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        return $this->resolveCreateAction($request);
    }

    protected function getInputDtoClassName(): string
    {
        return CreatePropertyDto::class;
    }

    protected function getEntityClassName(): string
    {
        return Property::class;
    }
}
