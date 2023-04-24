<?php

namespace App\Security\Auth0;

use Auth0\SDK\API\Authentication;
use Auth0\SDK\API\Management;
use Auth0\SDK\Configuration\SdkConfiguration;
use Psr\Http\Message\ResponseInterface;
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
        if (!$user instanceof Auth0User) {
            throw new UnsupportedUserException();
        }

        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return Auth0User::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->fetchUser($identifier);
    }

    private function fetchUser(string $identifier): UserInterface
    {
        $userData = json_decode($identifier, true);
        $userRoles = $this->resolveGetUserRolesAction($userData['user']['sub']);

        return new Auth0User(
            $userData['user']['sub'],
            $userRoles
        );
    }

    private function resolveGetUserRolesAction($userIdentifier): array
    {
        $auth0Management = new Management($this->configuration);
        $userRoleResponse = $auth0Management->users()->getRoles($userIdentifier);
        return $this->decodeRoleResponse($userRoleResponse);
    }

    private function decodeRoleResponse(ResponseInterface $response): array
    {
        $rolesDecoded = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

        $roles = [];
        foreach ($rolesDecoded as $role) {
            $roles[] = $role->name;
        }

        return $roles;
    }
}