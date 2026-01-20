<?php

namespace App\Security;

use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class JwtAuthenticator implements AuthenticatorInterface
{
    private JWTTokenManagerInterface $jwtManager;
    private UserProviderInterface $userProvider;

    public function __construct(JWTTokenManagerInterface $jwtManager, UserProviderInterface $userProvider)
    {
        $this->jwtManager = $jwtManager;
        $this->userProvider = $userProvider;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization') &&
            str_starts_with($request->headers->get('Authorization'), 'Bearer ');
    }

    public function authenticate(Request $request): PassportInterface
    {
        $authHeader = $request->headers->get('Authorization');
        $jwt = str_replace('Bearer ', '', $authHeader);

        if (!$jwt) {
            throw new AuthenticationException('No JWT token provided');
        }

        // Декодируем токен и получаем email
        try {
            $payload = $this->jwtManager->decodeFromJsonWebToken($jwt);
        } catch (\Exception $e) {
            throw new AuthenticationException('Invalid JWT token');
        }

        if (!isset($payload['username'])) {
            throw new AuthenticationException('JWT token missing username');
        }

        return new SelfValidatingPassport(
            new UserBadge($payload['username'], function ($username) {
                return $this->userProvider->loadUserByUsername($username);
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, PassportInterface $passport, string $firewallName): ?JsonResponse
    {
        // ничего не возвращаем, Symfony продолжает обработку
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?JsonResponse
    {
        return new JsonResponse(['error' => $exception->getMessage()], 401);
    }

    public function start(Request $request, AuthenticationException $authException = null): ?JsonResponse
    {
        return new JsonResponse(['error' => 'Authentication Required'], 401);
    }
}
