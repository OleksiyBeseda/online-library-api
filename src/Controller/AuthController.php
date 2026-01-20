<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthController extends AbstractController
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $hasher;
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        JWTTokenManagerInterface $jwtManager
    ) {
        $this->em = $em;
        $this->hasher = $hasher;
        $this->jwtManager = $jwtManager;
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return $this->json(['error' => 'Email and password required'], 400);
        }

        $existingUser = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser) {
            return $this->json(['error' => 'User already exists'], 400);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->hasher->hashPassword($user, $password));
        $user->setRole('CLIENT');

        $this->em->persist($user);
        $this->em->flush();

        $token = $this->jwtManager->create($user);

        return $this->json(['token' => $token]);
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return $this->json(['error' => 'Email and password required'], 400);
        }

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user || !$this->hasher->isPasswordValid($user, $password)) {
            return $this->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $this->jwtManager->create($user);

        return $this->json(['token' => $token]);
    }
}
