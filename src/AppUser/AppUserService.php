<?php

namespace App\AppUser;

use App\AppUser\Dto\AppUser as AppUserDto;
use App\AppUser\Entity\AppUser as AppUserEntity;
use App\AppUser\Entity\Factory\AppUserFactory;
use App\AppUser\Repository\AppUserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppUserService
{
    public function __construct
    (
        private readonly AppUserRepository $appUserRepository
    ){
    }

    public function getById($id): AppUserEntity
    {
        $entity = $this->appUserRepository->find($id);

        if(null === $entity)
        {
            throw new NotFoundHttpException("User with id ".$id." couldn't be found.");
        }

        return $entity;
    }

    public function store(AppUserDto $dto): AppUserEntity
    {
        $entity = AppUserFactory::build($dto);
        $this->appUserRepository->save($entity, true);

        return $entity;
    }


    public function update(AppUserDto $dto): AppUserEntity
    {
        if(null === $dto->getId())
        {
            throw new \LogicException('Id not provided');
        }

        $entity = AppUserFactory::build(
            $dto,
            $this->getById($dto->getId())
        );
        $this->save($entity);

        return $entity;
    }

    public function delete(int $id): void
    {
        $entity = $this->appUserRepository->find($id);

        if(null === $entity)
        {
            throw new NotFoundHttpException("User with id ".$id." couldn't be found.");
        }

        $this->appUserRepository->remove($entity, true);
    }

    private function save(AppUserEntity $entity): void
    {
        $this->appUserRepository->save($entity, true);
    }
}