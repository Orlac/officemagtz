<?php

declare(strict_types=1);

namespace App\Infrastructure\User;

use App\Domain\User\UserRepository;
use Illuminate\Database\ConnectionInterface;

final class DatabaseUserRepository implements UserRepository
{
    public function __construct(private readonly ConnectionInterface $db)
    {
    }

    public function existsByLogin(string $login): bool
    {
        return $this->db->table('users')->where('login', $login)->exists();
    }

    public function create(string $login, string $passwordHash): int
    {
        $now = now();

        return $this->db->table('users')->insertGetId([
            'login' => $login,
            'password' => $passwordHash,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
