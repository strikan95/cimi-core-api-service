<?php

namespace App\Security\Auth0;

use Symfony\Component\Security\Core\User\UserInterface;

class Auth0User implements UserInterface
{
    private ?string $identifier = null;

    private array $roles = [];

    public function __construct(string $identifier, array $roles)
    {
        $this->identifier = $identifier;
        $this->roles = $roles;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(?string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_REGISTERED';

        return array_unique($roles);
    }


    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->identifier;
    }
}