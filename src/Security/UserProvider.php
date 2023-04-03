<?php

namespace App\Security;


use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function __construct
    (
        private readonly UserRepository $userRepository
    )
    {
    }

    public function supportsClass($class): bool
    {
        return $class instanceof UserInterface || \is_subclass_of($class, UserInterface::class);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->userRepository->findOneBy(['authIdentifier' => $identifier]);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return throw new \LogicException('Only stateless');
    }
}