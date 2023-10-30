<?php

declare(strict_types=1);

namespace ExercisePromo\Repository;

use Doctrine\DBAL\Connection;
use ExercisePromo\Entity\Promo;
use ExercisePromo\Entity\PromoIssue;

class PromoRepository
{
    public function __construct(
        private Connection $dbal,
        private IpRepository $ipRepo,
    ) {}

    public function find(int $id): ?Promo
    {
        $result = $this->dbal->fetchAssociative('SELECT id, code FROM promos WHERE id = :id', ['id' => $id]);

        if (!$result) {
            return null;
        }

        return Promo::fromArray($result);
    }

    public function getIssuedPromoCode(int $promoId): ?string
    {
        $query = "SELECT code FROM promos p INNER JOIN promos_issues pi ON p.id = pi.promo_id WHERE p.id = :id";
        $result = $this->dbal->fetchOne($query, ['id' => $promoId]);

        if (!$result) {
            return null;
        }

        return $result;
    }

    public function getNextPromoId(): int
    {
        $this->dbal->executeStatement("UPDATE sequences SET current = (@next := current + 1) WHERE name = 'promo'");

        return $this->dbal->fetchOne("SELECT @next");
    }

    public function trackPromoIssue(string $ip): bool
    {
        $ret = $this->dbal->executeStatement('INSERT promos_issues SET promo_id = @next, ip=:ip', ['ip' => $ip]);

        return $ret > 0;
    }
}
