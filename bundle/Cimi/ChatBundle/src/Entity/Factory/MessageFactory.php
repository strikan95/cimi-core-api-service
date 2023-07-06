<?php

namespace Cimi\ChatBundle\Entity\Factory;

use Cimi\ChatBundle\Entity\Conversation;
use Cimi\ChatBundle\Entity\Message;
use Cimi\ChatBundle\Entity\Participation;

class MessageFactory
{
    public static function buildMessage(Conversation $conversation, Participation $participant, string $body): Message
    {
        return new Message($conversation, $participant, $body);
    }
}