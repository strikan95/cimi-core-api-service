<?php

namespace App\AppUser\Dto;

use App\AppUser\Entity\AppUser as AppUserEntity;
use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;

class AppUserOutputDto
{
    // ID provided by auth0
    public ?string $userIdentifier;

    #[Groups(['app_user_basic', 'app_user_extended'])]
    public ?int $id;

    #[Groups(['app_user_basic', 'app_user_extended'])]
    public ?string $role;

    #[Groups(['app_user_basic', 'app_user_extended'])]
    public ?string $displayName;

    #[Groups(['app_user_private'])]
    public ?string $firstName;

    #[Groups(['app_user_private'])]
    public ?string $lastName;

    /*    #[Groups(['app_user_with_listings'])]
        protected array $listings;*/

    #[Groups(['app_user_extended'])]
    public DateTime $createdAt;

    public function __construct(AppUserEntity $entity = null)
    {
        if($entity)
        {
            $this->buildFromEntity($entity);
        }
    }

    private function buildFromEntity(AppUserEntity $entity): void
    {
        $this->id = $entity->getId();
        $this->userIdentifier = $entity->getUserIdentifier();
        $this->role = $entity->getRole();

        $this->displayName = $entity->getDisplayName();
        $this->firstName = $entity->getFirstName();
        $this->lastName = $entity->getLastName();

        $this->createdAt = $entity->getCreatedAt();
    }

}