<?php

declare(strict_types=1);

namespace App\Domain\User;

interface UserRepository
{
    public function existsByLogin(string $login): bool;

    public function create(string $login, string $passwordHash): int;
}
