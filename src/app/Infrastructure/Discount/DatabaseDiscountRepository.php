<?php

declare(strict_types=1);

namespace App\Infrastructure\Discount;

use App\Domain\Discount\Discount;
use App\Domain\Discount\DiscountRepository;
use DateTimeImmutable;
use Illuminate\Database\ConnectionInterface;

final class DatabaseDiscountRepository implements DiscountRepository
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    public function __construct(private readonly ConnectionInterface $db)
    {
    }

    public function findLatestIssuedAfter(int $userId, DateTimeImmutable $after): ?Discount
    {
        $row = $this->db->table('discounts')
            ->where('user_id', $userId)
            ->where('issued_at', '>', $after->format(self::DATE_FORMAT))
            ->orderByDesc('issued_at')
            ->first();

        return $row ? $this->hydrate($row) : null;
    }

    public function findByCode(string $code): ?Discount
    {
        $row = $this->db->table('discounts')->where('code', $code)->first();

        return $row ? $this->hydrate($row) : null;
    }

    public function create(int $userId, string $code, int $percent, DateTimeImmutable $issuedAt): Discount
    {
        $id = $this->db->table('discounts')->insertGetId([
            'user_id' => $userId,
            'code' => $code,
            'percent' => $percent,
            'issued_at' => $issuedAt->format(self::DATE_FORMAT),
        ]);

        return new Discount($id, $userId, $code, $percent, $issuedAt);
    }

    private function hydrate(object $row): Discount
    {
        return new Discount(
            (int) $row->id,
            (int) $row->user_id,
            $row->code,
            (int) $row->percent,
            new DateTimeImmutable($row->issued_at),
        );
    }
}
