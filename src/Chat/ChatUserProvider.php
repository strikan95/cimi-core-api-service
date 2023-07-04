<?php

namespace App\Chat;

use App\AppUser\Repository\AppUserRepository;
use App\Security\User\CurrentUserProvider;
use Cimi\ChatBundle\ChatUserProvider\ChatUserProviderInterface;
use Cimi\ChatBundle\Entity\ChatUserInterface;

class ChatUserProvider implements ChatUserProviderInterface
{

    public function __construct(
        private readonly CurrentUserProvider $currentUserProvider,
        private readonly AppUserRepository $appUserRepository
    )
    {
    }

    public function getCurrentUser(): ?ChatUserInterface
    {
        return $this->currentUserProvider->get();
    }

    public function getUser($id): ?ChatUserInterface
    {
        return $this->appUserRepository->findByLocalId($id);
    }
}