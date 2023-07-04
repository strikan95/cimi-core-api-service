<?php

namespace Cimi\ChatBundle\Services;

use Cimi\ChatBundle\ChatUserProvider\ChatUserProviderService;
use Cimi\ChatBundle\Entity\Conversation;
use Cimi\ChatBundle\Entity\Message;
use Cimi\ChatBundle\Entity\Participation;
use Cimi\ChatBundle\Repository\ConversationRepository;
use Cimi\ChatBundle\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;

class ChatService
{
    public function __construct(
        private readonly ChatUserProviderService $userProviderService,
        private readonly ConversationRepository $conversationRepository,
        private readonly ParticipantRepository $participantRepository
    )
    {
    }

    public function startChat(Request $request): Conversation
    {
        $content = $request->getContent();
        $data = json_decode($content, true);
        $participants = $this->getParticipants($data['to']);

        $conversation = new Conversation(
            $participants,
            new Message($data['message'], $participants[0])
        );

        $this->conversationRepository->save($conversation, true);

        return $conversation;
    }

    public function sendMessage(int $id, Request $request): ?Conversation
    {
        $content = $request->getContent();
        $data = json_decode($content, true);

        $currentUser = $this->userProviderService->getUserProvider()->getCurrentUser();

        $sender = $this->participantRepository->findByUser(
            $this->userProviderService->getUserProvider()->getCurrentUser()
        );

        $conversation = $this->conversationRepository->findById($id);

        $conversation->addMessage(
            new Message($data['message'], $sender)
        );

        $this->conversationRepository->save($conversation, true);

        return $conversation;
    }


    public function getParticipants(array $ids): array
    {
        $participants = [];

        // Initiator
        $participants[] =  new Participation(
            $this->userProviderService->getUserProvider()->getCurrentUser()
        );
        foreach ($ids as $id)
        {
            $user = $this->userProviderService->getUserProvider()->getUser($id);
            $participants[] = new Participation($user);
        }

        return $participants;
    }

    public function test(): string
    {
        $user = $this->userProviderService->getUserProvider()->getCurrentUser();

        return 'hello';
    }
}