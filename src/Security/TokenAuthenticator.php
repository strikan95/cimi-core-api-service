<?php

namespace App\Security;

use App\SecurityUser\Entity\User;
use App\SecurityUser\Repository\UserRepository;
use Auth0\Symfony\Contracts\Security\AuthorizerInterface;
use Auth0\Symfony\Security\Authorizer;
use Auth0\Symfony\Service;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;


class TokenAuthenticator extends AbstractAuthenticator implements AuthorizerInterface
{

    public function __construct
    (
        private readonly Authorizer $inner,
        private readonly UserRepository $userRepository
    )
    {
    }

    public function getService(): Service
    {
        return $this->inner->getService();
    }

    public function getConfiguration(): array
    {
        return $this->inner->getConfiguration();
    }

    public function supports(Request $request): ?bool
    {
        return $this->inner->supports($request);
    }

    public function authenticate(Request $request): Passport
    {
        // Extract any available value from the authorization header
        $param = $request->get('token', null);
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
        $token = str_ireplace('bearer ', '', $token);

        // Decode, validate and verify token.
        $token = $this->getService()->getSdk()->decode(
            token: $token,
            tokenType: \Auth0\SDK\Token::TYPE_TOKEN
        );

        $sub = $token->getSubject();
        if(!$this->userRepository->findBy(['authIdentifier' => $sub]))
        {
            // Create user
            $user = new user();
            $user->setAuthIdentifier($sub);

            $this->userRepository->save($user, true);
        }

        return new SelfValidatingPassport(new UserBadge($sub));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return $this->inner->onAuthenticationSuccess($request, $token, $firewallName);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return $this->inner->onAuthenticationFailure($request, $exception);
    }
}
