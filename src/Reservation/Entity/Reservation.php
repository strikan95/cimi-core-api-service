<?php

namespace App\Reservation\Entity;

use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use App\Reservation\Repository\ReservationRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(type: 'date')]
    private ?DateTime $startDate = null;

    #[ORM\Column(type: 'date')]
    private ?DateTime $endDate = null;
    #[ORM\ManyToOne(targetEntity: PropertyListingEntity::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'listing_id', referencedColumnName: 'id')]
    private ?PropertyListingEntity $listing = null;
    #[ORM\Column(name: "createdAt", type: "datetime")]
    private ?DateTime $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getListing(): ?PropertyListingEntity
    {
        return $this->listing;
    }

    public function setListing(?PropertyListingEntity $listing): void
    {
        $this->listing = $listing;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
