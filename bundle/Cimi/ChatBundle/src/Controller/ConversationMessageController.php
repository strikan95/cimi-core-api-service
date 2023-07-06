<?php

namespace Cimi\ChatBundle\Controller;

use Cimi\ChatBundle\Manager\ChatManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class ConversationMessageController extends AbstractController
{
    public function __construct(
        private readonly ChatManagerInterface $chatManager
    )
    {
    }

    #[Route('/api/v1/conversations/{id}/messages', name: 'cimi_chat_bundle.conversations.messages.get', methods: ['GET'])]
    public function getConversationMessages(int $id): JsonResponse
    {
        $response = $this->chatManager->getAllMessages($id);

        return $this->json($response['results'], Response::HTTP_CREATED, context: $response['context']);
    }

    #[Route('/api/v1/conversations/{id}/messages', name: 'cimi_chat_bundle.conversations.messages.add', methods: ['POST'])]
    public function sendMessage(int $id, Request $request): JsonResponse
    {
        $this->chatManager->sendMessage($id, $request);

        return $this->json([], Response::HTTP_CREATED);
    }
    #[Route('/api/v1/conversations/{cid}/messages/{mid}', name: 'cimi_chat_bundle.conversations.messages.delete', methods: ['DELETE'])]
    public function deleteMessage(int $cid, int $mid)
    {

    }
}