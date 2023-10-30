<?php

namespace ExercisePromo\Entity;

class Promo
{
    public function __construct(
        public string $code,
        public ?int $id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['code'],
            $data['id'],
        );
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
        ];
    }
}
