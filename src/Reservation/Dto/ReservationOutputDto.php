<?php

namespace App\Reservation\Dto;

use App\Reservation\Entity\Reservation as ReservationEntity;
use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;

class ReservationOutputDto
{
    #[Groups(['reservations_basic', 'reservations_extended', 'listings_with_reservations'])]
    public ?int $id;

    #[Groups(['reservations_basic', 'reservations_extended', 'listings_with_reservations'])]
    public ?DateTime $startDate;

    #[Groups(['reservations_basic', 'listings_with_reservations'])]
    public ?DateTime $endDate;

    #[Groups(['reservations_extended', 'listings_with_reservations'])]
    public ?DateTime $createdAt;

    public function __construct(ReservationEntity $entity = null)
    {
        if($entity !== null)
        {
            $this->buildFromEntity($entity);
        }
    }

    public function buildFromEntity(ReservationEntity $entity): void
    {
        $this->id = $entity->getId();
        $this->startDate = $entity->getStartDate();
        $this->endDate = $entity->getEndDate();
        $this->createdAt = $entity->getCreatedAt();
    }
}