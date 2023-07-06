<?php

namespace Cimi\ChatBundle\Manager;

use Cimi\ChatBundle\ChatUserProvider\ChatUserProviderService;
use Cimi\ChatBundle\Entity\Conversation;
use Cimi\ChatBundle\Entity\Message;
use Cimi\ChatBundle\Services\ConversationService;
use Cimi\ChatBundle\Services\MessageService;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class ChatManager implements ChatManagerInterface
{
    public function __construct(
        private readonly ConversationService $conversationService,
        private readonly MessageService $messageService,
        private readonly ChatUserProviderService $userProviderService
    )
    {
    }

    public function getCurrentUserConversations(): array
    {
        // Get all conversations for current user
        $currentUser = $this->userProviderService->getUserProvider()->getCurrentUser();
        $conversations = $this->conversationService->getAll($currentUser);

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['conversation_info'])
            ->toArray();


        return [
            'results' => $conversations,
            'context' => $context
        ];
    }

    public function getConversationInfo(int $id): array
    {
        $conversation = $this->conversationService->get($id);

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['conversation_info', 'conversation_with_participants'])
            ->toArray();

        return [
            'results' => $conversation,
            'context' => $context
        ];
    }

    public function getAllMessages(int $id): array
    {
        $conversation = $this->conversationService->get($id);

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['conversation_with_messages'])
            ->toArray();

        return [
            'results' => $conversation,
            'context' => $context
        ];
    }

    public function startConversation(Request $request): Conversation
    {
        $startChatPayload = $this->resolveStartConversationPayload($request);

        $conversation = $this->conversationService->create(
            $startChatPayload['participants'],
            $startChatPayload['admin']
        );

        $message = $this->messageService->sendMessage(
            $conversation,
            $startChatPayload['initialMessage']['from'],
            $startChatPayload['initialMessage']['body']
        );
        return $conversation;
    }

    public function sendMessage(int $id, Request $request): Conversation
    {
        $sendMessagePayload = $this->resolveSendMessagePayload($request);
        $conversation = $this->conversationService->get($id);

        $this->messageService->sendMessage(
            $conversation,
            $sendMessagePayload['from'],
            $sendMessagePayload['body']
        );

        return $conversation;
    }

    public function deleteMessage(int $mid): void
    {
        $this->messageService->deleteMessage($mid);
    }

    private function resolveStartConversationPayload(Request $request): array
    {
        // Get request data
        $requestData = json_decode($request->getContent(), true);

        // Get currently auth user
        $currentUser = $this->userProviderService->getUserProvider()->getCurrentUser();

        // Get user entities
        $participants = [];
        foreach ($requestData['participant_ids'] as $participantId)
        {
            $participants[] = $this->userProviderService->getUserProvider()->getUser($participantId);
        }

        // Get message data
        $message = $requestData['message'];

        return [
            'admin' => $currentUser,
            'participants' => $participants,
            'initialMessage' => [
                'from' => $currentUser,
                'body' => $message['body']
            ]
        ];
    }

    private function resolveSendMessagePayload(Request $request): array
    {
        $content = $request->getContent();
        $data = json_decode($content, true);

        return [
          'from' => $this->userProviderService->getUserProvider()->getCurrentUser(),
          'body' => $data['message']['body']
        ];
    }
}