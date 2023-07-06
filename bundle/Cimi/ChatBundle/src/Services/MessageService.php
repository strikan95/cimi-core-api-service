<?php

namespace Cimi\ChatBundle\Services;

use Cimi\ChatBundle\ChatUserProvider\ChatUserProviderService;
use Cimi\ChatBundle\Entity\ChatUserInterface;
use Cimi\ChatBundle\Entity\Conversation;
use Cimi\ChatBundle\Entity\Factory\MessageFactory;
use Cimi\ChatBundle\Entity\Message;
use Cimi\ChatBundle\Repository\MessageRepository;
use Cimi\ChatBundle\Repository\ParticipantRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MessageService
{
    public function __construct(
        private readonly MessageRepository $messageRepository,
        private readonly ParticipantRepository $participantRepository,
        private readonly ChatUserProviderService $userProviderService
    )
    {
    }

    public function sendMessage(Conversation $conversation, ChatUserInterface $sender, string $body): Message
    {
        $participant = $this->participantRepository->findIfIsParticipant($conversation, $sender);
        if(null === $participant)
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'User is not a participant');

        $message = MessageFactory::buildMessage($conversation, $participant, $body);
        $this->dbSave($message);

        return $message;
    }

    public function deleteMessage(int $id): void
    {
        $message = $this->messageRepository->findById($id);
        if(null === $message)
            throw new HttpException(Response::HTTP_NOT_FOUND, 'Message with id ' . $id . ' not found');

        $canDelete = $message->getSender()->getUser() === $this->userProviderService->getUserProvider()->getCurrentUser();
        if (!$canDelete)
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Cannot delete message.');

        $this->dbRemove($message);
    }

    private function dbSave(Message $message): void
    {
        $this->messageRepository->save($message, true);
    }

    private function dbRemove(Message $message): void
    {
        $this->messageRepository->remove($message, true);
    }
}