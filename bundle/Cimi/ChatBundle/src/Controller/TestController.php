<?php

namespace Cimi\ChatBundle\Controller;

use Cimi\ChatBundle\Services\ChatService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    public function __construct(
        private readonly ChatService $chatService
    )
    {
    }

    #[Route('/api/v1/chat', name: 'cimi_chat_bundle', methods: ['POST'])]
    public function chat(Request $request): JsonResponse
    {
        $conversation = $this->chatService->startChat($request);

        return $this->json(['id' => $conversation->getId()], Response::HTTP_CREATED);
    }

    #[Route('/api/v1/conversation/{id}', name: 'cimi_chat_bundle.send.message', methods: ['POST'])]
    public function send(int $id, Request $request): JsonResponse
    {
        $conversation = $this->chatService->sendMessage($id, $request);

        return $this->json(['id' => $conversation->getId()], Response::HTTP_CREATED);
    }
}