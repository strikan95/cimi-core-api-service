<?php

namespace Cimi\ChatBundle\Entity\Factory;

use Cimi\ChatBundle\Entity\Conversation;

class ConversationFactory
{
    public static function buildConversation(array $users): Conversation
    {
        return new Conversation($users);
    }
}