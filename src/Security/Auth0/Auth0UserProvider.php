<?php

namespace App\Security\Auth0;

use App\SecurityUser\Entity\SecurityUser as User;
use App\SecurityUser\Repository\SecurityUserRepository;
use Auth0\SDK\API\Authentication;
use Auth0\SDK\Configuration\SdkConfiguration;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;


class Auth0UserProvider implements UserProviderInterface
{
    private SdkConfiguration $configuration;
    private Authentication $auth0Authentication;

    public function __construct(
        string $auth0Domain,
        string $auth0ClientId,
        string $auth0ClientSecret,
        string $auth0Audience,
        string $cookieSecret,
        string $loginCallback,
        private readonly SecurityUserRepository $userRepository
    ){
        $this->configuration = new SdkConfiguration(
            domain: $auth0Domain,
            clientId: $auth0ClientId,
            redirectUri: $loginCallback,
            clientSecret: $auth0ClientSecret,
            audience: [$auth0Audience],
            cookieSecret: $cookieSecret,
        );

        $this->auth0Authentication = new Authentication($this->configuration);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException();
        }

        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->fetchUser($identifier);
    }

    private function fetchUser(string $identifier): UserInterface
    {
        $userData = json_decode($identifier, true);

        $user = $this->userRepository->findByIdentifier($userData['user']['sub']);

        if(null === $user)
        {
            $user = $this->storeUser($userData);
        }

        //retrieve api management token
        $response = $this->auth0Authentication->clientCredentials();
        $managementTokenResponse = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

        //set api management token
        $this->configuration->setManagementToken($managementTokenResponse->access_token);

        //retrieve roles
        //$auth0Management = new Management($this->configuration);
        //$userRoleResponse = $auth0Management->users()->getRoles($userData['user']['sub']);
        //$userRoles = json_decode($userRoleResponse->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

/*        return new User(
            $userData['user']['sub'],
            $userData['user']['nickname'] ?? null,
            $userData['user']['email'] ?? null,
            isset($userData['user']['updated_at']) ? new \DateTimeImmutable($userData['user']['updated_at']) : null,
            $userData['accessToken'] ?? null,
            $userData['accessTokenExpired'] ?? null,
            //$this->roles($userRoles)
        );*/

        return $user;
    }

    private function storeUser(array $userData): User
    {
        $authId = $userData['user']['sub'];

        $user = new User();
        $user->setAuth0Id($authId);
        $user->setRoles(['ROLE_USER']);

        $this->userRepository->save($user, true);

        return $user;
    }

    private function roles(array $userRoles): array
    {
        $roles = [];
        foreach ($userRoles as $role) {
            $roles[] = $role->name;
        }

        return $roles;
    }
}