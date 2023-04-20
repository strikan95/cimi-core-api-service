<?php

namespace App\SecurityUser\Entity;

use App\SecurityUser\Repository\SecurityUserRepository;
use App\Profile\Landlord\Entity\Landlord as LandlordEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: SecurityUserRepository::class)]
class SecurityUser implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    private ?string $auth0_id = null;

    #[ORM\Column]
    private array $roles = [];

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getAuth0Id(): ?string
    {
        return $this->auth0_id;
    }

    public function setAuth0Id(?string $auth0_id): void
    {
        $this->auth0_id = $auth0_id;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->auth0_id;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
