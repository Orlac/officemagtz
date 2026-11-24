<?php

namespace App\Console\Commands;

use App\Application\User\UserService;
use DomainException;
use Illuminate\Console\Command;

class CreateUserCommand extends Command
{
    protected $signature = 'user:create {login : Логин} {password? : Пароль (если не указан, будет запрошен)}';

    protected $description = 'Создать пользователя';

    public function handle(UserService $users): int
    {
        $login = trim((string) $this->argument('login'));
        $password = $this->argument('password') ?? $this->secret('Пароль');

        if ($login === '' || $password === null || $password === '') {
            $this->error('Логин и пароль не должны быть пустыми');

            return self::FAILURE;
        }

        try {
            $id = $users->create($login, $password);
        } catch (DomainException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info("Пользователь {$login} создан (id={$id})");

        return self::SUCCESS;
    }
}
