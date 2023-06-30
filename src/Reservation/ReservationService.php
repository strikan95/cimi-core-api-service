<?php

namespace App\Reservation;

use App\PropertyListing\Entity\Factory\PropertyListingFactory;
use App\PropertyListing\Entity\PropertyListing;
use App\PropertyListing\Repository\PropertyListingRepository;
use App\Reservation\Dto\ReservationInputDto;
use App\Reservation\Dto\ReservationOutputDto;
use App\Reservation\Entity\Factory\ReservationEntityFactory;
use App\Reservation\Entity\Reservation;
use App\Reservation\Repository\ReservationRepository;
use App\Security\User\CurrentUserProvider;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReservationService
{
    public function __construct
    (
        private readonly ReservationRepository $reservationRepository,
        private readonly ReservationEntityFactory $reservationFactory,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface  $serializer,
        private readonly Security $security
    ){
    }

    public function getListingReservation(PropertyListing $listing): array
    {
        $reservationDtos = [];
        foreach ($listing->getReservations() as $reservation)
        {
            $reservationDtos[] = new ReservationOutputDto($reservation);
        }

        return $reservationDtos;
    }

    public function createReservationEntity(Request $request): Reservation
    {
        // Deserialize and validate DTO
        $content = $request->getContent();
        $dto = $this->getValidatedDto(
            $content,
            ['create'],
            ReservationInputDto::class
        );

        return $this->reservationFactory->buildOrUpdate($dto);
    }

    public function updateReservation(int $id, Request $request, bool $save = true): Reservation
    {
        $reservation = $this->findListingOrFail($id);
        $dto = $this->getValidatedDto(
            $request->getContent(),
            ['update'],
            ReservationInputDto::class
        );

        $updatedReservation = $this->reservationFactory->buildOrUpdate($dto, $reservation);
        if($save)
            $this->dbSave($updatedReservation);

        return $reservation;
    }

    public function deleteReservation(int $id): void
    {
        $reservation = $this->findListingOrFail($id);

        $this->dbRemove($reservation);
    }

    private function dbSave(Reservation $reservation): void
    {
        $this->reservationRepository->save($reservation, true);
    }

    private function dbRemove(Reservation $reservation): void
    {
        $this->reservationRepository->remove($reservation, true);
    }

    private function findListingOrFail($id): Reservation
    {
        $reservation = $this->reservationRepository->findById($id);
        if(null === $reservation)
            throw new NotFoundHttpException("Reservation with id ".$id." couldn't be found.");

        return $reservation;
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