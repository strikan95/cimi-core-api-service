<?php

namespace Cimi\ChatBundle\Entity\Factory;

use Cimi\ChatBundle\Entity\ChatUserInterface;
use Cimi\ChatBundle\Entity\Conversation;
use Cimi\ChatBundle\Entity\Participation;

class ParticipantFactory
{
    public static function buildParticipant(ChatUserInterface $user, Conversation $conversation): Participation
    {
        return new Participation($user, $conversation);
    }
}