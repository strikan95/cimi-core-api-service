<?php

namespace App\AppUser\Controller;

use App\AppUser\AppUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class AppUserController extends AbstractController
{
    public function __construct(
        private readonly AppUserService $appUserService,
    ){
    }

    #[Route('/api/v1/user/me', name: 'api.users.get.current.profile', methods: ['GET'])]
    #[IsGranted('ROLE_FULLY_REGISTERED')]
    public function getCurrentUserProfile(): JsonResponse
    {
        $dto = $this->appUserService->getMyProfile();

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['app_user_private', 'app_user_extended'])
            ->toArray();

        return $this->json($dto, Response::HTTP_OK, context:$context);
    }

    #[Route('/api/v1/user/{id}', name: 'api.users.get.profile.by.id', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $dto = $this->appUserService->getById($id, true);

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['app_user_extended'])
            ->toArray();

        return $this->json($dto, Response::HTTP_OK, context: $context);
    }

    #[Route('/api/v1/user/create', name: 'api.users.create.profile', methods: ['POST'])]
    #[IsGranted('ROLE_TOKEN_USER')]
    public function createProfile(Request $request): JsonResponse
    {
        $entity = $this->appUserService->createProfile($request);
        return $this->json([], Response::HTTP_CREATED, ['Location' => '/user/'.$entity->getId()]);
    }

    #[Route('/api/v1/user/{id}/update', name: 'api.users.update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $appUser = $this->appUserService->update($id, $request);
        return $this->json([], Response::HTTP_NO_CONTENT, ['Location' => '/user/' . $appUser->getId()]);
    }
}
