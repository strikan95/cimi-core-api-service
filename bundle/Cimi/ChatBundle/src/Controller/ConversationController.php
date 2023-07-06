<?php

namespace Cimi\ChatBundle\Controller;

use Cimi\ChatBundle\ChatUserProvider\ChatUserProviderInterface;
use Cimi\ChatBundle\ChatUserProvider\ChatUserProviderService;
use Cimi\ChatBundle\Manager\ChatManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class ConversationController extends AbstractController
{
    public function __construct(
        private readonly ChatManagerInterface $chatManager
    )
    {
    }

    #[Route('/api/v1/conversations', name: 'cimi_chat_bundle.conversations.start', methods: ['POST'])]
    public function startConversation(Request $request): JsonResponse
    {
        $conversation = $this->chatManager->startConversation($request);
        return $this->json(['location' => $conversation->getId()], Response::HTTP_CREATED);
    }

    #[Route('/api/v1/conversations/me', name: 'cimi_chat_bundle.conversations.me.all', methods: ['GET'])]
    public function getCurrentUserConversations(): JsonResponse
    {
        $response = $this->chatManager->getCurrentUserConversations();
        return $this->json($response['results'], Response::HTTP_CREATED, context: $response['context']);
    }

    #[Route('/api/v1/conversations/{id}/info', name: 'cimi_chat_bundle.conversations.info', methods: ['GET'])]
    public function getConversationInfo(int $id, Request $request): JsonResponse
    {
        $response = $this->chatManager->getConversationInfo($id);

        return $this->json($response['results'], Response::HTTP_CREATED, context: $response['context']);
    }
}