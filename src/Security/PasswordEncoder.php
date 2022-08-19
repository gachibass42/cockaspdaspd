<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class PasswordEncoder implements PasswordHasherInterface
{
    public function hash(string $plainPassword): string
    {
        return hash('sha256', $plainPassword);
    }

    public function verify(string $hashedPassword, string $plainPassword): bool
    {
        return $hashedPassword === $this->hash($plainPassword);
    }

    public function needsRehash(string $hashedPassword): bool
    {
        return false;
    }
}
