<?php

namespace App\AppUser\Controller;

use App\AppUser\AppUserService;
use App\AppUser\Dto\AppUser as AppUserDto;
use App\AppUser\Entity\AppUser as AppUserEntity;
use App\AppUser\UserProvider\CurrentUserProvider;
use App\Security\Auth0\Auth0ApiManager;
use App\Security\Roles;
use Auth0\SDK\Exception\ArgumentException;
use Auth0\SDK\Exception\NetworkException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
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
        private readonly Auth0ApiManager $auth0ApiManager
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
        $this->denyAccessUnlessGranted(Roles::ROLE_FULLY_REGISTERED);

        $entity = $this->appUserService->getById($id);

        $dto = new AppUserDto($entity);

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['app_user_extended'])
            ->toArray();

        return $this->json($dto, Response::HTTP_OK, context:$context);
    }

    #[Route('/api/v1/user', name: 'api.users.register.profile', methods: ['POST'])]
    public function registerUserProfile(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entity = $this->resolveUserRegisterAction($request);

        $this->updateUserRoles($this->getUser(), [Roles::ROLE_FULLY_REGISTERED_ID]);

        return $this->json([], Response::HTTP_CREATED, ['Location' => '/user/'.$entity->getId()]);
    }

    #[Route('/api/v1/user/landlord', name: 'api.users.register.profile', methods: ['POST'])]
    public function registerLandlordProfile(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entity = $this->resolveUserRegisterAction($request);

        $this->updateUserRoles($this->getUser(), [Roles::ROLE_FULLY_REGISTERED_ID, Roles::ROLE_LANDLORD_ID]);

        return $this->json([], Response::HTTP_CREATED, ['Location' => '/user/'.$entity->getId()]);
    }

    private function resolveUserRegisterAction(Request $request): AppUserEntity
    {
        $content = $request->getContent();
        /** @var AppUserDto $dto */
        $dto = $this->serializer->deserialize
        (
            $content,
            AppUserDto::class,
            'json'
        );
        $dto->setUserIdentifier($this->getUser()->getUserIdentifier());

        //Validate
        $errors = $this->validator->validate($dto, null, ['create']);
        if(count($errors) > 0)
        {
            throw new BadRequestHttpException('Error when validating.');
        }

        return $this->appUserService->store($dto);
    }

    private function updateUserRoles(UserInterface $user, array $roles): void
    {
        try {
            $this->auth0ApiManager->addUserRole($this->getUser(), [Roles::ROLE_FULLY_REGISTERED_ID, Roles::ROLE_LANDLORD_ID]);
        } catch (ArgumentException $e) {
            throw new \LogicException($e->getMessage());
        } catch (NetworkException $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }
}
