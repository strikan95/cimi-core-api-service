<?php

namespace App\Security\Auth0;

use Auth0\SDK\Auth0;

class UserManager
{
    public function __construct(private readonly Auth0 $auth0)
    {

    }

    public function createUser() {
        $response = $this->auth0->management()->users()->create('Username-Password-Authentication', ['email' => 'bla@mail.com', 'password' => 'Test123!']);

        dd($response);
    }
}

/*"email": "john.doe@gmail.com",
  "phone_number": "+199999999999999",
  "user_metadata": {},
  "blocked": false,
  "email_verified": false,
  "phone_verified": false,
  "app_metadata": {},
  "given_name": "John",
  "family_name": "Doe",
  "name": "John Doe",
  "nickname": "Johnny",
  "picture": "https://secure.gravatar.com/avatar/15626c5e0c749cb912f9d1ad48dba440?s=480&r=pg&d=https%3A%2F%2Fssl.gstatic.com%2Fs2%2Fprofiles%2Fimages%2Fsilhouette80.png",
  "user_id": "abc",
  "connection": "Initial-Connection",
  "password": "secret",
  "verify_email": false,
  "username": "johndoe"*/