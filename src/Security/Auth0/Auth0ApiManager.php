<?php

namespace App\Security\Auth0;

use App\Security\User\TokenUser;
use Auth0\SDK\API\Authentication;
use Auth0\SDK\API\Management;
use Auth0\SDK\Configuration\SdkConfiguration;
use Auth0\SDK\Exception\ArgumentException;
use Auth0\SDK\Exception\NetworkException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class Auth0ApiManager
{
    private SdkConfiguration $configuration;
    private Management $auth0Manager;

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

        $this->auth0Manager = new Management($this->configuration);
    }

    /**
     * @throws NetworkException
     * @throws ArgumentException
     */
    public function addUserRole(UserInterface $user, array $roles)
    {
        if(!$user instanceof TokenUser)
        {
            throw new \LogicException('User must be type of Auth0User');
        }

        $response = $this->auth0Manager->users()->addRoles($user->getUserIdentifier(), $roles);

        if ($response->getStatusCode() >= 400)
        {
            // Something went wrong
            throw new \HttpException('Error while updating user roles', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}