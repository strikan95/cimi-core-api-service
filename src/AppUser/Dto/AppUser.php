<?php

namespace App\AppUser\Dto;

use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;
use App\AppUser\Entity\AppUser as AppUserEntity;

class AppUser
{
    // ID provided by auth0
    protected string $userIdentifier;

    #[Groups(['app_user_basic', 'app_user_extended'])]
    protected int $id;

    #[Groups(['app_user_extended'])]
    protected string $email;

    #[Groups(['app_user_basic', 'app_user_extended'])]
    protected string $displayName;

    #[Groups(['app_user_private'])]
    protected string $firstName;

    #[Groups(['app_user_private'])]
    protected string $lastName;

/*    #[Groups(['app_user_with_listings'])]
    protected array $listings;*/

    #[Groups(['app_user_extended'])]
    protected DateTime $createdAt;

    public function __construct(AppUserEntity $entity = null)
    {
        if($entity !== null)
        {
            $this->load($entity);
        }
    }

    public function load(AppUserEntity $entity): void
    {
        $this->id = $entity->getId();
        $this->userIdentifier = $entity->getUserIdentifier();
        $this->displayName = $entity->getDisplayName();
        $this->firstName = $entity->getFirstName();
        $this->lastName = $entity->getLastName();
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

    public function getUserIdentifier(): string
    {
        return $this->userIdentifier;
    }

    public function setUserIdentifier(string $userIdentifier): void
    {
        $this->userIdentifier = $userIdentifier;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
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