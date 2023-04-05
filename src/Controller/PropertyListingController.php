<?php

namespace App\Controller;

use App\DTO\Response\Property\CreatePropertyDto;
use App\Entity\Property;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/api/v1/listings/{id}', name: 'api.listing.update', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        return $this->resolveUpdateAction($request, $id);
    }

    #[Route('/api/v1/listings/{id}', name: 'api.listing.delete', methods: ['DELETE'])]
    public function delete(Request $request, int $id): JsonResponse
    {
        return $this->resolveDeleteAction($request, $id);
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
