<?php

namespace App\Reservation\Dto;

use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Reservation\Entity\Reservation as ReservationEntity;

class Reservation
{
    #[Groups(['reservations_basic', 'reservations_extended', 'listings_with_reservations'])]
    protected int $id;

    #[Groups(['reservations_basic', 'reservations_extended', 'listings_with_reservations'])]
    protected DateTime $startDate;

    #[Groups(['reservations_basic', 'listings_with_reservations'])]
    protected DateTime $endDate;

    #[Groups(['reservations_extended', 'listings_with_reservations'])]
    protected DateTime $createdAt;

    public function __construct(ReservationEntity $entity = null)
    {
        if($entity !== null)
        {
            $this->load($entity);
        }
    }

    public function load(ReservationEntity $entity): void
    {
        $this->id = $entity->getId();
        $this->startDate = $entity->getStartDate();
        $this->endDate = $entity->getEndDate();
        $this->createdAt = $entity->getCreatedAt();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}