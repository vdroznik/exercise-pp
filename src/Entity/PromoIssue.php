<?php

namespace ExercisePromo\Entity;

use DateTimeImmutable;

class PromoIssue
{
    public function __construct(
        public int $promoId,
        public string $ip,
        public ?int $id = null,
        public ?DateTimeImmutable $createdAt = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['promo_id'],
            $data['ip'],
            $data['id'],
            date_create_immutable($data['created_at']),
        );
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'promo_id' => $this->promoId,
            'ip' => $this->ip,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
        ];
    }
}
