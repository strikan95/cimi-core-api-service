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

    #[Route('/chat', name: 'cimi_chat_bundle', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $content = $request->getContent();
        $data = json_decode($content);


        return $this->json(['message' => 'hello'], Response::HTTP_OK);
    }
}