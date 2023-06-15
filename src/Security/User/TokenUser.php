<?php

namespace App\Security\User;

use App\AppUser\Entity\AppUser as AppUserEntity;
use Symfony\Component\Security\Core\User\UserInterface;

class TokenUser implements UserInterface
{
    const NAMESPACE = 'cimi-core';
    const AUTH_WITH_TOKEN_ROLE = 'ROLE_TOKEN_USER';
    const FULLY_REGISTERED_ROLE = 'ROLE_FULLY_REGISTERED';

    public function __construct(private readonly array $tokenData)
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->tokenData['sub'] ?? '';
    }

    public function getRoles(): array
    {
        $response = [];
        $response[] = self::AUTH_WITH_TOKEN_ROLE;
        if(isset($this->tokenData[self::NAMESPACE.'/app_metadata']['role']))
        {
            $response[] = self::FULLY_REGISTERED_ROLE;
            $response[] = $this->tokenData[self::NAMESPACE.'/app_metadata']['role'];
        }

        return array_unique(array_values($response));
    }

    public function eraseCredentials()
    {
        return null;
    }
}