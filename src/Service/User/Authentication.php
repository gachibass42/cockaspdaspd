<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Modules\UserProfile\UserRepository;
use App\Security\PasswordEncoder;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Authentication
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private PasswordEncoder $passwordEncoder
    ) {}

    public function register(): ?User
    {
        $qb = $this->
        userRepository->
        createQueryBuilder('u');

        $nextId = $this->getNextId($qb);

        $login = $this->getNextLogin($qb);

        $user = new User();
        $user
            ->setId($nextId)
            ->setUsername($login)
            ->setPassword($this->generatePassword())
            ->setApiToken($this->generateApiToken())
            ->setApiTokenExpiresAt($this->getApiTokenExpirationDatetime())
        ;
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function getJwt(string $userId): ?JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $userId]);
        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $algorithm    = new Sha256();
        $signingKey   = InMemory::base64Encoded(
            'hiG8DlOKvtih6AxlZn5XKImZ06yu8I3mkOzaJrEuW8yAv8Jnkw330uMt8AEqQ5LB'
        );

        $now   = new DateTimeImmutable();
        $token = $tokenBuilder
            // Configures the issuer (iss claim)
            ->withHeader('algorithm', 'sha256')
            ->withClaim('username', $user ->getUsername())
            ->withClaim('password', $user ->getPassword())
            // Builds a new token
            ->getToken($algorithm, $signingKey);
        return new JsonResponse([
            'token' => $token -> toString(),
            'algorithm' => $token->headers()->get('algorithm'),
            'username' => $token->claims()->get('username'),
            'password' => $token->claims()->get('password'),
            'id' => $user->getId()]);
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

    private function generatePassword(): string
    {
        return bin2hex(random_bytes(8));
    }

    private function getApiTokenExpirationDatetime(): \DateTimeImmutable
    {
        return (new \DateTimeImmutable())->modify('+1 month');
    }

    public function getNextId(\Doctrine\ORM\QueryBuilder $qb): int
    {
        return (int)$qb
            ->select('MAX(u.id)')
            ->getQuery()
            ->getResult() + 1;
    }

    public function getNextLogin(\Doctrine\ORM\QueryBuilder $qb): string
    {
        $current_max_length_array = $qb
            -> select('u.username')
        ->orderBy('LENGTH(u.username)', 'DESC')
        ->setMaxResults(1)
        ->getQuery()
        ->getResult();
        if (count($current_max_length_array) == 0)
            return 'blinker1';
        $current_max_length = strval(strlen($current_max_length_array[0]['username']));
        $current_last_username = $qb
            ->select('u.username')
            ->where('LENGTH(u.username)=:length')
            ->setParameter('length', $current_max_length)
            ->orderBy('u.username'  , 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        $current_number = (int)substr($current_last_username[0]['username'], 7);
        return 'blinker' . ($current_number + 1);
    }
}
