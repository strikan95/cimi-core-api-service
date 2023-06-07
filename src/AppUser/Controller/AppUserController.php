<?php

namespace App\AppUser\Controller;

use App\AppUser\AppUserService;
use App\AppUser\Dto\AppUser as AppUserDto;
use App\AppUser\Entity\AppUser as AppUserEntity;
use App\AppUser\UserProvider\CurrentUserProvider;
use App\Security\Auth0\UserManager;
use App\Security\Roles;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AppUserController extends AbstractController
{
    public function __construct(
        private readonly CurrentUserProvider $userProvider,
        private readonly AppUserService $appUserService,
        private readonly SerializerInterface  $serializer,
        private readonly ValidatorInterface $validator,
        private readonly UserManager $userManager
    ){
    }

    #[Route('/api/v1/user/me', name: 'api.users.get.current.profile', methods: ['GET'])]
    public function getCurrentUserProfile(): JsonResponse
    {
        $this->denyAccessUnlessGranted(Roles::ROLE_FULLY_REGISTERED);

        $entity = $this->userProvider->get();

        $dto = new AppUserDto($entity);

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['app_user_private', 'app_user_extended'])
            ->toArray();

        return $this->json($dto, Response::HTTP_OK, context:$context);
    }

    #[Route('/api/v1/user/{id}', name: 'api.users.get.profile.by.id', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        $entity = $this->appUserService->getById($id);

        $dto = new AppUserDto($entity);

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['app_user_extended'])
            ->toArray();

        return $this->json($dto, Response::HTTP_OK, context:$context);
    }

    #[Route('/api/v1/register', name: 'api.users.register', methods: ['POST'])]
    public function registerProfile(Request $request): JsonResponse
    {
        $content = $request->getContent();
        /** @var AppUserDto $dto */

        $dto = $this->serializer->deserialize
        (
            $content,
            AppUserDto::class,
            'json'
        );

        //Validate
        $errors = $this->validator->validate($dto, null, ['create']);
        if(count($errors) > 0)
        {
            throw new BadRequestHttpException('Error when validating.');
        }

        $entity = $this->appUserService->store($dto);

        return $this->json([], Response::HTTP_CREATED, ['Location' => '/user/'.$entity->getId()]);
    }

    #[Route('/api/v1/register/test', name: 'api.users.test', methods: ['POST'])]
    public function test(Request $request): JsonResponse
    {
        $this->userManager->createUser();


        return $this->json([], Response::HTTP_CREATED);
    }
}
