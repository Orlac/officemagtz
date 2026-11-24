<?php

declare(strict_types=1);

namespace App\Domain\Discount;

use DateTimeImmutable;

interface DiscountRepository
{
    /** Последняя скидка пользователя, полученная позже $after. */
    public function findLatestIssuedAfter(int $userId, DateTimeImmutable $after): ?Discount;

    public function findByCode(string $code, int $userId): ?Discount;

    public function create(int $userId, string $code, int $percent, DateTimeImmutable $issuedAt): Discount;
}
