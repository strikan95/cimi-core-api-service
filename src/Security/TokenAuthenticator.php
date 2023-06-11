<?php

namespace App\Security;

use App\Services\JWTServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class TokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly JWTServiceInterface $jwtService
    )
    {
    }

    public function supports(Request $request): ?bool
    {
        return null !== $request->get('token') || ($request->headers->has('Authorization') && stripos((string) $request->headers->get('Authorization'), 'Bearer ') === 0);
    }

    public function authenticate(Request $request): Passport
    {
        $token = $this->jwtService->extractBearerTokenFromRequest($request);

        return new SelfValidatingPassport(new UserBadge($token));
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
}