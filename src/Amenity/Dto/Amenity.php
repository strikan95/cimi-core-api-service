<?php

namespace App\Amenity\Dto;

use App\Amenity\Entity\Amenity as AmenityEntity;
use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;

class Amenity
{
    #[Groups(['amenities_basic', 'amenities_extended'])]
    protected int $id;

    #[Groups(['amenities_basic', 'amenities_extended', 'listings_with_amenities'])]
    protected string $name;

    #[Groups(['amenities_extended'])]
    protected DateTime $createdAt;

    public function __construct(AmenityEntity $entity = null)
    {
        if($entity !== null)
        {
            $this->load($entity);
        }
    }

    public function load(AmenityEntity $entity): void
    {
        $this->id = $entity->getId();
        $this->name = $entity->getName();
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
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