<?php

namespace Cimi\ChatBundle\Controller;

use Cimi\ChatBundle\Manager\ChatManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    public function __construct(
        private readonly ChatManagerInterface $chatManager
    )
    {
    }

    #[Route('/api/v1/messages/{id}', name: 'cimi_chat_bundle.messages.delete', methods: ['DELETE'])]
    public function getConversationInfo(int $id): JsonResponse
    {
        $this->chatManager->deleteMessage($id);

        return $this->json([], Response::HTTP_OK);
    }
}