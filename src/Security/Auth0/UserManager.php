<?php

namespace App\Security\Auth0;

use Auth0\SDK\Auth0;
use Auth0\SDK\Exception\ArgumentException;
use Auth0\SDK\Exception\NetworkException;

class UserManager
{
    public function __construct(private readonly Auth0 $auth0)
    {
    }

    public function fetchUserInfo(string $id): string
    {
        return $this->auth0
            ->management()
            ->users()
            ->get($id)
            ->getBody()
            ->getContents();
    }

    /**
     * @throws NetworkException
     * @throws ArgumentException
     */
    public function updateAppMetadata(string $id, array $metaData): \Psr\Http\Message\ResponseInterface
    {
        return $this->auth0->management()->users()->update($id, $metaData);
    }

}