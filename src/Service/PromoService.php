<?php

declare(strict_types=1);

namespace ExercisePromo\Service;

use Doctrine\DBAL\Connection;
use ExercisePromo\Repository\IpRepository;
use ExercisePromo\Repository\PromoRepository;

class PromoService
{
    public function __construct(
        private PromoRepository $repo,
        private IpRepository $ipRepo,
        private Connection $dbal,
    ) {}

    public function getIssuedPromoCode(int $promoId): ?string
    {
        return $this->repo->getIssuedPromoCode($promoId);
    }

    public function issuePromoCode(string $ip): ?string
    {
        $this->dbal->beginTransaction();

        $nextPromoId = $this->repo->getNextPromoId();
        $promo = $this->repo->find($nextPromoId);

        if (!$promo) {
            $this->dbal->rollBack();

            return null;
        }

        $this->repo->trackPromoIssue($ip);
        $this->ipRepo->incrementCountForIp($ip);

        $this->dbal->commit();

        return $promo->code;
    }
}
