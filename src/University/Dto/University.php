<?php

namespace App\University\Dto;

use DateTime;
use App\University\Entity\University as UniversityEntity;
use Symfony\Component\Serializer\Annotation\Groups;

class University
{
    #[Groups(['universities_extended'])]
    protected int $id;

    #[Groups(['universities_basic', 'universities_extended'])]
    protected string $name;

    #[Groups(['universities_basic', 'universities_extended'])]
    protected string $city;

    #[Groups(['universities_basic', 'universities_extended'])]
    protected string $fullAddress;

    #[Groups(['universities_extended'])]
    protected string $lat;

    #[Groups(['universities_extended'])]
    protected string $lon;

    #[Groups(['universities_extended'])]
    protected DateTime $createdAt;

    public function __construct(UniversityEntity $entity = null)
    {
        if($entity !== null)
        {
            $this->load($entity);
        }
    }

    public function load(UniversityEntity $entity): void
    {
        $this->id = $entity->getId();
        $this->name = $entity->getName();
        $this->city = $entity->getCity();
        $this->fullAddress = $entity->getFullAddress();
        $this->lat = $entity->getLat();
        $this->lon = $entity->getLon();
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

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getFullAddress(): string
    {
        return $this->fullAddress;
    }

    public function setFullAddress(string $fullAddress): void
    {
        $this->fullAddress = $fullAddress;
    }

    public function getLat(): string
    {
        return $this->lat;
    }

    public function setLat(string $lat): void
    {
        $this->lat = $lat;
    }

    public function getLon(): string
    {
        return $this->lon;
    }

    public function setLon(string $lon): void
    {
        $this->lon = $lon;
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