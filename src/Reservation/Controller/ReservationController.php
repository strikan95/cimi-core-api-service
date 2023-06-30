<?php

namespace App\Reservation\Controller;

use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use App\PropertyListing\PropertyListingService;
use App\Reservation\Dto\Reservation as ReservationDto;
use App\Reservation\ReservationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends AbstractController
{
    public function __construct(
        private readonly ReservationService $reservationService
    )
    {
    }

    #[Route('/api/v1/reservations/{id}', name: 'app.reservations.update', methods: ['PUT'])]
    #[IsGranted('ROLE_FULLY_REGISTERED')]
    public function updateReservation(int $id, Request $request): JsonResponse
    {
        $reservation = $this->reservationService->updateReservation($id, $request);

        return $this->json([], Response::HTTP_OK, ['Location' => '/reservations/' . $reservation->getId()]);
    }

    #[Route('/api/v1/reservations/{id}', name: 'app.reservations.update', methods: ['DELETE'])]
    #[IsGranted('ROLE_FULLY_REGISTERED')]
    public function deleteReservation(int $id): JsonResponse
    {
        $this->reservationService->deleteReservation($id);

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
