<?php

namespace App\Security\Auth0;

use Auth0\SDK\Auth0;
use Auth0\SDK\Configuration\SdkConfiguration;
use Auth0\SDK\Token;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class Auth0Authenticator extends AbstractAuthenticator
{
    private Auth0 $auth0;

    public function __construct(
        string $auth0Domain,
        string $auth0ClientId,
        string $auth0ClientSecret,
        string $auth0Audience,
        string $cookieSecret,
        string $loginCallback
    ){
        $configuration = new SdkConfiguration(
            domain: $auth0Domain,
            clientId: $auth0ClientId,
            redirectUri: $loginCallback,
            clientSecret: $auth0ClientSecret,
            audience: [$auth0Audience],
            cookieSecret: $cookieSecret,
        );

        $this->auth0 = new Auth0($configuration);
    }

    public function supports(Request $request): ?bool
    {
        return
            null !== $request->get('token') ||
            (
                $request->headers->has('Authorization') &&
                stripos((string) $request->headers->get('Authorization'), 'Bearer ') === 0
            );
    }

    public function authenticate(Request $request): Passport
    {
        $token = $this->extractToken($request);

        $userToken = $this->auth0->decode(
            token: $token,
            tokenType: Token::TYPE_TOKEN
        );

        $bla = $userToken->toArray();

        return new SelfValidatingPassport(
            new UserBadge(json_encode(['user' => $userToken->toArray()], JSON_THROW_ON_ERROR))
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $response = [
            'errors' => [
                (object) [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'title' => 'Authorization failed',
                    'detail' => strtr($exception->getMessageKey(), $exception->getMessageData())
                ]
            ]
        ];

        return new JsonResponse($response, Response::HTTP_UNAUTHORIZED);
    }

    private function extractToken(Request $request): string
    {
        // Extract any available value from the authorization header
        $param = $request->get('token');
        $header = trim($request->headers->get('Authorization', ''));
        $token = $param ?? $header;
        $usingHeader = null === $param;

        // Ensure the 'authorization' header is present in the request
        if ('' === $token) {
            throw new AuthenticationException('`Authorization` header not present.');
        }

        // Ensure the 'authorization' header includes a bearer prefixed JSON web token.
        if ($usingHeader && 0 !== stripos($token, 'bearer ')) {
            throw new AuthenticationException('`Authorization` header is malformed.');
        }

        // Strip the 'bearer' portion of the authorization string.
        return str_ireplace('bearer ', '', $token);
    }
}