<?php

declare(strict_types=1);

namespace App\Application\Discount;

use App\Domain\Discount\Discount;
use App\Domain\Discount\DiscountRepository;
use DateTime;
use DateTimeImmutable;

final class DiscountService
{
    public function __construct(private readonly DiscountRepository $discounts)
    {
    }

    public function issue(int $userId): Discount
    {
        $now = new DateTimeImmutable();

        $reissueLockHours = (int) config('discount.reissue_lock_hours');
        $existing = $this->discounts->findLatestIssuedAfter($userId, $now->modify("-{$reissueLockHours} hours"));
        if ($existing !== null) {
            return $existing;
        }

        return $this->discounts->create(
            $userId,
            strtoupper(bin2hex(random_bytes(6))),
            random_int((int) config('discount.min_percent'), (int) config('discount.max_percent')),
            $now,
        );
    }

    /** Возвращает скидку или null, если скидка недоступна. */
    public function getDiscount(int $userId, string $code): ?Discount
    {
        $discount = $this->discounts->findByCode(strtoupper(trim($code)), $userId);

        if ($discount === null) {
            return null;
        }

        $validHours = (int) config('discount.valid_hours');
        if ($discount->issuedAt < (new DateTime())->modify("-{$validHours} hours")) {
            return null;
        }

        return $discount;
    }

    /** Дата и время, до которой действует скидка. */
    public function expiresAt(Discount $discount): DateTimeImmutable
    {
        $validHours = (int) config('discount.valid_hours');

        return $discount->issuedAt->modify("+{$validHours} hours");
    }
}
