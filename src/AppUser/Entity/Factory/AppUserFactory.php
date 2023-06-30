<?php

namespace App\AppUser\Entity\Factory;

use App\ApiTools\EntityFactory\AbstractEntityFactory;
use App\AppUser\Dto\AppUser as AppUserDto;
use App\AppUser\Entity\AppUser as AppUserEntity;

class AppUserFactory extends AbstractEntityFactory
{
    function getEntityClassName(): string
    {
        return AppUserEntity::class;
    }

    /** @param AppUserEntity $target */
    function onCreatePreLoad($source, mixed $target, ?array $settings): void
    {
        if(!isset($settings['auth0Identifier']))
            throw new \LogicException('Auth0 identifier must be set');

        $target->setUserIdentifier($settings['auth0Identifier']);
    }
}