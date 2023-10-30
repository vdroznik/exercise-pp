<?php

declare(strict_types=1);

namespace ExercisePromo\Repository;

use Doctrine\DBAL\Connection;
use ExercisePromo\Entity\Promo;

class PromoIssueRepository
{
    public function __construct(
        private Connection $dbal
    ) {}

    public function create(Promo $promo): bool
    {
        $result = $this->dbal->insert('promos_issues', array_filter($promo->asArray()));

        return ((int) $result) > 0;
    }
}
