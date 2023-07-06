<?php

namespace Cimi\ChatBundle\Services;

use Cimi\ChatBundle\Entity\ChatUserInterface;
use Cimi\ChatBundle\Entity\Conversation;
use Cimi\ChatBundle\Entity\Factory\ConversationFactory;
use Cimi\ChatBundle\Repository\ConversationRepository;
use Cimi\ChatBundle\Repository\ParticipantRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ConversationService
{
    public function __construct(
        private readonly ConversationRepository $conversationRepository,
        private readonly ParticipantRepository $participantRepository
    )
    {
    }

    public function get(int $id): Conversation
    {
        $conversation = $this->conversationRepository->findById($id);
        if(null === $conversation)
            throw new HttpException(Response::HTTP_NOT_FOUND, 'Conversation with id ' . $id . ' not found.');

        return $conversation;
    }

    public function create(array $users, ChatUserInterface $admin): Conversation
    {
        $conversation = ConversationFactory::buildConversation($users, $admin);
        $this->dbSave($conversation);

        return $conversation;
    }

    public function getAll(ChatUserInterface $user)
    {
        return $this->conversationRepository->getAllUsersConversations($user->getId());
    }

    private function dbSave(Conversation $conversation): void
    {
        $this->conversationRepository->save($conversation, true);
    }
}