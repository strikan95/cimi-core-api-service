<?php

namespace App\AppUser\Dto;

use App\AppUser\Entity\AppUser as AppUserEntity;
use Symfony\Component\Serializer\Annotation\Groups;

class AppUserInputDto
{
    #[Groups(['create'])]
    public ?string $role;

    #[Groups(['create'])]
    public ?string $displayName;

    #[Groups(['create', 'update'])]
    public ?string $firstName;

    #[Groups(['create', 'update'])]
    public ?string $lastName;
}