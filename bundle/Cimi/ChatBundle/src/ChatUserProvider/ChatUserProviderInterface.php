<?php

namespace Cimi\ChatBundle\ChatUserProvider;

use Cimi\ChatBundle\Entity\ChatUserInterface;

interface ChatUserProviderInterface
{
    public function getCurrentUser(): ?ChatUserInterface;
    public function getUser($id): ?ChatUserInterface;
}