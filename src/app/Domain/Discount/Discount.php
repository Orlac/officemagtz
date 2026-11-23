<?php

declare(strict_types=1);

namespace App\Domain\Discount;

use DateTimeImmutable;

final readonly class Discount
{
    public function __construct(
        public int $id,
        public int $userId,
        public string $code,
        public int $percent,
        public DateTimeImmutable $issuedAt,
    ) {
    }
}
