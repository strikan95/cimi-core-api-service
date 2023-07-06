<?php

namespace Cimi\ChatBundle\Manager;

use Cimi\ChatBundle\Entity\Conversation;
use Symfony\Component\HttpFoundation\Request;

interface ChatManagerInterface
{
    public function startConversation(Request $request): Conversation;
}