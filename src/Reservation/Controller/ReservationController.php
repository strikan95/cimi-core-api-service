<?php

namespace App\Reservation\Controller;

use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use App\PropertyListing\PropertyListingService;
use App\Reservation\Dto\Reservation as ReservationDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends AbstractController
{
    public function __construct(
        private readonly PropertyListingService $propertyListingService,
        private readonly SerializerInterface  $serializer,
        private readonly ValidatorInterface $validator)
    {
    }

    #[Route('/api/v1/listings/{id}/reservations', name: 'app.listings.reservations', methods: ['GET'])]
    public function getReservationsForAListing(int $id): JsonResponse
    {
        /** @var PropertyListingEntity $listing */
       $listing =  $this->propertyListingService->getById($id);

        $dtos = [];
        foreach ($listing->getReservations() as $reservation)
        {
            $dto = new ReservationDto($reservation);
            $dtos[] = $dto;
        }

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['reservations_basic'])
            ->toArray();

        return $this->json($dtos, Response::HTTP_OK, context:$context);
    }
}
