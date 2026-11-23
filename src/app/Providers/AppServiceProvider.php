<?php

namespace App\Providers;

use App\Domain\Discount\DiscountRepository;
use App\Domain\User\UserRepository;
use App\Infrastructure\Discount\DatabaseDiscountRepository;
use App\Infrastructure\User\DatabaseUserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DiscountRepository::class, DatabaseDiscountRepository::class);
        $this->app->bind(UserRepository::class, DatabaseUserRepository::class);
    }
}
