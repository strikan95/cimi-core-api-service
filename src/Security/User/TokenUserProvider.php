<?php

namespace App\Security\User;

use App\Services\JWTServiceInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenUserProvider implements UserProviderInterface
{
    public function __construct(
        private readonly JWTServiceInterface $JWTService
    )
    {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $tokenData = $this->JWTService->decodeBearerToken($identifier);

        return new TokenUser(
            $tokenData
        );
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return TokenUser::class === $class;
    }
}