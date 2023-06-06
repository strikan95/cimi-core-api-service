<?php

namespace App\Security\User;

use App\AppUser\Entity\AppUser as AppUserEntity;
use Symfony\Component\Security\Core\User\UserInterface;

class TokenUser implements UserInterface
{
    const ROLE_TOKEN_USER = 'ROLE_TOKEN_USER';

    private array $tokenData;

    public function __construct(array $tokenData)
    {
        $this->tokenData = $tokenData;
    }

    public function getUserIdentifier(): string
    {
        return $this->tokenData['sub'] ?? '';
    }

    public function getRoles(): array
    {
        $roles = array_merge($this->tokenData['cimi-core/roles'], [self::ROLE_TOKEN_USER]);

        return $roles;
    }

    public function eraseCredentials()
    {
        return null;
    }
}