<?php

namespace App\AppUser\Entity;

use App\AppUser\Repository\AppUserRepository;
use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: AppUserRepository::class)]
class AppUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    private ?string $auth0Identifier = null;

    private ?string $role = null;

    #[ORM\Column(unique: true)]
    private ?string $email = null;
    
    #[ORM\Column]
    private ?string $displayName = null;

    #[ORM\Column]
    private ?string $firstName = null;

    #[ORM\Column]
    private ?string $lastName = null;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: PropertyListingEntity::class)]
    private Collection $listings;

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

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUserIdentifier(): ?string
    {
        return $this->auth0Identifier;
    }

    public function setUserIdentifier(?string $userIdentifier): void
    {
        $this->auth0Identifier = $userIdentifier;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): void
    {
        $this->displayName = $displayName;
    }


    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getListings(): Collection
    {
        return $this->listings;
    }

    public function setListings(Collection $listings): void
    {
        $this->listings = $listings;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): void
    {
        $this->role = $role;
    }
}