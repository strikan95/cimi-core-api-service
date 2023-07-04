<?php

namespace Cimi\ChatBundle\ChatUserProvider;

class ChatUserProviderService
{
    public function __construct(
        private readonly ChatUserProviderInterface $userProvider
    )
    {
    }

    public function getUserProvider(): ChatUserProviderInterface
    {
        return $this->userProvider;
    }
}