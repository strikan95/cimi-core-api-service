<?php

namespace Cimi\ChatBundle\Voter;

use Cimi\ChatBundle\Entity\Conversation;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ParticipationVoter extends Voter
{

    const ADD_PARTICIPANT = 'participation:add';

    const REMOVE_PARTICIPANT = 'participation:remove';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::ADD_PARTICIPANT, self::REMOVE_PARTICIPANT])) {
            return false;
        }

        if (!$subject instanceof Conversation) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        // TODO: Implement voteOnAttribute() method.
    }
}