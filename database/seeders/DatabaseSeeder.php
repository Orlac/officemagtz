<?php

namespace Database\Seeders;

use App\Application\User\UserService;
use App\Domain\Discount\DiscountRepository;
use DateTimeImmutable;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(UserService $users, DiscountRepository $discounts): void
    {
        $ids = [];
        foreach (['user1', 'user2'] as $login) {
            $ids[$login] = $users->exists($login) ? null : $users->create($login, 'password');
        }

        // Демо-скидки: действующая у user1 и просроченная у user2
        if ($ids['user1'] !== null) {
            $discounts->create($ids['user1'], 'DEMOACTIVE01', 15, new DateTimeImmutable('-2 hours'));
        }
        if ($ids['user2'] !== null) {
            $discounts->create($ids['user2'], 'DEMOEXPIRED1', 30, new DateTimeImmutable('-5 hours'));
        }
    }
}
