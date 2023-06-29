<?php

namespace App\AppUser;

use App\AppUser\Dto\AppUser as AppUserDto;
use App\AppUser\Dto\AppUserInputDto;
use App\AppUser\Dto\AppUserOutputDto;
use App\AppUser\Entity\AppUser as AppUserEntity;
use App\AppUser\Entity\Factory\AppUserFactory;
use App\AppUser\Repository\AppUserRepository;
use App\Security\Auth0\UserManager;
use Auth0\SDK\Exception\ArgumentException;
use Auth0\SDK\Exception\NetworkException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AppUserService
{
    public function __construct
    (
        private readonly AppUserRepository $appUserRepository,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface  $serializer,
        private readonly Security $security,
        private readonly AppUserFactory $userFactory,
        private readonly UserManager $userManager
    ){
    }

    public function getMyProfile(): AppUserOutputDto
    {
        $me = $this->appUserRepository->findByAuthIdentifier(
            $this->security->getToken()->getUserIdentifier()
        );
        if(null === $me)
            throw new NotFoundHttpException("Profile couldnt be found");

        return new AppUserOutputDto($me);
    }

    public function getById($id, bool $asDto = false): AppUserOutputDto|AppUserEntity
    {
        $appUser = $this->appUserRepository->findByLocalId($id);
        if(null === $appUser)
            throw new NotFoundHttpException("User with id ".$id." couldn't be found.");

        if($asDto)
            return new AppUserOutputDto($appUser);

        return $appUser;
    }

    public function createProfile(Request $request)
    {
        $authIdentifier = $this->security->getToken()->getUserIdentifier();
        $user = $this->appUserRepository->findByAuthIdentifier($authIdentifier);
        if(null !== $user)
            throw new BadRequestHttpException('User with same id already exists');

        $content = $request->getContent();
        $dto = $this->getValidatedDto(
            $content,
            ['create'],
            AppUserInputDto::class
        );

        $entity = $this->userFactory->buildOrUpdate(
            $dto,
            null,
            ['auth0Identifier' => $authIdentifier]
        );

        $this->dbSave($entity);

        $metaData = [
            'app_metadata' => [
                'local_id' => $entity->getId(),
                'role' => $entity->getRole(),
            ]
        ];
        $this->updateAppMetadata($entity, $metaData);

        return $entity;
    }


    public function update(int $id, Request $request): AppUserEntity
    {
        // Fetch entity
        $appUser = $this->appUserRepository->findByLocalId($id);
        if(null == $appUser)
            throw new NotFoundHttpException("User with id ".$id." couldn't be found.");

                if(!$this->security->isGranted('update:profile', $appUser))
                    throw new UnauthorizedHttpException('Error: Unauthorized action');

        // Deserialize and validate DTO
        $content = $request->getContent();
        $dto = $this->getValidatedDto(
            $content, ['update'], AppUserInputDto::class
        );

        // Update entity
        $updatedUser = $this->userFactory->buildOrUpdate($dto, $appUser);

        $this->dbSave($updatedUser);
        return $updatedUser;
    }

    private function dbSave(AppUserEntity $entity): void
    {
        $this->appUserRepository->save($entity, true);
    }

    private function dbRemove(AppUserEntity $entity): void
    {
        $this->appUserRepository->remove($entity, true);
    }

    private function updateAppMetadata($entity, $metaData): void
    {
        try {
            $response = $this->userManager->updateAppMetadata($entity->getUserIdentifier(), $metaData);
        } catch (ArgumentException|NetworkException $e) {
            $this->dbRemove($entity);
            throw new BadRequestHttpException('Error while updating user metadata');
        }
    }

    private function getValidatedDto(string $rawJson, array $groups, string $className)
    {
        $dto = $this->deserializeTo($rawJson, $className, $groups);
        if(count($this->validateDto($dto, $groups)) > 0)
        {
            throw new BadRequestHttpException('Error when validating.');
        }

        return $dto;
    }

    private function deserializeTo(string $content, string $className, array $groups)
    {
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups($groups)
            ->toArray();

        return $this->serializer->deserialize
        (
            $content,
            $className,
            'json',
            $context
        );
    }

    private function validateDto($dto, array $context): ConstraintViolationListInterface
    {
        return $this->validator->validate($dto, null, $context);
    }
}