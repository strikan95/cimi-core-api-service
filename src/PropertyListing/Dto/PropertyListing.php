<?php

namespace App\PropertyListing\Dto;

use App\Amenity\Dto\Amenity as AmenityDto;
use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

class PropertyListing
{
    #[Groups(['listings_basic', 'listings_extended'])]
    protected int $id;

    #[Groups(['listings_basic', 'listings_extended'])]
    protected string $title;

    #[Groups(['listings_extended'])]
    protected string $description;

    #[Groups(['listings_with_amenities'])]
    protected array $amenities;

    #[Groups(['listings_extended'])]
    protected DateTime $createdAt;

    public function __construct(PropertyListingEntity $entity = null)
    {
        if($entity !== null)
        {
            $this->load($entity);
        }
    }

    public function load(PropertyListingEntity $entity): void
    {
        $this->id = $entity->getId();
        $this->title = $entity->getTitle();
        $this->description = $entity->getDescription();
        $this->createdAt = $entity->getCreatedAt();

        $this->setAmenities($entity->getAmenities());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getAmenities(): array
    {
        return $this->amenities;
    }

    public function setAmenities(Collection $amenities): void
    {
        foreach ($amenities as $amenity)
        {
            $this->amenities[] = new AmenityDto($amenity);
        }
    }
}