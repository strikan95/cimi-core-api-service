<?php

namespace App\PropertyListing\Controller;

use App\PropertyListing\PropertyListingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class PropertyListingReservationsController extends AbstractController
{
    public function __construct
    (
        private readonly PropertyListingService $propertyListingService
    ){
    }

    #[Route('/api/v1/listings/{id}/reservations', name: 'app.listings.reservations.create', methods: ['POST'])]
    public function createListingReservation(int $id, Request $request): JsonResponse
    {
        $reservation = $this->propertyListingService->createListingReservation($id, $request);
        return $this->json([], Response::HTTP_CREATED, ['Location' => '/reservations/'.$reservation->getId()]);
    }

    #[Route('/api/v1/listings/{id}/reservations', name: 'app.listings.reservations.index', methods: ['GET'])]
    public function getListingReservations(int $id): JsonResponse
    {
        $reservationDtos = $this->propertyListingService->getListingReservations($id);

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['reservations_basic'])
            ->toArray();

        return $this->json($reservationDtos, Response::HTTP_OK, context:$context);
    }
}