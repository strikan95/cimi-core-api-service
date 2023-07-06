<?php

namespace Cimi\ChatBundle\Controller;

use Cimi\ChatBundle\Manager\ChatManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class ConversationParticipantController extends AbstractController
{
    public function __construct(
        private readonly ChatManagerInterface $chatManager
    )
    {
    }

    #[Route('/api/v1/conversations/{id}/participants', name: 'cimi_chat_bundle.participants.add', methods: ['PUT'])]
    public function addParticipant(int $id, Request $request): JsonResponse
    {
        return $this->json([], Response::HTTP_CREATED);
    }

    #[Route('/api/v1/conversations/{cid}/participants/{pid}', name: 'cimi_chat_bundle.participants.add', methods: ['DELETE'])]
    public function removeParticipant(int $cid, int $pid, Request $request): JsonResponse
    {
        return $this->json([], Response::HTTP_CREATED);
    }
}