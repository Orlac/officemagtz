<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Domain\User\UserRepository;
use DomainException;
use Illuminate\Contracts\Hashing\Hasher;

final class UserService
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly Hasher $hasher,
    ) {
    }

    public function exists(string $login): bool
    {
        return $this->users->existsByLogin($login);
    }

    public function create(string $login, string $password): int
    {
        if ($this->users->existsByLogin($login)) {
            throw new DomainException("Пользователь «{$login}» уже существует");
        }

        return $this->users->create($login, $this->hasher->make($password));
    }
}
