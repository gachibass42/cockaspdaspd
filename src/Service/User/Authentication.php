<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Modules\UserProfile\UserRepository;
use App\Security\PasswordEncoder;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Authentication
{
    private const REGISTER_URL = 'https://proc.minegoat.ru/api/register';

    public function __construct(
        private LoggerInterface $logger,
        private HttpClientInterface $client,
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private PasswordEncoder $passwordEncoder
    ) {}

    public function register(): ?User
    {
        try {
            $response = $this->client
                ->request(Request::METHOD_GET, self::REGISTER_URL)
                ->toArray()
            ;
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), ['method' => __METHOD__]);
            return null;
        }

        $user = new User();
        $user
            ->setId($response['user']['id'])
            ->setUsername($response['user']['login'])
            ->setPassword($response['user']['password'])
            ->setApiToken($this->generateApiToken())
            ->setApiTokenExpiresAt($this->getApiTokenExpirationDatetime())
        ;
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function login(string $username, string $hashedPassword): ?array
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);
        if ($user === null) {
            return null;
        }

        if ($hashedPassword !== $this->passwordEncoder->hash($user->getPassword())) {
            return null;
        }

        if ($user->isTokenExpired()) {
            $user
                ->setApiToken($this->generateApiToken())
                ->setApiTokenExpiresAt($this->getApiTokenExpirationDatetime())
            ;
            $this->entityManager->flush();
        }

        return [
            'apiToken' => $user->getApiToken()
        ];
    }

    private function generateApiToken(): string
    {
        return bin2hex(random_bytes(64));
    }

    private function getApiTokenExpirationDatetime(): \DateTimeImmutable
    {
        return (new \DateTimeImmutable())->modify('+1 month');
    }
}
