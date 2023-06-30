<?php

namespace App\Security\User;

use App\AppUser\Entity\AppUser as AppUserEntity;
use App\AppUser\Repository\AppUserRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class CurrentUserProvider
{
    public function __construct(
        private readonly AppUserRepository $userRepository,
        private readonly TokenStorageInterface $tokenStorage
    ){
    }

    public function get(): ?AppUserEntity
    {
        return $this->fromToken($this->tokenStorage->getToken());
    }

    public function fromToken(TokenInterface $token): ?AppUserEntity
    {
        if (!$token || !$token->getUser() instanceof TokenUser) {
            return null;
        }

        return $this->userRepository->findByAuthIdentifier($token->getUserIdentifier());
    }
}