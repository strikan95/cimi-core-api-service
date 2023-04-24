<?php

namespace App\AppUser\UserProvider;

use App\AppUser\Entity\AppUser as AppUserEntity;
use App\AppUser\Repository\AppUserRepository;
use App\Security\Auth0\Auth0User;
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
        if (!$token || !$token->getUser() instanceof Auth0User) {
            return null;
        }

        return $this->userRepository->findOneBy(['userIdentifier' => $token->getUserIdentifier()]);
    }
}