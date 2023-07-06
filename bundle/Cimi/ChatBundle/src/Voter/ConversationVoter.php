<?php

namespace Cimi\ChatBundle\Voter;

use Cimi\ChatBundle\ChatUserProvider\ChatUserProviderService;
use Cimi\ChatBundle\Entity\ChatUserInterface;
use Cimi\ChatBundle\Entity\Conversation;
use Cimi\ChatBundle\Entity\Participation;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ConversationVoter extends Voter
{
    const VIEW = 'conversation:view';

    public function __construct(
        private readonly ChatUserProviderService $userProviderService
    )
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::VIEW])) {
            return false;
        }

        if (!$subject instanceof Conversation) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $this->userProviderService->getUserProvider()->getCurrentUser();

        return match($attribute) {
            self::VIEW => $this->canView($subject, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canView(Conversation $conversation, ChatUserInterface $user): bool
    {
        if (!$this->isParticipant($conversation, $user))
            return false;

        return true;
    }

    private function isParticipant(Conversation $conversation, ChatUserInterface $user): bool
    {
        /** @var Participation $participant */
        foreach ($conversation->getParticipants() as $participant)
        {
            if ($participant->getUser() === $user)
                return true;
        }

        return false;
    }
}