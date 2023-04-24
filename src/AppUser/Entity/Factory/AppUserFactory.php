<?php

namespace App\AppUser\Entity\Factory;

use App\AppUser\Dto\AppUser as AppUserDto;
use App\AppUser\Entity\AppUser as AppUserEntity;

class AppUserFactory
{
    public static function build(AppUserDto $dto, AppUserEntity $entity = null): ?AppUserEntity
    {
        if(null === $entity)
        {
            $entity = new AppUserEntity();
            $entity->setUserIdentifier($dto->getUserIdentifier());
        }

        self::loadFromDto($dto, $entity);

        return $entity;
    }

    private static function loadFromDto(AppUserDto $dto, AppUserEntity $entity): void
    {
        $entity->setDisplayName($dto->getDisplayName());
        $entity->setFirstName($dto->getFirstName());
        $entity->setLastName($dto->getLastName());
    }
}