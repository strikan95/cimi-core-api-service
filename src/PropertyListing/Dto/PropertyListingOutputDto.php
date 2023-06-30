<?php

namespace App\PropertyListing\Dto;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Amenity\Dto\Amenity as AmenityDto;
use App\Reservation\Dto\Reservation as ReservationDto;
class PropertyListingOutputDto
{
    #[Groups(['listings_basic', 'listings_extended'])]
    public ?int $id;

    #[Groups(['listings_basic', 'listings_extended'])]
    public ?string $title;

    #[Groups(['listings_extended'])]
    public ?string $description;

    #[Groups(['listings_basic', 'listings_extended'])]
    public ?int $price;

    #[Groups(['listings_basic', 'listings_extended'])]
    public ?string $lat;

    #[Groups(['listings_basic', 'listings_extended'])]
    public ?string $lon;

    #[Groups(['listings_with_amenities'])]
    public ?array $amenities;

    #[Groups(['listings_with_reservations'])]
    public ?array $reservations;

    #[Groups(['listings_extended'])]
    public ?DateTime $createdAt;

    public function __construct($entity = null)
    {
        if($entity)
        {
            $this->buildFromEntity($entity);
        }
    }

    private function buildFromEntity($entity): void
    {
        $this->id = $entity->getId();
        $this->title = $entity->getTitle();
        $this->description = $entity->getDescription();
        $this->price = $entity->getPrice();
        $this->lat = $entity->getLat();
        $this->lon = $entity->getLon();

        $this->createdAt = $entity->getCreatedAt();

        $this->setAmenityDtos($entity->getAmenities());
        $this->setReservationDtos($entity->getReservations());
    }

    public function setAmenityDtos(Collection $amenities): void
    {
        foreach ($amenities as $amenity)
        {
            $this->amenities[] = new AmenityDto($amenity);
        }
    }

    private function setReservationDtos(Collection $reservations): void
    {
        foreach ($reservations as $reservation)
        {
            $this->reservations[] = new ReservationDto($reservation);
        }
    }
}