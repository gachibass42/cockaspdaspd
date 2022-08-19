<?php

declare(strict_types=1);

namespace App\Security;

use App\Modules\UserProfile\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class TokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function supports(Request $request): ?bool
    {
        if ('api_v1_user_register' === $request->attributes->get('_route') && $request->isMethod('GET')) {
            return false;
        }
        if ('api_v1_user_login' === $request->attributes->get('_route') && $request->isMethod('GET')) {
            return false;
        }

        return true;
    }

    public function authenticate(Request $request): Passport
    {
        if (!str_starts_with($authorizationHeader = $request->headers->get('Authorization', ''), 'Bearer ')) {
            throw new AuthenticationException();
        }

        // skip beyond "Bearer "
        $token = trim(substr($authorizationHeader, 7));

        return new SelfValidatingPassport(new UserBadge($token, function ($userIdentifier) {
            $user = $this->userRepository->findOneBy(['apiToken' => $userIdentifier]);

            if ($user === null) {
                throw new UserNotFoundException();
            }

            return $user;
        }));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'error' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
