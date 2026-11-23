<?php

declare(strict_types=1);

namespace App\Application\Discount;

use App\Domain\Discount\Discount;
use App\Domain\Discount\DiscountRepository;
use DateTimeImmutable;

final class DiscountService
{
    private const MIN_PERCENT = 1;
    private const MAX_PERCENT = 50;
    private const REISSUE_LOCK = '-1 hour';   // в течение часа отдаём прежнюю скидку
    private const VALID_FOR = '-3 hours';     // код действует 3 часа

    public function __construct(private readonly DiscountRepository $discounts)
    {
    }

    public function issue(int $userId): Discount
    {
        $now = new DateTimeImmutable();

        $existing = $this->discounts->findLatestIssuedAfter($userId, $now->modify(self::REISSUE_LOCK));
        if ($existing !== null) {
            return $existing;
        }

        return $this->discounts->create(
            $userId,
            strtoupper(bin2hex(random_bytes(6))),
            random_int(self::MIN_PERCENT, self::MAX_PERCENT),
            $now,
        );
    }

    /** Возвращает процент скидки или null, если скидка недоступна. */
    public function check(int $userId, string $code): ?int
    {
        $discount = $this->discounts->findByCode(strtoupper(trim($code)));

        if ($discount === null
            || $discount->userId !== $userId
            || $discount->issuedAt < (new DateTimeImmutable())->modify(self::VALID_FOR)
        ) {
            return null;
        }

        return $discount->percent;
    }
}
